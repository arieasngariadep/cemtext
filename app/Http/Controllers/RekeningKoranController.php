<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Alert;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportRekeningKoranExport;

class RekeningKoranController extends Controller
{
    public function formUploadRC(Request $request)
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
        return view('RekeningKoran.formUploadRekeningKoran', $data);
    }

    public function prosesUploadSumamryRC(Request $request)
    {
        $file_import = $request->file('file_import');
        $ekstensi_file_import = array($request->file('file_import')->getClientOriginalExtension());
        $mime_file_import = $request->file('file_import')->getMimeType();
        $userId = $request->userId;
        $extensions = array("txt", "TXT");

        if(!in_array($ekstensi_file_import[0], $extensions)){
            return redirect()->back()->with('alert', 'Format file yang anda upload bukan TXT');
        }else{
            $nama_file_import = 'RC_'.$userId.'.txt';
            $file_import->move(\base_path() ."/public/import/RC", $nama_file_import);

            DB::statement('drop table if exists nama_rek_'.$userId.', no_'.$userId.', no_nama_'.$userId.', no_rek_'.$userId.', rctemp_'.$userId.', rctemp1_'.$userId.', rctemp1_namarek_'.$userId.', rctemp1_norek_'.$userId.', result_rc_temp_'.$userId.', temp_'.$userId.', result_rc_'.$userId.'');

            DB::statement('create table rctemp_'.$userId.'(rc varchar(400))engine myisam');
            $sql = 'load data infile "C:/xampp/htdocs/cemtext/public/Import/RC/RC_'.$userId.'.txt" into table rctemp_'.$userId.'';
            DB::connection()->getPdo()->exec($sql);
            
            DB::statement('alter table rctemp_'.$userId.' add column no int auto_increment primary key FIRST'); 

            DB::statement('create table no_nama_'.$userId.' (no bigint, nama varchar(20)) engine myisam');
            DB::statement('insert into no_nama_'.$userId.' select no, mid(rc, 1, 20) as nama from rctemp_'.$userId.' where no + 1 and mid(rc, 1, 100) like "%REKENING KORAN%"');

            DB::statement('alter table no_nama_'.$userId.' add column no_norek bigint');
            DB::statement('update no_nama_'.$userId.' set no_norek = no+4');

            DB::statement('alter table no_nama_'.$userId.' add column no_namarek bigint');
            DB::statement('update no_nama_'.$userId.' set no_namarek = no+4');

            DB::statement('create table no_'.$userId.' (no bigint)');
            DB::statement('insert into no_'.$userId.' select no_norek from no_nama_'.$userId.'');
            DB::statement('insert into no_'.$userId.' select no_namarek from no_nama_'.$userId.'');

            DB::statement('alter table no_'.$userId.' add index(no)');
            DB::statement('alter table rctemp_'.$userId.' add index(no)');

            DB::statement('create table rctemp1_'.$userId.' like rctemp_'.$userId.'');

            DB::statement('insert into rctemp1_'.$userId.' select * from rctemp_'.$userId.' where no in (select no from no_'.$userId.') or mid(rc, 1, 3) like "%/%" order by no');

            DB::statement('alter table no_nama_'.$userId.' add index(no_norek)');
            DB::statement('alter table no_nama_'.$userId.' add index(no_namarek)');
            DB::statement('alter table rctemp1_'.$userId.' add index(no)');

            DB::statement('alter table rctemp1_'.$userId.' add column no_rek varchar(46)');
            DB::statement('update rctemp1_'.$userId.' set no_rek =  mid(rctemp1_'.$userId.'.rc, 17, 17) where no in (select no_norek as no from no_nama_'.$userId.')');

            DB::statement('alter table rctemp1_'.$userId.' add column nama_rek varchar(250)');
            DB::statement('update rctemp1_'.$userId.' set nama_rek = mid(rctemp1_'.$userId.'.rc, 63, 250) where no in (select no_namarek as no from no_nama_'.$userId.')');
            DB::statement('update rctemp1_'.$userId.' set nama_rek = replace(nama_rek, "MATAUANG : IDR", "")');

            DB::statement('create table rctemp1_norek_'.$userId.'(no int, no_rek varchar(46))');
            DB::statement('insert into rctemp1_norek_'.$userId.' select no, no_rek from rctemp1_'.$userId.' where no_rek is not null');
            DB::statement('alter table rctemp1_norek_'.$userId.' add index(no)');

            DB::statement('create table no_rek_'.$userId.' like rctemp1_norek_'.$userId.'');
            DB::statement('alter table no_rek_'.$userId.' add index(no_rek)');
            DB::statement('insert into no_rek_'.$userId.'
            select no_rek_'.$userId.'.no as no, rctemp1_norek_'.$userId.'.no_rek as no_rek from rctemp1_'.$userId.', rctemp1_norek_'.$userId.',
            (select rctemp1_'.$userId.'.no, max(rctemp1_norek_'.$userId.'.no) as pair from rctemp1_'.$userId.', rctemp1_norek_'.$userId.' where rctemp1_norek_'.$userId.'.no < rctemp1_'.$userId.'.no and rctemp1_'.$userId.'.no_rek is null 
            group by rctemp1_'.$userId.'.no) no_rek_'.$userId.' where rctemp1_'.$userId.'.no = no_rek_'.$userId.'.no and rctemp1_norek_'.$userId.'.no = no_rek_'.$userId.'.pair');
            DB::statement('update rctemp1_'.$userId.', no_rek_'.$userId.' set rctemp1_'.$userId.'.no_rek = no_rek_'.$userId.'.no_rek where rctemp1_'.$userId.'.no = no_rek_'.$userId.'.no');

            DB::statement('create table rctemp1_namarek_'.$userId.'(no int, nama_rek varchar(250))');
            DB::statement('insert into rctemp1_namarek_'.$userId.' select no, nama_rek from rctemp1_'.$userId.' where nama_rek is not null');
            DB::statement('alter table rctemp1_namarek_'.$userId.' add index(no)');

            DB::statement('create table nama_rek_'.$userId.' like rctemp1_namarek_'.$userId.'');
            DB::statement('alter table nama_rek_'.$userId.' add index(nama_rek)');
            DB::statement('insert into nama_rek_'.$userId.'
            select nama_rek_'.$userId.'.no as no, rctemp1_namarek_'.$userId.'.nama_rek as nama_rek from rctemp1_'.$userId.', rctemp1_namarek_'.$userId.', 
            (select rctemp1_'.$userId.'.no, max(rctemp1_namarek_'.$userId.'.no) as pair from rctemp1_'.$userId.', rctemp1_namarek_'.$userId.' where rctemp1_namarek_'.$userId.'.no < rctemp1_'.$userId.'.no and rctemp1_'.$userId.'.nama_rek is null 
            group by rctemp1_'.$userId.'.no) nama_rek_'.$userId.' where rctemp1_'.$userId.'.no = nama_rek_'.$userId.'.no and rctemp1_namarek_'.$userId.'.no = nama_rek_'.$userId.'.pair');
            DB::statement('update rctemp1_'.$userId.', nama_rek_'.$userId.' set rctemp1_'.$userId.'.nama_rek = nama_rek_'.$userId.'.nama_rek where rctemp1_'.$userId.'.no = nama_rek_'.$userId.'.no');

            DB::statement('create table temp_'.$userId.' (rc varchar(400))engine myisam');
            DB::statement('insert into temp_'.$userId.' select rc as rc from rctemp_'.$userId.' WHERE rc NOT LIKE "%REKENING KORAN IA%" and rc NOT LIKE "%PERIODE TGL%" and rc NOT LIKE "%CABANG%" and rc NOT LIKE "%NO. REKENING%" and rc NOT LIKE "%TELLER%" and rc NOT LIKE "%================%" and rc NOT LIKE "%SALDO AWAL%" and rc NOT LIKE "%................%" and rc NOT LIKE "%TANDA TANGAN%"');
            DB::statement('alter table temp_'.$userId.' add column no int auto_increment primary key FIRST');
            
            DB::statement('create table result_rc_temp_'.$userId.'
            (
                rc varchar(400),
                no_rek varchar(46),
                nama_rek varchar(250)
            )engine myisam');
            DB::statement('insert into result_rc_temp_'.$userId.'
            select 
                rc as rc,
                no_rek as no_rek,
                nama_rek as nama_rek
            from rctemp1_'.$userId.' where rc like "%/%"');
            
            DB::statement('alter table result_rc_temp_'.$userId.' add column no int(11) FIRST');
            DB::statement('update result_rc_temp_'.$userId.', temp_'.$userId.' set result_rc_temp_'.$userId.'.no = temp_'.$userId.'.no where mid(result_rc_temp_'.$userId.'.rc, 1, 50) = mid(temp_'.$userId.'.rc, 1, 50)');

            DB::statement('alter table result_rc_temp_'.$userId.' add column tanggal varchar(10)');
            DB::statement('update result_rc_temp_'.$userId.' set tanggal = mid(rc, 1, 10)');

            DB::statement('alter table result_rc_temp_'.$userId.' add column no_jurnal varchar(10)');
            DB::statement('update result_rc_temp_'.$userId.' set no_jurnal = mid(rc, 16, 10)');

            DB::statement('alter table result_rc_temp_'.$userId.' add column entity varchar(5)');
            DB::statement('update result_rc_temp_'.$userId.' set entity = mid(rc, 26, 5)');

            DB::statement('alter table result_rc_temp_'.$userId.' add column user varchar(10)');
            DB::statement('update result_rc_temp_'.$userId.' set user = mid(rc, 32, 8)');

            DB::statement('alter table result_rc_temp_'.$userId.' add column tc varchar(25)');
            DB::statement('update result_rc_temp_'.$userId.' set tc = mid(rc, 40, 7)');

            DB::statement('alter table result_rc_temp_'.$userId.' add column keterangan_1 varchar(50)');
            DB::statement('update result_rc_temp_'.$userId.' set keterangan_1 = mid(rc, 47, 40)');

            DB::statement('alter table result_rc_temp_'.$userId.' add column keterangan_2 varchar(80)');
            DB::statement('update result_rc_temp_'.$userId.', temp_'.$userId.' set result_rc_temp_'.$userId.'.keterangan_2 = case when (no_jurnal = mid(temp_'.$userId.'.rc, 16, 10)) then mid(temp_'.$userId.'.rc, 47, 80) else null end where result_rc_temp_'.$userId.'.no+1 = temp_'.$userId.'.no');

            DB::statement('alter table result_rc_temp_'.$userId.' add column keterangan_3 varchar(80)');
            DB::statement('update result_rc_temp_'.$userId.', temp_'.$userId.' set result_rc_temp_'.$userId.'.keterangan_3 = case when (no_jurnal = mid(temp_'.$userId.'.rc, 16, 10)) then mid(temp_'.$userId.'.rc, 47, 80) else null end where result_rc_temp_'.$userId.'.no+2 = temp_'.$userId.'.no');

            DB::statement('alter table result_rc_temp_'.$userId.' add column keterangan_4 varchar(80)');
            DB::statement('update result_rc_temp_'.$userId.', temp_'.$userId.' set result_rc_temp_'.$userId.'.keterangan_4 = case when (no_jurnal = mid(temp_'.$userId.'.rc, 16, 10)) then mid(temp_'.$userId.'.rc, 47, 80) else null end where result_rc_temp_'.$userId.'.no+3 = temp_'.$userId.'.no');

            DB::statement('alter table result_rc_temp_'.$userId.' add column keterangan_5 varchar(80)');
            DB::statement('update result_rc_temp_'.$userId.', temp_'.$userId.' set result_rc_temp_'.$userId.'.keterangan_5 = case when (no_jurnal = mid(temp_'.$userId.'.rc, 16, 10)) then mid(temp_'.$userId.'.rc, 47, 80) else null end where result_rc_temp_'.$userId.'.no+4 = temp_'.$userId.'.no');

            DB::statement('alter table result_rc_temp_'.$userId.' add column nominal varchar(25)');
            DB::statement('update result_rc_temp_'.$userId.' set nominal = mid(rc, 85, 22)');

            DB::statement('alter table result_rc_temp_'.$userId.' add column pos varchar(2)');
            DB::statement('update result_rc_temp_'.$userId.' set pos = mid(rc, 106, 2)');

            DB::statement('alter table result_rc_temp_'.$userId.' add column saldo varchar(25)');
            DB::statement('update result_rc_temp_'.$userId.' set saldo = mid(rc, 108, 24)');

            DB::statement('alter table result_rc_temp_'.$userId.' add column pos_saldo varchar(2)');
            DB::statement('update result_rc_temp_'.$userId.' set pos_saldo = mid(rc, 132, 2)');

            DB::statement('update result_rc_temp_'.$userId.' set nominal = replace(nominal, ",", ""),
            nominal = replace(nominal, ".00", ""),
            saldo = replace(saldo, ",", ""),
            saldo = replace(saldo, ".00", ""),
            nama_rek = replace(nama_rek, "?", "-"),
            no_rek = replace(no_rek, "\t", ""),
            nama_rek = replace(nama_rek, "\t", ""),
            tanggal = replace(tanggal, "\t", ""),
            no_jurnal = replace(no_jurnal, "\t", ""),
            entity = replace(entity, "\t", ""),
            user = replace(user, "\t", ""),
            tc = replace(tc, "\t", ""),
            keterangan_1 = replace(keterangan_1, "\t", ""),
            keterangan_2 = replace(keterangan_2, "\t", ""),
            keterangan_3 = replace(keterangan_3, "\t", ""),
            keterangan_4 = replace(keterangan_4, "\t", ""),
            keterangan_5 = replace(keterangan_5, "\t", ""),
            nominal = replace(nominal, "\t", ""),
            pos = replace(pos, "\t", ""),
            saldo = replace(saldo, "\t", ""),
            pos_saldo = replace(pos_saldo, "\t", ""),
            no_rek = replace(no_rek, "\r", ""),
            nama_rek = replace(nama_rek, "\r", ""),
            tanggal = replace(tanggal, "\r", ""),
            no_jurnal = replace(no_jurnal, "\r", ""),
            entity = replace(entity, "\r", ""),
            user = replace(user, "\r", ""),
            tc = replace(tc, "\r", ""),
            keterangan_1 = replace(keterangan_1, "\r", ""),
            keterangan_2 = replace(keterangan_2, "\r", ""),
            keterangan_3 = replace(keterangan_3, "\r", ""),
            keterangan_4 = replace(keterangan_4, "\r", ""),
            keterangan_5 = replace(keterangan_5, "\r", ""),
            nominal = replace(nominal, "\r", ""),
            pos = replace(pos, "\r", ""),
            saldo = replace(saldo, "\r", ""),
            pos_saldo = replace(pos_saldo, "\r", ""),
            no_rek = replace(no_rek, "\n", ""),
            nama_rek = replace(nama_rek, "\n", ""),
            tanggal = replace(tanggal, "\n", ""),
            no_jurnal = replace(no_jurnal, "\n", ""),
            entity = replace(entity, "\n", ""),
            user = replace(user, "\n", ""),
            tc = replace(tc, "\n", ""),
            keterangan_1 = replace(keterangan_1, "\n", ""),
            keterangan_2 = replace(keterangan_2, "\n", ""),
            keterangan_3 = replace(keterangan_3, "\n", ""),
            keterangan_4 = replace(keterangan_4, "\n", ""),
            keterangan_5 = replace(keterangan_5, "\n", ""),
            nominal = replace(nominal, "\n", ""),
            pos = replace(pos, "\n", ""),
            saldo = replace(saldo, "\n", ""),
            pos_saldo = replace(pos_saldo, "\n", "")');

            DB::statement('create table result_rc_'.$userId.'
            (
                no_rek varchar(46),
                nama_rek varchar(250),
                tanggal varchar(10),
                no_jurnal varchar(10),
                entity varchar(5),
                user varchar(10),
                tc varchar(25),
                keterangan_1 varchar(50),
                keterangan_2 varchar(80),
                keterangan_3 varchar(80),
                keterangan_4 varchar(80),
                keterangan_5 varchar(80),
                nominal varchar(25),
                pos varchar(2),
                saldo varchar(25),
                pos_saldo varchar(2)
            ) engine myisam');

            DB::statement('insert into result_rc_'.$userId.'
            select
                trim(no_rek) as no_rek,
                trim(nama_rek) as nama_rek,
                trim(tanggal) as tanggal,
                trim(no_jurnal) as no_jurnal,
                trim(entity) as entity,
                trim(user) as user,
                trim(tc) as tc,
                trim(keterangan_1) as keterangan_1,
                trim(keterangan_2) as keterangan_2,
                trim(keterangan_3) as keterangan_3,
                trim(keterangan_4) as keterangan_4,
                trim(keterangan_5) as keterangan_5,
                trim(nominal) as nominal,
                trim(pos) as pos,
                trim(saldo) as saldo,
                trim(pos_saldo) as pos_saldo
            from result_rc_temp_'.$userId.' where tanggal != "NO. REKENI"');
            DB::statement('alter table result_rc_'.$userId.' add column id int auto_increment primary key FIRST'); 

            return Excel::download(new ReportRekeningKoranExport($userId), 'Summary Report RC.xlsx');
        }
    }
}
