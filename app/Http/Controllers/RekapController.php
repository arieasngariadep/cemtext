<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Alert;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapExport;
use App\Jobs\RekapJob;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function formUploadRekap(Request $request)
	{
        $uri = $request->segment(1);
        $role = $request->session()->get('role_id');
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

		return view('formUploadRekap', $data);
	}

    public function prosesImportRekap(Request $request)
    {    
        $userId = $request->session()->get('userId');
        $file_import = $request->file('file_import');
        $ekstensi_file_import = $request->file('file_import')->getClientOriginalExtension();
        if($ekstensi_file_import != 'csv'){
            $alert = "";
            if($ekstensi_file_import != 'csv'){
                $alert .= "Ini bukan file CSV, silahkan pilih file dengan format .csv";
            }
            return redirect()->back()->with('alert', $alert);
        }else{
            $nama_file_import = 'Rekap'.'.'.$file_import->getClientOriginalExtension();
            $file_import->move(\base_path() ."/public/Import/Rekap", $nama_file_import);

            DB::statement('drop table if exists rekap_temp_'.$userId.', result_rekap_'.$userId.'');
            DB::statement('create table rekap_temp_'.$userId.' (temp varchar(800)) engine myisam');

            $sql = 'load data infile "C:/xampp/htdocs/cemtext/public/Import/Rekap/Rekap.csv" into table rekap_temp_'.$userId.' FIELDS TERMINATED BY ";" LINES TERMINATED BY "\n"';
            DB::connection()->getPdo()->exec($sql);
            DB::statement('alter table rekap_temp_'.$userId.' add column id int auto_increment primary key FIRST');

            DB::statement('alter table rekap_temp_'.$userId.' add column post_date varchar(30)');
            DB::statement('update rekap_temp_'.$userId.' set post_date = SPLIT_STRING(temp, ",", 1)');

            DB::statement('alter table rekap_temp_'.$userId.' add column value_date varchar(30)');
            DB::statement('update rekap_temp_'.$userId.' set value_date = SPLIT_STRING(temp, ",", 2)');

            DB::statement('alter table rekap_temp_'.$userId.' add column branch varchar(30)');
            DB::statement('update rekap_temp_'.$userId.' set branch = SPLIT_STRING(temp, ",", 3)');

            DB::statement('alter table rekap_temp_'.$userId.' add column no_jurnal varchar(30)');
            DB::statement('update rekap_temp_'.$userId.' set no_jurnal = SPLIT_STRING(temp, ",", 4)');

            DB::statement('update rekap_temp_'.$userId.' set temp = replace(temp, ", | ", " | ")');

            DB::statement('alter table rekap_temp_'.$userId.' add column description varchar(400)');
            DB::statement('update rekap_temp_'.$userId.' set description = SPLIT_STRING(temp, ",", 5)');

            DB::statement('update rekap_temp_'.$userId.' set description = replace(description, """", "")');

            DB::statement('alter table rekap_temp_'.$userId.' add column des1 varchar(100)');
            DB::statement('update rekap_temp_'.$userId.' set des1 = SPLIT_STRING(description, " | ", 1)');

            DB::statement('alter table rekap_temp_'.$userId.' add column des2 varchar(100)');
            DB::statement('update rekap_temp_'.$userId.' set des2 = SPLIT_STRING(description, " | ", 2)');

            DB::statement('alter table rekap_temp_'.$userId.' add column des3 varchar(100)');
            DB::statement('update rekap_temp_'.$userId.' set des3 = SPLIT_STRING(description, " | ", 3)');

            DB::statement('alter table rekap_temp_'.$userId.' add column des4 varchar(100)');
            DB::statement('update rekap_temp_'.$userId.' set des4 = SPLIT_STRING(description, " | ", 4)');

            DB::statement('alter table rekap_temp_'.$userId.' add column temp2 varchar(800) after temp');
            DB::statement('update rekap_temp_'.$userId.' set temp2 = temp');
            DB::statement('update rekap_temp_'.$userId.' set temp2 = replace(temp2, """,""", ";")');

            DB::statement('alter table rekap_temp_'.$userId.' add column debit varchar(100)');
            DB::statement('update rekap_temp_'.$userId.' set debit = SPLIT_STRING(temp2, ";", 2)');

            DB::statement('alter table rekap_temp_'.$userId.' add column credit varchar(100)');
            DB::statement('update rekap_temp_'.$userId.' set credit = SPLIT_STRING(temp2, ";", 3)');

            DB::statement('delete from rekap_temp_'.$userId.' where des1 not like "TRANSFER KE%"');
            DB::statement('update rekap_temp_'.$userId.' set debit = replace(debit, ".00", ""), debit = replace(debit, ",", "")');

            DB::statement('create table result_rekap_'.$userId.'
            (
                description varchar(400),
                post_date varchar(200),
                txn_date varchar(15),
                to_Act varchar(20),
                mid_no varchar(20),
                merchant_name varchar(400),
                shift varchar(5),
                credit_date varchar(5),
                credit_month varchar(5),
                credit_year varchar(5),
                txn_day varchar(5),
                txn_month varchar(5),
                txn_year varchar(5),
                debit_amount varchar(100)
            ) engine myisam');
            DB::statement('alter table result_rekap_'.$userId.' add column id int auto_increment primary key FIRST');

            DB::statement('insert into result_rekap_'.$userId.' (description, post_date, txn_date, to_Act, mid_no, merchant_name, shift, credit_date, credit_month, credit_year, txn_day, txn_month, txn_year, debit_amount)
            select
                description as description,
                post_date as post_date,
                mid(rekap_temp_'.$userId.'.des3, 12, 8) as txn_date,
                mid(rekap_temp_'.$userId.'.des3, 1, 10) as to_Act,
                mid(rekap_temp_'.$userId.'.des3, 23, 6) as mid_no,
                mid(rekap_temp_'.$userId.'.des3, 30, 400) as merchant_name,
                mid(rekap_temp_'.$userId.'.des3, 20, 2) as shift,
                mid(rekap_temp_'.$userId.'.post_date, 1, 2) as credit_date,
                mid(rekap_temp_'.$userId.'.post_date, 4, 2) as credit_month,
                mid(rekap_temp_'.$userId.'.post_date, 7, 2) as credit_year,
                mid(rekap_temp_'.$userId.'.des3, 12, 2) as txn_day,
                mid(rekap_temp_'.$userId.'.des3, 14, 2) as txn_month,
                mid(rekap_temp_'.$userId.'.des3, 16, 4) as txn_year,
                debit as debit_amount
            from rekap_temp_'.$userId.' where post_date != "Post Date"');

            DB::statement('alter table result_rekap_'.$userId.' add column mname0 varchar(400) after merchant_name');
            DB::statement('update result_rekap_'.$userId.' set mname0 = TRIM(LEADING "0" FROM merchant_name)');

            DB::statement('alter table result_rekap_'.$userId.' add column mname1 varchar(400) after mname0');
            DB::statement('update result_rekap_'.$userId.' set mname1 = TRIM(LEADING "1" FROM mname0)');

            DB::statement('alter table result_rekap_'.$userId.' add column mname2 varchar(400) after mname1');
            DB::statement('update result_rekap_'.$userId.' set mname2 = TRIM(LEADING "2" FROM mname1)');

            DB::statement('alter table result_rekap_'.$userId.' add column mname3 varchar(400) after mname2');
            DB::statement('update result_rekap_'.$userId.' set mname3 = TRIM(LEADING "3" FROM mname2)');

            DB::statement('alter table result_rekap_'.$userId.' add column mname4 varchar(400) after mname3');
            DB::statement('update result_rekap_'.$userId.' set mname4 = TRIM(LEADING "4" FROM mname3)');

            DB::statement('alter table result_rekap_'.$userId.' add column mname5 varchar(400) after mname4');
            DB::statement('update result_rekap_'.$userId.' set mname5 = TRIM(LEADING "5 " FROM mname4)');

            DB::statement('alter table result_rekap_'.$userId.' add column mname6 varchar(400) after mname5');
            DB::statement('update result_rekap_'.$userId.' set mname6 = TRIM(LEADING "6" FROM mname5)');

            DB::statement('alter table result_rekap_'.$userId.' add column mname7 varchar(400) after mname6');
            DB::statement('update result_rekap_'.$userId.' set mname7 = TRIM(LEADING "7" FROM mname6)');

            DB::statement('alter table result_rekap_'.$userId.' add column mname8 varchar(400) after mname7');
            DB::statement('update result_rekap_'.$userId.' set mname8 = TRIM(LEADING "8" FROM mname7)');

            DB::statement('alter table result_rekap_'.$userId.' add column mname9 varchar(400) after mname8');
            DB::statement('update result_rekap_'.$userId.' set mname9 = TRIM(LEADING "9" FROM mname8)');

            DB::statement('alter table result_rekap_'.$userId.' add column mname varchar(400) after mname9');
            DB::statement('update result_rekap_'.$userId.' set mname = TRIM(LEADING "0 " FROM mname9)');
            DB::statement('update result_rekap_'.$userId.' set mname = TRIM(LEADING "1 " FROM mname9)');
            DB::statement('update result_rekap_'.$userId.' set mname = TRIM(LEADING "2 " FROM mname9)');
            DB::statement('update result_rekap_'.$userId.' set mname = TRIM(LEADING "3 " FROM mname9)');
            DB::statement('update result_rekap_'.$userId.' set mname = TRIM(LEADING "4 " FROM mname9)');
            DB::statement('update result_rekap_'.$userId.' set mname = TRIM(LEADING "5 " FROM mname9)');
            DB::statement('update result_rekap_'.$userId.' set mname = TRIM(LEADING "6 " FROM mname9)');
            DB::statement('update result_rekap_'.$userId.' set mname = TRIM(LEADING "7 " FROM mname9)');
            DB::statement('update result_rekap_'.$userId.' set mname = TRIM(LEADING "8 " FROM mname9)');
            DB::statement('update result_rekap_'.$userId.' set mname = TRIM(LEADING "9 " FROM mname9)');
            DB::statement('update result_rekap_'.$userId.' set merchant_name = mname');
            DB::statement('update result_rekap_'.$userId.' set txn_date = concat(mid(txn_date, 1, 2), "/", mid(txn_date, 3, 2), "/", mid(txn_date, 5, 4))');
            
            return Excel::download(new RekapExport($userId), 'Data Rekap.xlsx');
        }
    }
}
