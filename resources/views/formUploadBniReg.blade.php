@extends('layout.index')
@section('title', 'Upload BNI REG')
@section('titleTab', 'Upload BNI REG')

@section('content')
<div class="row">
    <div class="col-xl-12 justify-content-center">
        <div class="card">
            <div class="card-body col-xl-11 ml-5">
                <h4 class="mt-0 header-title">Form Upload Summary BNI REG</h4>
                <?= $alert ?>
                <form action="<?= route('prosesUploadBniReg') ?>" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <input type="file" id="input-file-now" class="dropify" name="file_import[]" required multiple />
                    </div> <!--end row-->
                    <div class="row justify-content-center mt-3">
                        <button type="submit" class="btn btn-dark btn-round waves-effect waves-light" style="width: 250px"><i class="mdi mdi-check-all mr-2"></i>Upload</button>
                    </div>
                </form>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->
</div><!--end row-->
@endsection('content')