<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Alert;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ResultSameTech767Export;
use App\Exports\ResultSameTech777Export;

class CemtextController extends Controller
{
    public function formUploadCemtext(Request $request)
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
        return view('formUploadCemtext', $data);
    }

    public function prosesUploadCemtext767(Request $request)
    {
        $userId = $request->session()->get('userId');
        $file_import = $request->file('file_import');
        $ekstensi_file_import = $request->file('file_import')->getClientOriginalExtension();
        
        $nama_file_import = 'sametex767_'.$userId.'.txt';
        $file_import->move(\base_path() ."/public/Import/Cemtext", $nama_file_import);

        DB::statement('drop table if exists result_sametex767_'.$userId.', sametex767_'.$userId.', sametex767_temp_'.$userId.', suspense_767_'.$userId.'');
        
        DB::statement('create table sametex767_'.$userId.' (sametex767 varchar(255)) engine myisam'); 
        
        $sql = 'load data infile "C:/xampp/htdocs/cemtext/public/Import/Cemtext/sametex767_'.$userId.'.txt" into table sametex767_'.$userId.''; 
        DB::connection()->getPdo()->exec($sql);

        DB::statement('create table sametex767_temp_'.$userId.'
        (
            part_1 varchar(15),
            part_2 varchar(200)
        ) engine myisam'); 

        DB::statement('insert into sametex767_temp_'.$userId.'
        select 
            mid(sametex767, 1, 12) as part_1,
            mid(sametex767, 12, 200) as part_2
        from sametex767_'.$userId.'');
        DB::statement('alter table sametex767_temp_'.$userId.' add column no int auto_increment primary key FIRST'); 

        DB::statement('create table result_sametex767_'.$userId.'
        (
            seq_no int(11),
            rekening varchar(12), 
            tranx varchar(5),
            jrnl varchar(8),
            nominal varchar(10),
            tanggal varchar(10),
            sys varchar(5),
            cheque varchar(8),
            err varchar(8),
            messages varchar(50),
            suspense varchar(500)
        ) engine myisam'); 

        DB::statement('insert into result_sametex767_'.$userId.'
        select
            part_1 as seq_no,
            mid(part_2,1,11) as rekening,
            mid(part_2,14,5) as tranx,
            mid(part_2,20,6) as jrnl,
            mid(part_2,36,10) as nominal,
            mid(part_2,50,10) as tanggal,
            mid(part_2,60,5) as sys,
            mid(part_2,63,8) as cheque,
            mid(part_2,68,3) as err,
            mid(part_2,75,25) as messagess,
            mid(part_2,75,25) as suspense
        from sametex767_temp_'.$userId.' where mid(part_2, 48, 8) like "%/%"');
        DB::statement('alter table result_sametex767_'.$userId.' add column no int auto_increment primary key FIRST'); 

        DB::statement('create table suspense_767_'.$userId.' (suspense varchar(500)) engine myisam'); 

        DB::statement('insert into suspense_767_'.$userId.'
        select mid(part_2,1,500) as suspense from sametex767_temp_'.$userId.' where no in (select no+1 from sametex767_temp_'.$userId.' where mid(part_2, 48, 8) like "%/%")');
        DB::statement('alter table suspense_767_'.$userId.' add column no int auto_increment primary key FIRST'); 

        DB::statement('update suspense_767_'.$userId.'
        set suspense = replace(suspense, "        ", "")');

        DB::statement('update result_sametex767_'.$userId.' join suspense_767_'.$userId.' on result_sametex767_'.$userId.'.no = suspense_767_'.$userId.'.no set result_sametex767_'.$userId.'.suspense = suspense_767_'.$userId.'.suspense');

        DB::statement('update result_sametex767_'.$userId.'
        set tranx = trim(tranx),
        jrnl = trim(jrnl),
        nominal = trim(nominal),
        nominal = replace(nominal, ",", ""),
        nominal = replace(nominal, ".00", ""),
        tanggal = trim(tanggal),
        tanggal = DATE(STR_TO_DATE(tanggal, "%d/%m/%Y")),
        sys = trim(sys),
        cheque = trim(cheque),
        err = trim(err),
        messages = trim(messages),
        messages = replace(messages, "\n", ""),
        messages = replace(messages, "\r", ""),
        messages = replace(messages, "\t", ""),
        suspense = replace(suspense, "                          ", " "),
        suspense = replace(suspense, "       ", " "),
        suspense = replace(suspense, "\n", ""),
        suspense = replace(suspense, "\r", ""),
        suspense = replace(suspense, "\t", "")');

        return Excel::download(new ResultSameTech767Export($userId), 'Summary Cemtext 0767.xlsx');
    }

    public function prosesUploadCemtext777(Request $request)
    {
        $userId = $request->session()->get('userId');
        $file_import = $request->file('file_import');
        $ekstensi_file_import = $request->file('file_import')->getClientOriginalExtension();
        
        $nama_file_import = 'sametex777_'.$userId.'.txt';
        $file_import->move(\base_path() ."/public/Import/Cemtext", $nama_file_import);

        DB::statement('drop table if exists result_cemtext777_'.$userId.', cemtext777_temp1_'.$userId.', cemtext777_temp2_'.$userId.', suspense777_'.$userId.', suspense777_2_'.$userId.', suspense777_3_'.$userId.', suspense777_1_'.$userId.', cemtext777_temp3_'.$userId.'');

        ## Create table temporary sametext777 1
        DB::statement('create table cemtext777_temp1_'.$userId.' (sametex777 varchar(150)) engine myisam'); 

        $sql = 'load data infile "C:/xampp/htdocs/cemtext/public/Import/Cemtext/sametex777_'.$userId.'.txt" into table cemtext777_temp1_'.$userId.'';
        DB::connection()->getPdo()->exec($sql);
        DB::statement('alter table cemtext777_temp1_'.$userId.' add column no int auto_increment primary key first');
        
        ## Create table temporary sametext777 2
        DB::statement('create table cemtext777_temp2_'.$userId.'
        (
            part_1 varchar(14),
            part_2 varchar(150)
        ) engine myisam'); 

        DB::statement('insert into cemtext777_temp2_'.$userId.'
        select 
            mid(sametex777, 1, 14) as part_1,
            mid(sametex777, 12, 150) as part_2
        from cemtext777_temp1_'.$userId.'');
        DB::statement('alter table cemtext777_temp2_'.$userId.' add column no int auto_increment primary key first'); 

        ## Create table temporary sametext777 3
        DB::statement('create table cemtext777_temp3_'.$userId.'
        (
            seq_no int(11),
            rekening varchar(12), 
            tranx varchar(5),
            jrnl varchar(8),
            nominal varchar(20),
            tanggal varchar(10),
            sys varchar(5),
            cheque varchar(8),
            err varchar(8),
            messages varchar(50),
            suspense_1 varchar(500)
        ) 
        engine myisam'); 

        DB::statement('insert into cemtext777_temp3_'.$userId.'
        select 
            trim(part_1) as seq_no,
            mid(part_2, 1, 12) as rekening,
            mid(part_2, 16, 4) as tranx,
            mid(part_2, 21, 6) as jrnl,
            mid(part_2, 27, 20) as nominal,
            mid(part_2, 50, 10) as tanggal,
            mid(part_2, 60, 5) as sys,
            mid(part_2, 64, 8) as cheque,
            mid(part_2, 69, 3) as err,
            mid(part_2, 76, 25) as messages,
            mid(part_2, 76, 25) as suspense_1
        from cemtext777_temp2_'.$userId.' where mid(part_2, 48, 8) like "%/%"');
        DB::statement('alter table cemtext777_temp3_'.$userId.' add column no int auto_increment primary key first');
        DB::statement('update cemtext777_temp3_'.$userId.' set nominal = replace(nominal, ".00", ""), nominal = replace(nominal, ",", "")');
        
        ## Create table suspense 1
        DB::statement('create table suspense777_1_'.$userId.' (suspense varchar(150)) engine myisam'); 

        DB::statement('insert into suspense777_1_'.$userId.' select mid(part_2, 1, 150) as suspense from cemtext777_temp2_'.$userId.' where no in (select no+1 from cemtext777_temp2_'.$userId.' where mid(part_2, 48, 8) like "%/%")');
        DB::statement('alter table suspense777_1_'.$userId.' add column no int auto_increment primary key first');

        DB::statement('alter table suspense777_1_'.$userId.' add column suspense_1 varchar(17)');
        DB::statement('update suspense777_1_'.$userId.' set suspense_1 = mid(suspense, 34, 17)');
        
        DB::statement('alter table suspense777_1_'.$userId.' add column suspense_2 varchar(10)');
        DB::statement('update suspense777_1_'.$userId.' set suspense_2 = mid(suspense, 51, 10)');

        DB::statement('alter table suspense777_1_'.$userId.' add column suspense_3 varchar(24)');
        DB::statement('update suspense777_1_'.$userId.' set suspense_3 = mid(suspense, 61, 24)');

        DB::statement('alter table suspense777_1_'.$userId.' add column suspense_4 varchar(16)');
        DB::statement('update suspense777_1_'.$userId.' set suspense_4 = mid(suspense, 88, 16)');
        
        DB::statement('alter table suspense777_1_'.$userId.' add column suspense_5 varchar(8)');
        DB::statement('update suspense777_1_'.$userId.' set suspense_5 = mid(suspense, 104, 8)');

        DB::statement('alter table suspense777_1_'.$userId.' add column suspense_6 varchar(10)');
        DB::statement('update suspense777_1_'.$userId.' set suspense_6 = mid(suspense, 113, 10)');

        ## Create table suspense 2
        DB::statement('create table suspense777_2_'.$userId.' (suspense varchar(150)) engine myisam'); 

        DB::statement('insert into suspense777_2_'.$userId.' select mid(part_2, 1, 150) as suspense from cemtext777_temp2_'.$userId.' where no in (select no+2 from cemtext777_temp2_'.$userId.' where mid(part_2, 48, 8) like "%/%")');
        DB::statement('alter table suspense777_2_'.$userId.' add column no int auto_increment primary key first');

        DB::statement('alter table suspense777_2_'.$userId.' add column suspense_7 varchar(12)');
        DB::statement('update suspense777_2_'.$userId.' set suspense_7 = mid(suspense, 86, 12)');

        ## Create table suspense 3
        DB::statement('create table suspense777_3_'.$userId.' (suspense varchar(150)) engine myisam'); 

        DB::statement('insert into suspense777_3_'.$userId.' select mid(part_2, 1, 150) as suspense from cemtext777_temp2_'.$userId.' where no in (select no+3 from cemtext777_temp2_'.$userId.' where mid(part_2, 48, 8) like "%/%")');
        DB::statement('alter table suspense777_3_'.$userId.' add column no int auto_increment primary key first');

        DB::statement('alter table suspense777_3_'.$userId.' add column suspense_8 varchar(6)');
        DB::statement('update suspense777_3_'.$userId.' set suspense_8 = mid(suspense, 86, 6)');

        ## Update Suspense to Cemtext temporary table
        DB::statement('alter table cemtext777_temp3_'.$userId.' 
            add suspense_2 varchar(10) not null after suspense_1, 
            add suspense_3 varchar(24) not null after suspense_2, 
            add suspense_4 varchar(16) not null after suspense_3, 
            add suspense_5 varchar(8) not null after suspense_4, 
            add suspense_6 varchar(10) not null after suspense_5, 
            add suspense_7 varchar(12) not null after suspense_6,
            add suspense_8 varchar(6) not null after suspense_7');

        DB::statement('update cemtext777_temp3_'.$userId.', suspense777_1_'.$userId.' set 
            cemtext777_temp3_'.$userId.'.suspense_1 = suspense777_1_'.$userId.'.suspense_1,
            cemtext777_temp3_'.$userId.'.suspense_2 = suspense777_1_'.$userId.'.suspense_2,
            cemtext777_temp3_'.$userId.'.suspense_3 = suspense777_1_'.$userId.'.suspense_3,
            cemtext777_temp3_'.$userId.'.suspense_4 = suspense777_1_'.$userId.'.suspense_4,
            cemtext777_temp3_'.$userId.'.suspense_5 = suspense777_1_'.$userId.'.suspense_5,
            cemtext777_temp3_'.$userId.'.suspense_6 = suspense777_1_'.$userId.'.suspense_6 where cemtext777_temp3_'.$userId.'.no = suspense777_1_'.$userId.'.no');

        DB::statement('update cemtext777_temp3_'.$userId.', suspense777_2_'.$userId.' set cemtext777_temp3_'.$userId.'.suspense_7 = suspense777_2_'.$userId.'.suspense_7 where cemtext777_temp3_'.$userId.'.no = suspense777_2_'.$userId.'.no');
        DB::statement('update cemtext777_temp3_'.$userId.', suspense777_3_'.$userId.' set cemtext777_temp3_'.$userId.'.suspense_8 = suspense777_3_'.$userId.'.suspense_8 where cemtext777_temp3_'.$userId.'.no = suspense777_3_'.$userId.'.no');

        DB::statement('create table result_cemtext777_'.$userId.' like cemtext777_temp3_'.$userId.'');
        DB::statement('insert into result_cemtext777_'.$userId.'
        select
            trim(no) as no,
            trim(seq_no) as seq_no,
            trim(rekening) as rekening,
            trim(tranx) as tranx,
            trim(jrnl) as jrnl,
            trim(nominal) as nominal,
            trim(tanggal) as tanggal,
            trim(sys) as sys,
            trim(cheque) as cheque,
            trim(err) as err,
            trim(messages) as messages,
            trim(suspense_1) as suspense_1,
            trim(suspense_2) as suspense_2,
            trim(suspense_3) as suspense_3,
            trim(suspense_4) as suspense_4,
            trim(suspense_5) as suspense_5,
            trim(suspense_6) as suspense_6,
            trim(suspense_7) as suspense_7,
            trim(suspense_8) as suspense_8
        from cemtext777_temp3_'.$userId.'');

        return Excel::download(new ResultSameTech777Export($userId), 'Summary Cemtext 0777.xlsx');
    }
}
