<?php
    $uri = Request::segment(1);
    $role = Session::get('role_id');
    $kelompok_id = Session::get('kelompok_id');
    $unit = Session::get('unit');
?>

<div class="left-sidenav">
    <ul class="metismenu left-sidenav-menu">
        <li class="<?= ($uri == 'dashboard' ? 'mm-active' : ''); ?>">
            <a href="<?= route('dashboard') ?>"><i class="ti-bar-chart"></i><span>Dashboard</span></a>
        </li>

        <?php if($role == 7 || $unit == "PAL" || $unit == "AKU") : ?>
        <li class="<?= ($uri == 'form_upload_rc' ? 'mm-active' : ''); ?>">
            <a href="<?= route('formUploadRC') ?>"><i class="ti-money"></i><span>Upload RC</span></a>
        </li>
        <?php endif; ?>

        <?php if($role == 7 || $unit == "AKU") : ?>
        <li class="<?= ($uri == 'formUploadBNIREG' ? 'mm-active' : ''); ?>">
            <a href="<?= route('formUploadBniReg') ?>"><i class="ti-save"></i><span>Upload BNI REG</span></a>
        </li>
        <li class="<?= ($uri == 'formUploadGiroInternal' ? 'mm-active' : ''); ?>">
            <a href="<?= route('formUploadGiroInternal') ?>"><i class="ti-bookmark-alt"></i><span>Upload Giro Internal</span></a>
        </li>
        <?php endif; ?>

        <?php if($unit == "AKU") : ?>
        <li class="<?= ($uri == 'formUploadCMOD' ? 'mm-active' : ''); ?>">
            <a href="<?= route('formUploadCMOD') ?>"><i class="ti-bookmark-alt"></i><span>Upload CMOD</span></a>
        </li>
        <li class="<?= ($uri == 'formUploadGiro' ? 'mm-active' : ''); ?>">
            <a href="<?= route('formUploadGiro') ?>"><i class="ti-bookmark-alt"></i><span>Upload Giro</span></a>
        </li>
        <li class="<?= ($uri == 'form_upload_ssd' ? 'mm-active' : '') ?>">
            <a href="<?= route('formUploadSSD') ?>"><i class="ti-bookmark"></i><span>Upload SDD</span></a>
        </li>
        <?php endif; ?>

        <?php if($role == 7 || $unit == "PPS") : ?>
        <li class="<?= ($uri == 'formUploadFUO0' ? 'mm-active' : ''); ?>">
            <a href="<?= route('formUploadFuo0') ?>"><i class="ti-briefcase"></i><span>Upload FUO0</span></a>
        </li>
        <li class="<?= ($uri == 'formUploadQRDispute' ? 'mm-active' : ''); ?>">
            <a href="<?= route('formUploadQRDispute') ?>"><i class="ti-receipt"></i><span>Upload QR Dispute</span></a>
        </li>
        <li class="<?= ($uri == 'formUploadAmex' ? 'mm-active' : ''); ?>">
            <a href="<?= route('formUploadAmex') ?>"><i class="ti-blackboard"></i><span>Upload Amex</span></a>
        </li>
        <li class="<?= ($uri == 'formUploadIncoming' ? 'mm-active' : ''); ?>">
            <a href="<?= route('formUploadIncoming') ?>"><i class="ti-clipboard"></i><span>Upload Incoming</span></a>
        </li>
        <?php endif; ?>
        
        <?php if($role == 7 || $kelompok_id == 6 || ($role == 1 && $kelompok_id == 6)) : ?>
        <li class="<?= ($uri == 'form_upload_cemtext' ? 'mm-active' : ''); ?>">
            <a href="<?= route('formUploadCemtext') ?>"><i class="ti-list"></i><span>Upload Cemtext</span></a>
        </li>
        <li class="<?= ($uri == 'form_upload_rekap' ? 'mm-active' : ''); ?>">
            <a href="<?= route('formUploadRekap') ?>"><i class="ti-server"></i><span>Upload BNI Direct</span></a>
        </li>
        <li class="<?= ($uri == 'formUploadGiro' ? 'mm-active' : ''); ?>">
            <a href="<?= route('formUploadGiro') ?>"><i class="ti-bookmark-alt"></i><span>Upload Giro</span></a>
        </li>
        <li class="<?= ($uri == 'formUploadCMOD' ? 'mm-active' : ''); ?>">
            <a href="<?= route('formUploadCMOD') ?>"><i class="ti-bookmark-alt"></i><span>Upload CMOD</span></a>
        </li>
        <li class="<?= ($uri == 'form_upload_ssd' ? 'mm-active' : '') ?>">
            <a href="<?= route('formUploadSSD') ?>"><i class="ti-bookmark"></i><span>Upload SDD</span></a>
        </li>
        <li class="<?= ($uri == 'formUploadNpre' ? 'mm-active' : '') ?>">
            <a href="<?= route('formUploadNpre') ?>"><i class="ti-desktop"></i><span>Upload Npre</span></a>
        </li>
        <?php endif; ?>

        <?php if($role == 1 || $role == 7) : ?>
        <li class="<?= ($uri == 'user' ? 'mm-active' : ''); ?>">
            <a href="<?= route('getAllUser') ?>"><i class="ti-layers-alt"></i><span>User</span></a>
        </li>
        <?php endif; ?>
    </ul>
</div>
