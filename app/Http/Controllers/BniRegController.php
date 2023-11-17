<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Alert;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BniRegController extends Controller
{
    public function formUploadBniReg(Request $request)
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
        return view('formUploadBniReg', $data);
    }

    public function prosesUploadBniReg(Request $request)
    {
        $userId = $request->session()->get('userId');
        foreach ($request->file('file_import') as $file_import) {
            $nama_file_import = $file_import->getClientOriginalName();
            $file_import->move(\base_path() ."/public/Import/folder_$userId/BNI/Data", $nama_file_import);
        }

        $process = Process::fromShellCommandline('cd C:\xampp\htdocs\cemtext\public\Import\folder_'.$userId.'\BNI\Data && copy *.txt bni_'.$userId.'.txt');
        $process->setTimeout(3600);
        $process->run();

        foreach ($request->file('file_import') as $file_import) {
            unlink(base_path('public/Import/folder_'.$userId.'/BNI/Data/'.$file_import->getClientOriginalName()));
        }

        $process = Process::fromShellCommandline('cd C:\xampp\htdocs\cemtext\public\Python\BNI && python bni.py '.$userId.'');
        try {
            $process->setTimeout(3600);
            $process->mustRun();
            
            unlink(base_path('public/Import/folder_'.$userId.'/BNI/Data/BNI_'.$userId.'.txt'));
            $pathToFile = base_path('public/Import/folder_'.$userId.'/BNI/HasilBNI.xlsx');

            return response()->download($pathToFile)->deleteFileAfterSend(true);
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }
    }
}
