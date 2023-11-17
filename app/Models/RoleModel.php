<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    protected $table = "role";
    protected $guarded = [];

    public function getAllRoleByAdminKelompok()
    {
        $role = new RoleModel();
        $data = $role
        ->select('*')
        ->where('role_id', '!=', 1)
        ->where('role_id', '!=', 7)
        ->get();
        return $data;
    }

    public function getAllRoleBySuperAdmin()
    {
        $role = new RoleModel();
        $data = $role
        ->select('*')
        ->get();
        return $data;
    }
}
