<?php
namespace App\Helpers;

class Alert {
    public static function alertDanger($msg){
        if($msg){
        $alert = '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert">
                    <i class="mdi mdi-alert-outline alert-icon"></i>
                    <div class="alert-text">
                        <strong>Gagal!</strong> '.$msg.'
                    </div>
                    
                    <div class="alert-close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="mdi mdi-close text-danger"></i></span>
                        </button>
                    </div>
                </div>';
        }else{
            $alert = false;
        }
        return $alert;
    }
    public static function alertSuccess($msg){
        if($msg){
        $alert = '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert">
                    <i class="mdi mdi-alert-outline alert-icon"></i>
                    <div class="alert-text">
                        <strong>Berhasil!</strong> '.$msg.'
                    </div>
                    
                    <div class="alert-close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="mdi mdi-close text-success"></i></span>
                        </button>
                    </div>
                </div>';
        }else{
            $alert = false;
        }
        return $alert;
    }
    public static function alertInfo($msg){
        if($msg){
        $alert = '<div class="alert icon-custom-alert alert-outline-purple b-round fade show" role="alert">
                    <i class="mdi mdi-alert-outline alert-icon"></i>
                    <div class="alert-text">
                        <strong>Info!</strong> '.$msg.'
                    </div>
                    <div class="alert-close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="mdi mdi-close text-purple"></i></span>
                        </button>
                    </div>
                </div>';
        }else{
            $alert = false;
        }
        return $alert;
    }
}