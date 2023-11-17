@extends('layout.index')
@section('title', 'Upload File SSD')
@section('titleTab', 'Upload File SSD')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <?= $alert ?>
        <form action="<?= route('prosesUploadFile') ?>" method="POST" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-group">
            <input type="file" id="input-file-now" class="dropify" name="filename" required />
            <input type="text" class="form-control" name="userId" value="<?= Request::session()->get('userId') ?>" hidden>
          </div>
          <div class="button-items mt-3 text-center">
            <button type="submit" class="btn btn-blue btn-square waves-effect waves-light"><i class="dripicons-cloud-upload mr-2"></i>Upload</button>
          </div>
        </form>
      </div>
    </div>
  </div> <!-- end col -->
</div> <!-- end row -->
@endsection('content')