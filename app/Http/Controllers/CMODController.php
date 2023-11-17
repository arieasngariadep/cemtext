<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Alert;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CMODController extends Controller{

    public function formUploadCMOD(Request $request)
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
        return view('CMOD.formUploadCMOD', $data);
    }

    public function prosesUploadCMOD(Request $request)
    {
        $userId = $request->session()->get('userId');
        foreach ($request->file('file_import') as $file_import) {
            $nama_file_import = $file_import->getClientOriginalName();
            $fileName = substr($nama_file_import,0,-5);
            $file_import->move(\base_path() ."/public/Import/folder_$userId/CMOD/Data", $nama_file_import);
        }

        $process = Process::fromShellCommandline('cd C:\xampp\htdocs\cemtext\public\Import\folder_'.$userId.'\CMOD\Data && copy *.prt1 '.$fileName.'.txt');
        $process->setTimeout(3600);
        $process->run();

        foreach ($request->file('file_import') as $file_import) {
            unlink(base_path('public/Import/folder_'.$userId.'/CMOD/Data/'.$file_import->getClientOriginalName()));
        }

        $process = Process::fromShellCommandline('cd C:\xampp\htdocs\cemtext\public\Python\CMOD && python cmod.py '.$userId.'');
        try {
            $process->setTimeout(3600);
            $process->mustRun();
            
            unlink(base_path('public/Import/folder_'.$userId.'/CMOD/Data/'.$fileName.'.txt'));
            $pathToFile = base_path('public/Import/folder_'.$userId.'/CMOD/HasilCMOD-'.$fileName.'.xlsx');

            return response()->download($pathToFile)->deleteFileAfterSend(true);
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }
    }

}

?>