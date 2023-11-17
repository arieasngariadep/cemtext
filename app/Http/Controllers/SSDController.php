<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Alert;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CemtextModel;
use Rap2hpoutre\FastExcel\FastExcel;

class SSDController extends Controller
{
    public function formUploadSSD(Request $request, CemtextModel $cemtext)
    {
        $role = $request->session()->get('role');
        $alert = $request->session()->get('alert');
        $alertSuccess = $request->session()->get('alertSuccess');
        $alertInfo = $request->session()->get('alertInfo');

        if($alertSuccess){
            $showalert = Alert::alertSuccess($alertSuccess);
        }else if($alertInfo){
            $showalert = Alert::alertinfo($alertInfo);
        }else{
            $showalert = Alert::alertDanger($alert);
        }

        $data = array(
            'role' => $role,
            'alert' => $showalert,
        );
        return view('SDD.formUploadSSD', $data);
    }

    public function formDownloadReportSDD(Request $request)
    {
        $role = $request->session()->get('role');
        $alert = $request->session()->get('alert');
        $alertSuccess = $request->session()->get('alertSuccess');
        $alertInfo = $request->session()->get('alertInfo');

        if($alertSuccess){
            $showalert = Alert::alertSuccess($alertSuccess);
        }else if($alertInfo){
            $showalert = Alert::alertinfo($alertInfo);
        }else{
            $showalert = Alert::alertDanger($alert);
        }

        $userId = $request->session()->get('userId');
        $jumlah = CemtextModel::getDataSDDCount($userId);

        $data = array(
            'role' => $role,
            'alert' => $showalert,
            'jumlah' => $jumlah,
        );
        return view('SDD.form_download_report_SDD', $data);
    }

