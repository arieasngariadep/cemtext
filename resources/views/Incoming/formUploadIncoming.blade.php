@extends('layout.index')
@section('title', 'Upload File Incoming')
@section('titleTab', 'Upload File Incoming')

@section('content')
<div class="row">
    <div class="col-lg-12 col-xl-12">
        <?php if($alert): ?>
        <div class="card m-b-30">
            <div class="card-body">
                <?= $alert ?>
            </div>
        </div>
        <?php endif;?>
    </div>
</div>

<div class="row">
    <div class="col-xl-12 justify-content-center">
        <div class="card">
            <div class="card-body col-xl-11 ml-5">
                <h4 class="mt-0 header-title mb-4">Form Upload Incoming</h4>
                
                <form id="submit" action="{{ route('prosesUploadIncoming') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div id="form-incoming" class="form-horizontal form-wizard-wrapper">
                        <h3>Upload Data A</h3>
                        <fieldset>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <input type="file" id="input-file-now" class="dropify" name="file_import_a" required />
                                        </div>
                                    </div><!--end form-group-->
                                </div><!--end col-->
                            </div><!--end row-->
                        </fieldset><!--end fieldset-->
                      
                        <h3>Upload Data B</h3>
                        <fieldset>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <input type="file" id="input-file-now" class="dropify" name="file_import_b" required />
                                        </div>
                                    </div><!--end form-group-->
                                </div><!--end col-->
                            </div><!--end row-->
                        </fieldset><!--end fieldset-->
                    </div>
                </form><!--end form-->
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->
</div><!--end row-->
@endsection('content')