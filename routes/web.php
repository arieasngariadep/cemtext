<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CemtextController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\SSDController;
use App\Http\Controllers\RekeningKoranController;
use App\Http\Controllers\BniRegController;
use App\Http\Controllers\Fuo0Controller;
use App\Http\Controllers\GiroController;
use App\Http\Controllers\GiroInternalController;
use App\Http\Controllers\QRDisputeController;
use App\Http\Controllers\RekapAnalisaController;
use App\Http\Controllers\RptController;
use App\Http\Controllers\AmexController;
use App\Http\Controllers\IncomingController;
use App\Http\Controllers\BndController;
use App\Http\Controllers\CMODController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ================================ Authentication Routing ================================ //
Route::get('/login', [AuthenticationController::class, 'login'])->name('login');

Route::post('/loginProcess', [AuthenticationController::class, 'loginProcess'])->name('loginProcess');
Route::get('/success', [AuthenticationController::class, 'successLogin'])->name('successLogin')->middleware('checkauth');
Route::post('change_password', [AuthenticationController::class, 'prosesChangePassword'])->name('prosesChangePassword');

Route::get('/logout', [AuthenticationController::class, 'logout'])->name('logout')->middleware('checkauth');
// ================================ End Authentication Routing ================================ //

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard')->middleware('checkauth');

// ================================ User Routing ================================ //
Route::prefix('user')->group(function(){
    Route::get('/', [UserController::class, 'getAllUser'])->name('getAllUser')->middleware('checkauth');
    Route::get('/add-new-user', [UserController::class, 'formAddUser'])->name('formAddUser')->middleware('checkauth');
    Route::get('/update-user/{id}', [UserController::class, 'formUpdateUser'])->name('formUpdateUser')->middleware('checkauth');
});

Route::post('proses_tambah_user', [UserController::class, 'prosesAddUser'])->name('prosesAddUser');
Route::post('proses_update_user', [UserController::class, 'prosesUpdateUser'])->name('prosesUpdateUser');
Route::get('proses_delete_user/{id}', [UserController::class, 'deleteUser'])->name('deleteUser');
Route::post('check_email_exists', [UserController::class, 'checkEmailExists'])->name('checkEmailExists');
// ================================ User Routing ================================ //

// ================================ Upload Cemtext Routing ================================ //
Route::get('/form_upload_cemtext', [CemtextController::class, 'formUploadCemtext'])->name('formUploadCemtext')->middleware('checkauth');
Route::post('proses_upload_cemtext767', [CemtextController::class, 'prosesUploadCemtext767'])->name('prosesUploadCemtext767')->middleware('checkauth');
Route::post('proses_upload_cemtext777', [CemtextController::class, 'prosesUploadCemtext777'])->name('prosesUploadCemtext777')->middleware('checkauth');
// ================================ End Upload Cemtext Routing ================================ //

// ================================ Upload Rekap Routing ================================ //
Route::get('/form_upload_rekap', [RekapController::class, 'formUploadRekap'])->name('formUploadRekap')->middleware('checkauth');
Route::post('proses_upload_rekap', [RekapController::class, 'prosesImportRekap'])->name('prosesImportRekap')->middleware('checkauth');
// ================================ End Upload Rekap Routing ================================ //

// ================================ Upload SSD Routing ================================ //
Route::get('/form_upload_ssd', [SSDController::class, 'formUploadSSD'])->name('formUploadSSD')->middleware('checkauth');
Route::post('proses_upload_ssd', [SSDController::class, 'prosesUploadFile'])->name('prosesUploadFile')->middleware('checkauth');
Route::get('/formDownloadReportSDD', [SSDController::class, 'formDownloadReportSDD'])->name('formDownloadReportSDD')->middleware('checkauth');
Route::post('getDataExportSDDPage1', [SSDController::class, 'getDataExportSDDPage1'])->name('getDataExportSDDPage1')->middleware('checkauth');
Route::post('getDataExportSDDPage2', [SSDController::class, 'getDataExportSDDPage2'])->name('getDataExportSDDPage2')->middleware('checkauth');
Route::post('getDataExportSDDPage3', [SSDController::class, 'getDataExportSDDPage3'])->name('getDataExportSDDPage3')->middleware('checkauth');
// ================================ End Upload SSD Routing ================================ //

