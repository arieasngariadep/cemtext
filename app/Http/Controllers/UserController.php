<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Alert;
use Illuminate\Support\Facades\DB;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\HistoryAccountModel;
use App\Models\KelompokModel;

class UserController extends Controller
{
    public function getAllUser(Request $request, UserModel $userModel)
    {
        $role = $request->session()->get('role_id');
        $kelompok = $request->session()->get('kelompok_id');
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

        if($role == 7){
            $userList = $userModel->getUserBySuperAdmin();
        }else{
            $userList = $userModel->getUserByAdminKelompok($kelompok);
        }

        $data = array(
            'role' => $role,
            'alert' => $showalert,
            'userList' => $userList,
        );
        return view('user.user', $data);
    }

    public function formAddUser(Request $request, RoleModel $roleModel, KelompokModel $kelompokModel)
    {
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

        if($role == 7){
        	$roleList = $roleModel->getAllRoleBySuperAdmin();
		}else{
        	$roleList = $roleModel->getAllRoleByAdminKelompok();
		}
        $kelompokList = $kelompokModel->getAllKelompok();

        $data = array(
            'role' => $role,
            'alert' => $showalert,
            'roleList' => $roleList,
            'kelompokList' => $kelompokList
        );
        return view('user.form_add_user', $data);
    }

    public function prosesAddUser(Request $request, UserModel $userModel, HistoryAccountModel $history)
    {
        date_default_timezone_set("Asia/Bangkok"); //set you countary name from below timezone list
        $password = Hash::make($request->password);
        $checkUser = $userModel->checkEmailExists($request->email);

        if($checkUser == 1){
            return redirect('user/add-new-user')->with('alert', 'Email Already Taken');
        }elseif($request->password != $request->cpassword){
            return redirect('user/add-new-user')->with('alert', 'Please Enter Same Password');
        }else{
            $aktifitas = "Menambahkan User Baru";
            $link = base64_encode(random_bytes(32));
            $today = date('Y-m-d');
            $expired_date = date('Y-m-d', strtotime("+90 days", strtotime($today)));
            $data = array(
                'email' => $request->email,
                'password' => $password,
                'name' => $request->fname,
                'role_id' => $request->roleId,
                'kelompok_id' => $request->kelompokId,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s"),
            );
            $userModel->insertUser($data);

            $dataHistory = array(
                'nama_penginput' => $request->name,
                'aktifitas' => $aktifitas,
                'email' => $request->email,
                'old_password' => $password,
                'new_password' => $password,
                'activation_link' => $link,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s"),
            );
            $history->insertHistoryAccount($dataHistory);

            return redirect('user')->with('alertSuccess', 'User Successfully Added');
        }
    }

    public function formUpdateUser(Request $request, UserModel $userModel, RoleModel $roleModel, KelompokModel $kelompokModel)
    {
        $role = $request->session()->get('role_id');
        $alert = $request->session()->get('alert');
        $alertSuccess = $request->session()->get('alertSuccess');
        $alertInfo = $request->session()->get('alertInfo');
        $uri3 = $request->segment(3);
        if($alertSuccess){
            $showalert = Alert::alertSuccess($alertSuccess);
        }else if($alertInfo){
            $showalert = Alert::alertinfo($alertInfo);
        }else{
            $showalert = Alert::alertDanger($alert);
        }

        if($role == 7){
        	$roleList = $roleModel->getAllRoleBySuperAdmin();
		}else{
        	$roleList = $roleModel->getAllRoleByAdminKelompok();
		}
        $kelompokList = $kelompokModel->getAllKelompok();
        $user = $userModel->getUserById($request->id);

        $data = array(
            'role' => $role,
            'alert' => $showalert,
            'roleList' => $roleList,
            'kelompokList' => $kelompokList,
            'user' => $user,
        );
        return view('user.form_update_user', $data);
    }

    public function prosesUpdateUser(Request $request, UserModel $userModel, HistoryAccountModel $history)
    {
        date_default_timezone_set("Asia/Bangkok"); //set you countary name from below timezone list
        $uri3 = $request->segment(3);
        $id = $request->id;
        $oldPassword = $request->oldPassword;
        $password = Hash::make($request->password);
        $checkUser = $userModel->checkEmailExists($request->email);
        if($checkUser > 1){
            return redirect('user/update-user/'.$id)->with('alert', 'Email Already Taken');
        }elseif($request->password != $request->cpassword){
            return redirect('user/update-user/'.$id)->with('alert', 'Please Enter Same Password');
        }else{
            $aktifitas = "Melakukan Perubahan Data User";
            $link = base64_encode(random_bytes(32));
            $data = array(
                'email' => $request->email,
                'password' => $password,
                'name' => $request->fname,
                'role_id' => $request->roleId,
                'kelompok_id' => $request->kelompokId,
                'updated_at' => date("Y-m-d h:i:s"),
            );
            DB::table('user')->where('user_id', $id)->update($data);

            $dataHistory = array(
                'nama_penginput' => $request->name,
                'aktifitas' => $aktifitas,
                'email' => $request->email,
                'old_password' => $oldPassword,
                'new_password' => $password,
                'activation_link' => $link,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s"),
            );
            $history->insertHistoryAccount($dataHistory);

            return redirect('user')->with('alertSuccess', 'User Successfully Updated');
        }
    }

    public function deleteUser(Request $request, UserModel $userModel){
        $id = $request->id;
        $userModel->deleteUser($id);
        return redirect()->back()->with('alertSuccess', 'Useer Has Been Deleted');
    }
}
