<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Alert;
use App\Models\UserModel;
use App\Models\HistoryAccountModel;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
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
        $passing = array(
            'alert' => $showalert,
        );
        return view('auth/login', $passing);
    }

    public function loginProcess(Request $request, UserModel $user)
    {
        date_default_timezone_set("Asia/Jakarta");
        $today = date("Y-m-d");
        $email = $request->email;
        $password  = $request->password;
        $userLogin = $user->getLoginUser($email);
        if($userLogin){ //apakah email tersebut ada atau tidak
            $pass = $userLogin->password;
            $verify_pass = password_verify($password, $pass);
            if($verify_pass){
                $ses_data = array(
                    'userId' => $userLogin->userId,
                    'username' => $userLogin->name,
                    'email' => $userLogin->email,
                    'password' => $userLogin->password,
                    'role_id' => $userLogin->role_id,
                    'role_name' => $userLogin->role_name,
                    'kelompok_id' => $userLogin->kelompok_id,
                    'unit' => $userLogin->unit,
                    'isLogin' => TRUE
                );
                $request->session()->put($ses_data);
                return redirect('dashboard');
            }else{
                return redirect()->back()->with('alert', 'Wrong Password');
            }
        }else{
            return redirect('/login')->with('alert', 'Email or Password Unmatched');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/login');
    }

    public function prosesChangePassword(Request $request, UserModel $userModel, HistoryAccountModel $history)
    {
        date_default_timezone_set("Asia/Bangkok"); //set you countary name from below timezone list
        if($request->password != $request->confirm_password){
            return redirect()->back()->with('alert', 'Please Enter Same Password');
        }else{
            $password = Hash::make($request->password);
            $data = array(
                'password' => $password,
                'updated_at' => date("Y-m-d h:i:s"),
            );
            UserModel::where('user_id', $request->userId)->update($data);

            $aktifitas = "Melakukan Perubahan Password";
            $link = base64_encode(random_bytes(32));
            $today = date('Y-m-d');
            $username = $request->session()->get('username');
            $email = $request->session()->get('email');
            $oldPassword = $request->session()->get('password');

            $dataHistory = array(
                'nama_penginput' => $username,
                'aktifitas' => $aktifitas,
                'email' => $email,
                'old_password' => $oldPassword,
                'new_password' => $password,
                'activation_link' => $link,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s"),
            );
            $history->insertHistoryAccount($dataHistory);

            return redirect()->back()->with('alertSuccess', 'Pasword Has Been Changed');
        }
    }
}