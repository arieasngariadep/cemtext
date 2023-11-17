<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryAccountModel extends Model
{
    protected $table = "history_account";
    protected $guarded = [];
    public $timestamps = false;

    public function insertHistoryAccount($data){
        $history = new HistoryAccountModel;
        $save = $history->create($data);
        return $save;
    }
}