// ================================ Upload Rekening Koran Routing ================================ //
Route::get('/form_upload_rc', [RekeningKoranController::class, 'formUploadRC'])->name('formUploadRC')->middleware('checkauth');
Route::post('proses_upload_rc', [RekeningKoranController::class, 'prosesUploadSumamryRC'])->name('prosesUploadSumamryRC')->middleware('checkauth');
// ================================ End Upload Rekening Koran Routing ================================ //

// ================================ Upload BNI REG Routing ================================ //
Route::get('/formUploadBNIREG', [BniRegController::class, 'formUploadBniReg'])->name('formUploadBniReg')->middleware('checkauth');
Route::post('prosesUploadBniReg', [BniRegController::class, 'prosesUploadBniReg'])->name('prosesUploadBniReg')->middleware('checkauth');
// ================================ End Upload BNI REG Routing ================================ //

// ================================ Upload Giro Internal Routing ================================ //
Route::get('/formUploadGiroInternal', [GiroInternalController::class, 'formUploadGiroInternal'])->name('formUploadGiroInternal')->middleware('checkauth');
Route::post('prosesUploadGiroInternal', [GiroInternalController::class, 'prosesUploadGiroInternal'])->name('prosesUploadGiroInternal')->middleware('checkauth');
// ================================ End Upload BNI REG Routing ================================ //

// ================================ Upload FUO0 Routing ================================ //
Route::get('/formUploadFUO0', [Fuo0Controller::class, 'formUploadFuo0'])->name('formUploadFuo0')->middleware('checkauth');
Route::post('prosesUploadFuo0', [Fuo0Controller::class, 'prosesUploadFuo0'])->name('prosesUploadFuo0')->middleware('checkauth');
// ================================ End Upload FUO0 Routing ================================ //

// ================================ Upload Giro Routing ================================ //
Route::get('/formUploadGiro', [GiroController::class, 'formUploadGiro'])->name('formUploadGiro')->middleware('checkauth');
Route::post('prosesUploadGiro', [GiroController::class, 'prosesUploadGiro'])->name('prosesUploadGiro')->middleware('checkauth');
// ================================ End Upload Giro Routing ================================ //

// ================================ Upload CMOD Routing ================================ //
Route::get('/formUploadCMOD', [CMODController::class, 'formUploadCMOD'])->name('formUploadCMOD')->middleware('checkauth');
Route::post('prosesUploadCMOD', [CMODController::class, 'prosesUploadCMOD'])->name('prosesUploadCMOD')->middleware('checkauth');
// ================================ End Upload CMOD Routing ================================ //

// ================================ Upload QR Dispute Routing ================================ //
Route::get('/formUploadQRDispute', [QRDisputeController::class, 'formUploadQRDispute'])->name('formUploadQRDispute')->middleware('checkauth');
Route::post('prosesUploadQRDsipute', [QRDisputeController::class, 'prosesUploadQRDsipute'])->name('prosesUploadQRDsipute')->middleware('checkauth');
// ================================ End Upload QR Dispute Routing ================================ //

// ================================ Upload QR Dispute Routing ================================ //
Route::get('/formUploadAmex', [AmexController::class, 'formUploadAmex'])->name('formUploadAmex')->middleware('checkauth');
Route::post('prosesUploadAmex', [AmexController::class, 'prosesUploadAmex'])->name('prosesUploadAmex')->middleware('checkauth');
// ================================ End Upload QR Dispute Routing ================================ //

// ================================ Upload QR Dispute Routing ================================ //
Route::get('/formUploadIncoming', [IncomingController::class, 'formUploadIncoming'])->name('formUploadIncoming')->middleware('checkauth');
Route::post('prosesUploadIncoming', [IncomingController::class, 'prosesUploadIncoming'])->name('prosesUploadIncoming')->middleware('checkauth');
// ================================ End Upload QR Dispute Routing ================================ //

// ================================ Upload QR Dispute Routing ================================ //
Route::get('/formUploadNpre', [BndController::class, 'formUploadNpre'])->name('formUploadNpre')->middleware('checkauth');
Route::post('prosesUploadNpre', [BndController::class, 'prosesUploadNpre'])->name('prosesUploadNpre')->middleware('checkauth');
// ================================ End Upload QR Dispute Routing ================================ //
