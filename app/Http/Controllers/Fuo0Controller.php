<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Alert;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FUO0\FUO0MultipleExport;
use App\Exports\FUO0\FUO0Export;

class Fuo0Controller extends Controller
{
    public function formUploadFuo0(Request $request)
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
        return view('formUploadFuo0', $data);
    }

    public function prosesUploadFuo0(Request $request)
    {
        $userId = $request->userId;

        $jumlah_data = count($request->file('file_import'));

        if($jumlah_data == 1){
            $userId = $request->userId;
            foreach ($request->file('file_import') as $file_import) {
                $nama_file_import = 'fuo0_'.$userId.'.txt';
                $file_import->move(\base_path() ."/public/Import/FUO0", $nama_file_import);
            }

            DB::statement('drop table if exists temp1_fuo0_'.$userId.', result_fuo0_'.$userId.', syariah_fuo0_'.$userId.', tanggal_report_fuo0_'.$userId.'');

            DB::statement('create table temp1_fuo0_'.$userId.'(fuo0 varchar(255))engine myisam');
            $sql = 'load data infile "D:/xampp/htdocs/cemtext/public/Import/FUO0/fuo0_'.$userId.'.txt" into table temp1_fuo0_'.$userId.'';
            DB::connection()->getPdo()->exec($sql); 
            DB::statement('alter table temp1_fuo0_'.$userId.' add column no int auto_increment primary key first');

            DB::statement('create table tanggal_report_fuo0_'.$userId.'(narasi varchar(255), tanggal_report varchar(6))');
            DB::statement('insert into tanggal_report_fuo0_'.$userId.'(narasi, tanggal_report) select fuo0 as narasi, mid(fuo0, 5, 6) as tanggal_report from temp1_fuo0_'.$userId.' where mid(fuo0, 1, 4) like "%FHDR%"');
            
            DB::statement('alter table temp1_fuo0_'.$userId.' add column tanggal_report varchar(6)');
            DB::statement('update temp1_fuo0_'.$userId.', tanggal_report_fuo0_'.$userId.' set temp1_fuo0_'.$userId.'.tanggal_report = tanggal_report_fuo0_'.$userId.'.tanggal_report');

            DB::statement('delete from temp1_fuo0_'.$userId.' where mid(fuo0, 1, 4) not like "%FREC%"');

            DB::statement('alter table temp1_fuo0_'.$userId.' add column kolom1 varchar(4)');
            DB::statement('update temp1_fuo0_'.$userId.' set kolom1 = mid(fuo0, 1, 4)');

            DB::statement('alter table temp1_fuo0_'.$userId.' add column kolom2 varchar(14)');
            DB::statement('update temp1_fuo0_'.$userId.' set kolom2 = mid(fuo0, 5, 14)');

            DB::statement('alter table temp1_fuo0_'.$userId.' add column kolom3 varchar(6)');
            DB::statement('update temp1_fuo0_'.$userId.' set kolom3 = mid(fuo0, 19, 6)');

            DB::statement('alter table temp1_fuo0_'.$userId.' add column kolom4 varchar(8)');
            DB::statement('update temp1_fuo0_'.$userId.' set kolom4 = mid(fuo0, 25, 8)');

            DB::statement('alter table temp1_fuo0_'.$userId.' add column kolom5 varchar(20)');
            DB::statement('update temp1_fuo0_'.$userId.' set kolom5 = mid(fuo0, 33, 19)');

            DB::statement('alter table temp1_fuo0_'.$userId.' add column kolom6 varchar(5)');
            DB::statement('update temp1_fuo0_'.$userId.' set kolom6 = mid(fuo0, 53, 5)');

            DB::statement('alter table temp1_fuo0_'.$userId.' add column kolom7 varchar(35)');
            DB::statement('update temp1_fuo0_'.$userId.' set kolom7 = mid(fuo0, 58, 35)');

            DB::statement('alter table temp1_fuo0_'.$userId.' add column kolom8 varchar(8)');
            DB::statement('update temp1_fuo0_'.$userId.' set kolom8 = mid(fuo0, 93, 8)');

            DB::statement('alter table temp1_fuo0_'.$userId.' add column kolom9 varchar(10)');
            DB::statement('update temp1_fuo0_'.$userId.' set kolom9 = mid(fuo0, 101, 10)');

            DB::statement('alter table temp1_fuo0_'.$userId.' add column kolom10 varchar(66)');
            DB::statement('update temp1_fuo0_'.$userId.' set kolom10 = mid(fuo0, 111, 66)');

            DB::statement('alter table temp1_fuo0_'.$userId.' add column kolom11 varchar(9)');
            DB::statement('update temp1_fuo0_'.$userId.' set kolom11 = mid(fuo0, 177, 9)');

            DB::statement('alter table temp1_fuo0_'.$userId.' add column kolom12 varchar(21)');
            DB::statement('update temp1_fuo0_'.$userId.' set kolom12 = mid(fuo0, 186, 21)');

            DB::statement('alter table temp1_fuo0_'.$userId.' add column kolom13 varchar(12)');
            DB::statement('update temp1_fuo0_'.$userId.' set kolom13 = mid(fuo0, 207, 12)');

            DB::statement('alter table temp1_fuo0_'.$userId.' add column kolom14 varchar(18)');
            DB::statement('update temp1_fuo0_'.$userId.' set kolom14 = mid(fuo0, 219, 18)');

            DB::statement('delete from temp1_fuo0_'.$userId.' where mid(kolom12, 14, 1) != "D"');

            DB::statement('create table result_fuo0_'.$userId.'
            (
                tanggal_report varchar(6),
                frec varchar(4),
                tanggal varchar(6),
                nomor_kartu varchar(20),
                id_1300 varchar(5),
                id_atm varchar(8),
                kode_mc2 varchar(10),
                amount varchar(9)
            ) engine myisam');

            DB::statement('insert into result_fuo0_'.$userId.'
            select
                trim(tanggal_report) as tanggal_report,
                trim(kolom1) as frec,
                trim(kolom3) as tanggal,
                trim(kolom5) as nomor_kartu,
                trim(kolom6) as id_1300,
                trim(kolom8) as id_atm,
                trim(kolom9) as kode_mc2,
                trim(kolom11) as amount
            from temp1_fuo0_'.$userId.'');
            DB::statement('alter table result_fuo0_'.$userId.' add column no int auto_increment primary key first');

            DB::statement('create table syariah_fuo0_'.$userId.' like result_fuo0_'.$userId.'');
            DB::statement('insert into syariah_fuo0_'.$userId.' select * from result_fuo0_'.$userId.' where nomor_kartu like "%518446%" or nomor_kartu like "%522028%" or nomor_kartu like "%531857%"');
    
            // foreach ($request->file('file_import') as $file_import) {
            //     unlink(base_path('public/Import/FUO0/fuo0_'.$userId.'.txt'));
            // }

            return Excel::download(new FUO0Export($userId), 'Summary FUO0.xlsx');
        }else{
            // Get Data PS
            $no_file = 1;
            foreach ($request->file('file_import') as $file_import) {
                $nama_file_import = $file_import->getClientOriginalName().'_'.$no_file.'.txt';
                $file_import->move(\base_path() ."/public/Import/FUO0", $nama_file_import);

                DB::statement('drop table if exists temp1_fuo0_'.$no_file.'_'.$userId.', result_fuo0_'.$no_file.'_'.$userId.', syariah_fuo0_'.$no_file.'_'.$userId.', tanggal_report_fuo0_'.$no_file.'_'.$userId.'');

                DB::statement('create table temp1_fuo0_'.$no_file.'_'.$userId.'(fuo0 varchar(255))engine myisam');
                $sql = 'load data infile "D:/xampp/htdocs/cemtext/public/Import/FUO0/'.$nama_file_import.'" into table temp1_fuo0_'.$no_file.'_'.$userId.'';
                DB::connection()->getPdo()->exec($sql); 
                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column no int auto_increment primary key first');

                DB::statement('create table tanggal_report_fuo0_'.$no_file.'_'.$userId.'(narasi varchar(255), tanggal_report varchar(6))');
                DB::statement('insert into tanggal_report_fuo0_'.$no_file.'_'.$userId.'(narasi, tanggal_report) select fuo0 as narasi, mid(fuo0, 5, 6) as tanggal_report from temp1_fuo0_'.$no_file.'_'.$userId.' where mid(fuo0, 1, 4) like "%FHDR%"');
                
                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column tanggal_report varchar(6)');
                DB::statement('update temp1_fuo0_'.$no_file.'_'.$userId.', tanggal_report_fuo0_'.$no_file.'_'.$userId.' set temp1_fuo0_'.$no_file.'_'.$userId.'.tanggal_report = tanggal_report_fuo0_'.$no_file.'_'.$userId.'.tanggal_report');
                
                DB::statement('delete from temp1_fuo0_'.$no_file.'_'.$userId.' where mid(fuo0, 1, 4) not like "%FREC%"');
                
                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column kolom1 varchar(4)');
                DB::statement('update temp1_fuo0_'.$no_file.'_'.$userId.' set kolom1 = mid(fuo0, 1, 4)');

                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column kolom2 varchar(14)');
                DB::statement('update temp1_fuo0_'.$no_file.'_'.$userId.' set kolom2 = mid(fuo0, 5, 14)');

                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column kolom3 varchar(6)');
                DB::statement('update temp1_fuo0_'.$no_file.'_'.$userId.' set kolom3 = mid(fuo0, 19, 6)');

                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column kolom4 varchar(8)');
                DB::statement('update temp1_fuo0_'.$no_file.'_'.$userId.' set kolom4 = mid(fuo0, 25, 8)');

                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column kolom5 varchar(20)');
                DB::statement('update temp1_fuo0_'.$no_file.'_'.$userId.' set kolom5 = mid(fuo0, 33, 19)');

                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column kolom6 varchar(5)');
                DB::statement('update temp1_fuo0_'.$no_file.'_'.$userId.' set kolom6 = mid(fuo0, 53, 5)');

                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column kolom7 varchar(35)');
                DB::statement('update temp1_fuo0_'.$no_file.'_'.$userId.' set kolom7 = mid(fuo0, 58, 35)');

                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column kolom8 varchar(8)');
                DB::statement('update temp1_fuo0_'.$no_file.'_'.$userId.' set kolom8 = mid(fuo0, 93, 8)');

                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column kolom9 varchar(10)');
                DB::statement('update temp1_fuo0_'.$no_file.'_'.$userId.' set kolom9 = mid(fuo0, 101, 10)');

                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column kolom10 varchar(66)');
                DB::statement('update temp1_fuo0_'.$no_file.'_'.$userId.' set kolom10 = mid(fuo0, 111, 66)');

                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column kolom11 varchar(9)');
                DB::statement('update temp1_fuo0_'.$no_file.'_'.$userId.' set kolom11 = mid(fuo0, 177, 9)');

                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column kolom12 varchar(21)');
                DB::statement('update temp1_fuo0_'.$no_file.'_'.$userId.' set kolom12 = mid(fuo0, 186, 21)');

                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column kolom13 varchar(12)');
                DB::statement('update temp1_fuo0_'.$no_file.'_'.$userId.' set kolom13 = mid(fuo0, 207, 12)');

                DB::statement('alter table temp1_fuo0_'.$no_file.'_'.$userId.' add column kolom14 varchar(18)');
                DB::statement('update temp1_fuo0_'.$no_file.'_'.$userId.' set kolom14 = mid(fuo0, 219, 18)');

                DB::statement('delete from temp1_fuo0_'.$no_file.'_'.$userId.' where mid(kolom12, 14, 1) != "D"');

                DB::statement('create table result_fuo0_'.$no_file.'_'.$userId.'
                (
                    tanggal_report varchar(6),
                    frec varchar(4),
                    tanggal varchar(6),
                    nomor_kartu varchar(20),
                    id_1300 varchar(5),
                    id_atm varchar(8),
                    kode_mc2 varchar(10),
                    amount varchar(9)
                ) engine myisam');

                DB::statement('insert into result_fuo0_'.$no_file.'_'.$userId.'
                select
                    trim(tanggal_report) as tanggal_report,
                    trim(kolom1) as frec,
                    trim(kolom3) as tanggal,
                    trim(kolom5) as nomor_kartu,
                    trim(kolom6) as id_1300,
                    trim(kolom8) as id_atm,
                    trim(kolom9) as kode_mc2,
                    trim(kolom11) as amount
                from temp1_fuo0_'.$no_file.'_'.$userId.'');
                DB::statement('alter table result_fuo0_'.$no_file.'_'.$userId.' add column no int auto_increment primary key first');

                DB::statement('create table syariah_fuo0_'.$no_file.'_'.$userId.' like result_fuo0_'.$no_file.'_'.$userId.'');
                DB::statement('insert into syariah_fuo0_'.$no_file.'_'.$userId.' select * from result_fuo0_'.$no_file.'_'.$userId.' where nomor_kartu like "%518446%" or nomor_kartu like "%522028%" or nomor_kartu like "%531857%"');

                unlink(public_path("/Import/FUO0/$nama_file_import"));

                $no_file++;
            }

            if($jumlah_data == 2){
                if(\Schema::hasTable('result_fuo0_3_'.$userId.'')){
                    DB::statement('drop table if exists temp1_fuo0_3_'.$userId.', result_fuo0_3_'.$userId.', syariah_fuo0_3_'.$userId.', tanggal_report_fuo0_3_'.$userId.'');
                }
            }
            return Excel::download(new FUO0MultipleExport($userId), 'Summary FUO0.xlsx');
            // return redirect()->back()->with('alertSuccess', 'Data Berhasil Diparsing');
        }
    }
}
