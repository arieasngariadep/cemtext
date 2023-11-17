<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QRDisputeModel extends Model
{   
    public static function getTanggalReport($userId)
    {
        $result = DB::table('temp3_qr_dispute_'.$userId.'')->select('*')->first();
        return $result;
    }

    // Get Data Hak
    public static function getDataDisputeKode30Hak($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '30')
        ->where('acquirer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode31Hak($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '31')
        ->where('acquirer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode40Hak($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '40')
        ->where('acquirer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode41Hak($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '41')
        ->where('acquirer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode81Hak($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '81')
        ->where('acquirer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode82Hak($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '82')
        ->where('acquirer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode91Hak($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '91')
        ->where('acquirer', '93600009')->first();
        return $result;
    }

    public static function getDataDisputeKode92Hak($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '92')
        ->where('acquirer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode93Hak($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '93')
        ->where('acquirer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode94Hak($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '94')
        ->where('acquirer', '93600009')->first();
        return $result;
    }

    // Get Data Kewajiban
    public static function getDataDisputeKode30Kewajiban($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '30')
        ->where('issuer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode31Kewajiban($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '31')
        ->where('issuer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode40Kewajiban($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '40')
        ->where('issuer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode41Kewajiban($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '41')
        ->where('issuer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode81Kewajiban($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '81')
        ->where('issuer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode82Kewajiban($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '82')
        ->where('issuer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode91Kewajiban($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '91')
        ->where('issuer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode92Kewajiban($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '92')
        ->where('issuer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode93Kewajiban($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '93')
        ->where('issuer', '93600009')->first();
        return $result;
    }
    
    public static function getDataDisputeKode94Kewajiban($userId)
    {
        $result = DB::table('result_qr_dispute_'.$userId.'')->select(\DB::raw('count(nomor) as total_item'), \DB::raw('sum(fee_return) as fee_return'), \DB::raw('sum(dispute_net_amount) as dispute_net_amount'))
        ->where('dispute_tran_code', '94')
        ->where('issuer', '93600009')->first();
        return $result;
    }
}