    public function prosesUploadFile(Request $request)
    {
        $userId = $request->userId;
        $filename = $request->file('filename');
        $nama_filename = 'SDD_'.$userId.'.txt';
        $filename->move(\base_path() ."/public/Import/SSD", $nama_filename);
        $ekstensi_file = $request->file('filename')->getClientOriginalExtension();

        DB::statement('drop table if exists ssd_temp_'.$userId.', ssd_temp2_'.$userId.', ssd_'.$userId.'');

        DB::statement('create table ssd_temp_'.$userId.' (ssd varchar(700)) engine myisam');

        $sql = 'load data infile "C:/xampp/htdocs/cemtext/public/Import/SSD/SDD_'.$userId.'.txt" into table ssd_temp_'.$userId.'';
        DB::connection()->getPdo()->exec($sql);

        DB::statement('create table ssd_temp2_'.$userId.'
        (
            SEQ_NO varchar(6),
            ACCOUNT varchar(10),
            TRAN_DATE varchar(8),
            POST_DATE varchar(8),
            JRNL_NO varchar(6),
            BRANCH_NO varchar(4),
            DESC_1 varchar(30),
            DESC_2 varchar(50),
            DESC_3 varchar(16),
            DESC_4 varchar(20),
            DESC_5 varchar(10),
            AMOUNT varchar(16),
            D_K varchar(1),
            BALANCE varchar(17),
            DESC_36 varchar(53),
            DESC_38 varchar(53),
            DESC_39 varchar(53),
            DESC_20_1 varchar(50),
            DESC_20_2 varchar(50),
            DESC_20_3 varchar(50),
            POST_TIME varchar(8),
            BEGIN_BAL varchar(17),
            CCY varchar(3),
            TELLER_ID varchar(5),
            TRANCODE varchar(6),
            GL_CLASS varchar(6),
            BRANCH varchar(4)
        ) engine myisam');

        DB::statement('insert into ssd_temp2_'.$userId.'
        select 
            mid(ssd, 1, 6) as SEQ_NO,
            mid(ssd, 7, 10) as ACCOUNT,
            mid(ssd, 17, 8) as TRAN_DATE,
            mid(ssd, 25, 8) as POST_DATE,
            mid(ssd, 33, 6) as JRNL_NO,
            mid(ssd, 39, 4) as BRANCH_NO,
            mid(ssd, 43, 30) as DESC_1,
            mid(ssd, 73, 50) as DESC_2,
            mid(ssd, 123, 16) as DESC_3,
            mid(ssd, 139, 8) as DESC_4,
            mid(ssd, 153, 6) as DESC_5,
            mid(ssd, 159, 14) as AMOUNT,
            mid(ssd, 175, 1) as D_K,
            mid(ssd, 176, 17) as BALANCE,
            mid(ssd, 193, 53) as DESC_36,
            mid(ssd, 246, 53) as DESC_38,
            mid(ssd, 299, 53) as DESC_39,
            mid(ssd, 352, 50) as DESC_20_1,
            mid(ssd, 402, 50) as DESC_20_1,
            mid(ssd, 452, 50) as DESC_20_3,
            mid(ssd, 502, 8) as POST_TIME,
            mid(ssd, 510, 17) as BEGIN_BAL,
            mid(ssd, 527, 3) as CCY,
            mid(ssd, 530, 5) as TELLER_ID,
            mid(ssd, 535, 6) as TRANCODE,
            mid(ssd, 541, 6) as GL_CLASS,
            mid(ssd, 547, 4) as BRANCH
        from ssd_temp_'.$userId.'');
        DB::statement('alter table ssd_temp2_'.$userId.' add column NO int auto_increment primary key FIRST'); 

        DB::statement('create table ssd_'.$userId.' like ssd_temp2_'.$userId.'');

        DB::statement('insert into ssd_'.$userId.'
        select 
            NO as NO,
            trim(SEQ_NO) as SEQ_NO,
            trim(ACCOUNT) as ACCOUNT,
            trim(TRAN_DATE) as TRAN_DATE,
            trim(POST_DATE) as POST_DATE,
            trim(JRNL_NO) as JRNL_NO,
            trim(BRANCH_NO) as BRANCH_NO,
            trim(DESC_1) as DESC_1,
            trim(DESC_2) as DESC_2,
            trim(DESC_3) as DESC_3,
            trim(DESC_4) as DESC_4,
            trim(DESC_5) as DESC_5,
            trim(AMOUNT) as AMOUNT,
            trim(D_K) as D_K,
            trim(BALANCE) as BALANCE,
            trim(DESC_36) as DESC_36,
            trim(DESC_38) as DESC_38,
            trim(DESC_39) as DESC_39,
            trim(DESC_20_1) as DESC_20_1,
            trim(DESC_20_1) as DESC_20_1,
            trim(DESC_20_3) as DESC_20_3,
            trim(POST_TIME) as POST_TIME,
            trim(BEGIN_BAL) as BEGIN_BAL,
            trim(CCY) as CCY,
            trim(TELLER_ID) as TELLER_ID,
            trim(TRANCODE) as TRANCODE,
            trim(GL_CLASS) as GL_CLASS,
            trim(BRANCH) as BRANCH
        from ssd_temp2_'.$userId.'');

        unlink(base_path('public/Import/SSD/SDD_'.$userId.'.txt'));

        // return Excel::download(new ReportSSDPage1Export($userId), 'Summary Report SSD FILE.xlsx');
        return redirect('formDownloadReportSDD')->with('alertSuccess', 'Berhasil Di Parsing');
    }
    
    public function getDataExportSDDPage1(Request $request)
    {
        $userId = $request->session()->get('userId');
        return (new FastExcel(CemtextModel::getDataReportSDDPage1Export($userId)))->download('Summary SDD File Page 1.xlsx');
    }
    
    public function getDataExportSDDPage2(Request $request)
    {
        $userId = $request->session()->get('userId');
        return (new FastExcel(CemtextModel::getDataReportSDDPage2Export($userId)))->download('Summary SDD File Page 2.xlsx');
    }
    
    public function getDataExportSDDPage3(Request $request)
    {
        $userId = $request->session()->get('userId');
        return (new FastExcel(CemtextModel::getDataReportSDDPage2Export($userId)))->download('Summary SDD File Page 3.xlsx');
    }
}
