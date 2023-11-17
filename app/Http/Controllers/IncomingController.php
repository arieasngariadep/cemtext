<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Alert;
use ZanySoft\Zip\Zip;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class IncomingController extends Controller
{
    public function formUploadIncoming(Request $request)
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
        return view('Incoming.formUploadIncoming', $data);
    }

    public function prosesUploadIncoming(Request $request)
    {
        $userId = $request->session()->get('userId');

        $file_import_a = $request->file('file_import_a');
        $nama_file_import_a = 'File_A.xlsx';
        $file_import_a->move(\base_path() ."/public/Import/folder_$userId/Incoming", $nama_file_import_a);

        $file_import_b = $request->file('file_import_b');
        $nama_file_import_b = 'File_B.xlsx';
        $file_import_b->move(\base_path() ."/public/Import/folder_$userId/Incoming", $nama_file_import_b);

        $process = Process::fromShellCommandline('cd C:\xampp\htdocs\cemtext\public\Python\Incoming && python Incoming.py '.$userId.'');
        $process->setTimeout(3600);
        $process->mustRun();
        
        unlink(base_path('public/Import/folder_'.$userId.'/Incoming/File_A.xlsx'));
        unlink(base_path('public/Import/folder_'.$userId.'/Incoming/File_B.xlsx'));

        $zip = Zip::create(base_path('public/Import/folder_'.$userId.'/Incoming/HasilIncoming.zip'));
        $file1 = base_path('public/Import/folder_'.$userId.'/Incoming/HasilJoin.xlsx');
        $file2 = base_path('public/Import/folder_'.$userId.'/Incoming/Summary.xlsx');
        $zip->add(array($file1, $file2))->close();

        $pathToFile = base_path('public/Import/folder_'.$userId.'/Incoming/HasilIncoming.zip');
        unlink(base_path('public/Import/folder_'.$userId.'/Incoming/HasilJoin.xlsx'));
        unlink(base_path('public/Import/folder_'.$userId.'/Incoming/Summary.xlsx'));

        return response()->download($pathToFile)->deleteFileAfterSend(true);
    }
}
