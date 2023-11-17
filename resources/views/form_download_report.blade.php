@extends('layout.index')
@section('title', 'Download Report')
@section('titleTab', 'Download Report')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">Download Report</h4>
                <form action="<?= route('formDownloadReport') ?>" method="GET" enctype="multipart/form-data">
                  @csrf
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label text-right">Jenis Cemtext</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="jenis_rekening">
                                <option value="">Select Cemtext Option</option>
                                <option value="0767" <?= ($jenis == '0767' ? 'selected' : '') ?>>Cemtext Jurnal</option>
                                <option value="0777" <?= ($jenis == '0777' ? 'selected' : '') ?>>Cemtext Payroll</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label text-right">Range Date</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" placeholder="Start date" id="tanggal" name="start_date" value="<?= $start_date ?>">
                            </div><!--end col-->
                            <div class="col-sm-3">
                                <input type="text" class="form-control" placeholder="End date" id="tanggal1" name="end_date" value="<?= $end_date ?>">
                            </div><!--end col-->
                    </div>
                  <?php 
                    if($start_date != NULL && $end_date != NULL){
                  ?>
                    <div class="form-group row">
                        <div class="col-sm-6" style="margin-left: 200px">
                            <label class="text-left">Jumlah Data</label>
                                <input type="text" name="jumlah_data" value="<?= $jumlah_data ?>" class="form-control" readonly>
                        </div>
                    </div>
                  <?php 
                    }
                  ?>
                    <div class="form-row">
                        <div class="col-md-5 mb-3 text-right">
                        <button type="submit" class="btn btn-primary waves-effect waves-light" style="width: 250px">Proses</button>
                        </div>                                          
                  
              </form>
              <?php 
                if($start_date != NULL && $end_date != NULL && $jumlah_data > 0){
              ?>
              <form action="<?= route('exportReport') ?>" method="POST" enctype="multipart/form-data">
                @csrf
                
                        <div class="col-md-5 text-left">
                            <input type="hidden" class="form-control datepicker" id="jenis_rekening" name="jenis_rekening" value="<?= $jenis ?>" style="width: 100%;">
                            <input type="hidden" class="form-control datepicker" id="start_date" name="start_date" value="<?= $start_date ?>" style="width: 100%;">
                            <input type="hidden" class="form-control datepicker" id="end_date" name="end_date" value="<?= $end_date ?>" style="width: 100%;">
                            <button type="submit" class="btn btn-success waves-effect waves-light" style="width: 250px"><i class="dripicons-cloud-download mr-2"></i>Download Report</button>
                        </div>
                    </div>
              </form>
              <?php 
                }
              ?>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection