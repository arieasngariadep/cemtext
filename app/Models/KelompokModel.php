<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokModel extends Model
{
    protected $table = 'kelompok';
    protected $guarded = [];
    public $timestamps = false;
    protected $fillable = ['id', 'kelompok'];

    public function getAllKelompok()
    {
        $kelompok = new KelompokModel();
        $data = $kelompok
        ->select('*')
        ->get();
        return $data;
    }
}
