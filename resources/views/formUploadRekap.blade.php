@extends('layout.index')
@section('title', 'Upload File Rekap')
@section('titleTab', 'Upload File Rekap')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <?= $alert ?>
        <h4 class="mt-0 header-title">File Upload Rekap</h4>
        <form action="<?= route('prosesImportRekap') ?>" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="file" name="file_import" id="input-file-now" class="dropify" />
            </div>
            <div class="button-items mt-3 text-center">
                <button type="submit" class="btn btn-blue btn-square waves-effect waves-light"><i class="dripicons-cloud-upload mr-2"></i>Upload</button>
            </div>
        </form>
      </div>
    </div>
  </div> <!-- end col -->
</div> <!-- end row -->
@endsection
