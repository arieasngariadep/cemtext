@extends('layout.index')
@section('title', 'Upload File Cemtext')
@section('titleTab', 'Upload File Cemtext')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <?= $alert ?>
        <div class="form-group row">
          <label class="col-md-2 my-1 control-label ml-3"> Pilih Jenis CemText :</label>
          <div class="col-md-4 ml">
            <div class="form-group">
              <select class="form-control" name="jenis_rekening" id="jenis_rekening">
                <option value="">Please Select Option</option>
                <option value="CEMTEXT767" id="Report_767">Cemtext Jurnal</option>
                <option value="CEMTEXT777" id="Report_777">Cemtext Payroll</option>
              </select>
            </div>
          </div>
        </div>
        <div id="CEMTEXT767" style="display: none;">
          <form action="<?= route('prosesUploadCemtext767') ?>" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="file" name="file_import" id="input-file-now" class="dropify" />
            <div class="button-items mt-3 text-center">
              <button type="submit" class="btn btn-blue btn-square waves-effect waves-light"><i class="dripicons-cloud-upload mr-2"></i>Upload</button>
            </div>
          </form>
        </div>

        <div id="CEMTEXT777" style="display: none;">
          <form action="<?= route('prosesUploadCemtext777') ?>" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="file" name="file_import" id="input-file-now" class="dropify" />
            <div class="button-items mt-3 text-center">
              <button type="submit" class="btn btn-blue btn-square waves-effect waves-light"><i class="dripicons-cloud-upload mr-2"></i>Upload</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div> <!-- end col -->
</div> <!-- end row -->
@endsection
