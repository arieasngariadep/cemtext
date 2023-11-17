<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Alert;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\QRDisputeExport;
use App\Models\QRDisputeModel;
use DateTime;

class QRDisputeController extends Controller
{
    public function formUploadQRDispute(Request $request)
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

        return view('QR_Dispute.formUploadQR', $data);
        // return view('QR_Dispute.reportQRDispute', $data);
    }

    public function prosesUploadQRDsipute(Request $request)
    {
        $file_import = $request->file('file_import');
        $ekstensi_file_import = $request->file('file_import')->getClientOriginalExtension();
        $mime_file_import = $request->file('file_import')->getMimeType();
        $userId = $request->session()->get('userId');
            
        $nama_file_import = 'qr_dispute_'.$userId.'.txt';
        $file_import->move(\base_path() ."/public/import/QR Dispute", $nama_file_import);

        DB::statement('drop table if exists temp1_qr_dispute_'.$userId.', temp2_qr_dispute_'.$userId.', temp3_qr_dispute_'.$userId.', result_qr_dispute_'.$userId.'');

        DB::statement('create table temp1_qr_dispute_'.$userId.' (kolom varchar(400))engine myisam');
        $sql = 'load data infile "C:/xampp/htdocs/cemtext/public/Import/QR Dispute/qr_dispute_'.$userId.'.txt" into table temp1_qr_dispute_'.$userId.'';
        DB::connection()->getPdo()->exec($sql); 
        DB::statement('alter table temp1_qr_dispute_'.$userId.' add column no int auto_increment primary key first');

        DB::statement('create table temp2_qr_dispute_'.$userId.' like temp1_qr_dispute_'.$userId.'');
        DB::statement('insert into temp2_qr_dispute_'.$userId.' select * from temp1_qr_dispute_'.$userId.' where mid(kolom, 17, 8) like "%/%"');
        DB::statement('delete from temp2_qr_dispute_'.$userId.' where mid(kolom, 1, 5) like "%TOTAL%"');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column nomor varchar(6)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set nomor = mid(kolom, 1, 6)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column trx_code varchar(6)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set trx_code = mid(kolom, 8, 6)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column tgl_trx varchar(8)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set tgl_trx = mid(kolom, 17, 8)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column jam_trx varchar(8)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set jam_trx = mid(kolom, 29, 8)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column ref_no varchar(12)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set ref_no = mid(kolom, 38, 12)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column trace_no varchar(6)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set trace_no = mid(kolom, 51, 6)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column terminal_id varchar(3)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set terminal_id = mid(kolom, 60, 3)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column merchant_pan varchar(20)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set merchant_pan = mid(kolom, 76, 20)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column acquirer varchar(8)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set acquirer = mid(kolom, 97, 8)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column issuer varchar(8)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set issuer = mid(kolom, 109, 8)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column customer_pan varchar(20)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set customer_pan = mid(kolom, 121, 20)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column nominal varchar(20)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set nominal = mid(kolom, 140, 17)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column merchant_category varchar(4)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set merchant_category = mid(kolom, 158, 4)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column merchant_criteria varchar(3)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set merchant_criteria = mid(kolom, 176, 3)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column response_code varchar(2)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set response_code = mid(kolom, 194, 2)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column merchant_name_and_location varchar(40)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set merchant_name_and_location = mid(kolom, 208, 40)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column convenience_fee varchar(16)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set convenience_fee = mid(kolom, 248, 16)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column interchange_fee varchar(16)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set interchange_fee = mid(kolom, 264, 16)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column reason_code varchar(4)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set reason_code = mid(kolom, 282, 4)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column dispute_tran_code varchar(2)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set dispute_tran_code = mid(kolom, 295, 2)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column dispute_amount varchar(16)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set dispute_amount = mid(kolom, 313, 16)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column fee_return varchar(16)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set fee_return = mid(kolom, 329, 16)');

        DB::statement('alter table temp2_qr_dispute_'.$userId.' add column dispute_net_amount varchar(16)');
        DB::statement('update temp2_qr_dispute_'.$userId.' set dispute_net_amount = mid(kolom, 345, 16)');

        DB::statement('update temp2_qr_dispute_'.$userId.' set 
        dispute_net_amount = replace(dispute_net_amount, ".00", ""),
        dispute_amount = replace(dispute_amount, ".00", ""),
        fee_return = replace(fee_return, ".00", ""),
        interchange_fee = replace(interchange_fee, ".00", ""),
        nominal = replace(nominal, ".00", ""),
        dispute_net_amount = replace(dispute_net_amount, ",", ""),
        dispute_amount = replace(dispute_amount, ",", ""),
        fee_return = replace(fee_return, ",", ""),
        interchange_fee = replace(interchange_fee, ",", ""),
        nominal = replace(nominal, ",", "")');

        DB::statement('create table temp3_qr_dispute_'.$userId.' (tanggalReport varchar(8))engine myisam');
        DB::statement('insert into temp3_qr_dispute_'.$userId.' select mid(kolom, 229, 8) from temp1_qr_dispute_'.$userId.' where mid(kolom, 208, 14) like "%Tanggal Report%"');

        DB::statement('create table result_qr_dispute_'.$userId.'
        (
            nomor varchar(6),
            trx_code varchar(6),
            tgl_trx varchar(8),
            jam_trx varchar(8),
            ref_no varchar(12),
            trace_no varchar(6),
            terminal_id varchar(3),
            merchant_pan varchar(20),
            acquirer varchar(8),
            issuer varchar(8),
            customer_pan varchar(20),
            nominal varchar(20),
            merchant_category varchar(4),
            merchant_criteria varchar(3),
            response_code varchar(2),
            merchant_name_and_location varchar(40),
            convenience_fee varchar(16),
            interchange_fee varchar(16),
            reason_code varchar(4),
            dispute_tran_code varchar(2),
            dispute_amount varchar(16),
            fee_return varchar(16),
            dispute_net_amount varchar(16)
        )engine myisam');

        DB::statement('insert into result_qr_dispute_'.$userId.'
        select
            trim(nomor) as nomor,
            trim(trx_code) as trx_code,
            trim(tgl_trx) as tgl_trx,
            trim(jam_trx) as jam_trx,
            trim(ref_no) as ref_no,
            trim(trace_no) as trace_no,
            trim(terminal_id) as terminal_id,
            trim(terminal_id) as merchant_pan,
            trim(acquirer) as acquirer,
            trim(issuer) as issuer,
            trim(customer_pan) as customer_pan,
            trim(nominal) as nominal,
            trim(merchant_category) as merchant_category,
            trim(merchant_criteria) as merchant_criteria,
            trim(response_code) as response_code,
            trim(merchant_name_and_location) as merchant_name_and_location,
            trim(convenience_fee) as convenience_fee,
            trim(interchange_fee) as interchange_fee,
            trim(reason_code) as reason_code,
            trim(dispute_tran_code) as dispute_tran_code,
            trim(dispute_amount) as dispute_amount,
            trim(fee_return) as fee_return,
            trim(dispute_net_amount) as dispute_net_amount
        from temp2_qr_dispute_'.$userId.'');

        return Excel::download(new QRDisputeExport($userId), 'Summary QR Dispute.xlsx');
        // return redirect()->back()->with('alertSuccess', 'Data Berhasil Diparsing');
    }
}