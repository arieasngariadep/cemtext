<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CemtextModel extends Model
{
    public static function getDataCemtext767($userId)
    {
        $result = DB::table('result_sametex767_'.$userId.'')->select('*')->get();
        return $result;
    }

    public static function getDataCemtext777($userId)
    {
        $result = DB::table('result_cemtext777_'.$userId.'')->select('*')->get();
        return $result;
    }

    public static function getReportRekap($userId)
    {
        $result = DB::table('result_rekap_'.$userId.'')
        ->select('description', 'post_date', 'txn_date', 'to_Act', 'mid_no', 'merchant_name', 'shift', 'credit_date', 'credit_month', 'credit_year', 'txn_day', 'txn_month', 'txn_year', 'debit_amount')
        ->get();
        return $result;
    }

    public static function getDataRekeningKoran($userId)
    {
        $result = DB::table('result_rc_'.$userId.'')->select('*')->get();
        return $result;
    }

    // SDD DATA
    public static function getDataSDDCount($userId)
    {
        $result = DB::table('ssd_'.$userId.'')->select('*')->count();
        return $result;
    }

    public static function getDataReportSDDPage1Export($userId)
    {
        $result = DB::table('ssd_'.$userId.'')->select('*')->whereBetween('no', [1, 350000])->get();
        return $result;
    }

    public static function getDataReportSDDPage2Export($userId)
    {
        $result = DB::table('ssd_'.$userId.'')->select('*')->whereBetween('no', [350001, 700000])->get();
        return $result;
    }

    public static function getDataReportSDDPage3Export($userId)
    {
        $result = DB::table('ssd_'.$userId.'')->select('*')->whereBetween('no', [700001, 1050000])->get();
        return $result;
    }
    
    public static function getDataFuo0Export($userId)
    {
        $result = DB::table('result_fuo0_'.$userId.'')
        ->select('frec', 'tanggal', 'nomor_kartu', 'id_1300', 'id_atm', 'kode_mc2', \DB::raw('trim(leading "0" from amount) as amount'))
        ->get();
        return $result;
    }
    
    public static function getTanggalReportFUO0($userId)
    {
        $result = DB::table('result_fuo0_'.$userId.'')->select('tanggal_report')->first();
        return $result;
    }
    
    public static function getDataSyariahFuo0Export($userId)
    {
        $result = DB::table('syariah_fuo0_'.$userId.'')
        ->select('frec', 'tanggal', 'nomor_kartu', 'id_1300', 'id_atm', 'kode_mc2', \DB::raw('trim(leading "0" from amount) as amount'))
        ->get();
        return $result;
    }
    
    public static function getTanggalReportSyariahFUO0($userId)
    {
        $result = DB::table('syariah_fuo0_'.$userId.'')->select('tanggal_report')->first();
        return $result;
    }

    public static function getDataFuo0MultipleExport($no_file, $userId)
    {
        $result = DB::table('result_fuo0_'.$no_file.'_'.$userId.'')
        ->select('frec', 'tanggal', 'nomor_kartu', 'id_1300', 'id_atm', 'kode_mc2', \DB::raw('trim(leading "0" from amount) as amount'))
        ->get();
        return $result;
    }
    
    public static function getTanggalReportFUO0Multiple($no_file, $userId)
    {
        $result = DB::table('result_fuo0_'.$no_file.'_'.$userId.'')->select('tanggal_report')->first();
        return $result;
    }
    
    public static function getDataSyariahFuo0MultipleExport($no_file, $userId)
    {
        $result = DB::table('syariah_fuo0_'.$no_file.'_'.$userId.'')
        ->select('frec', 'tanggal', 'nomor_kartu', 'id_1300', 'id_atm', 'kode_mc2', \DB::raw('trim(leading "0" from amount) as amount'))
        ->get();
        return $result;
    }
    
    public static function getTanggalReportSyariahFUO0Multiple($no_file, $userId)
    {
        $result = DB::table('syariah_fuo0_'.$no_file.'_'.$userId.'')->select('tanggal_report')->first();
        return $result;
    }
    
    public static function getDataGiro($userId)
    {
        $result = DB::table('result_giro_'.$userId.'')
        ->select('*')
        ->get();
        return $result;
    }
}
