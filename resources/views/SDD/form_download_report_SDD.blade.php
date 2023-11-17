@extends('layout.index')
@section('title', 'Upload File SSD')
@section('titleTab', 'Upload File SSD')

@section('content')
<div class="row">
    <div class="col-xl-12 justify-content-center">
        <div class="card">
            <div class="card-body col-xl-11 ml-5">
                <?= $alert ?>
                <h4 class="mt-0 header-title">Download Report SDD</h4>
                <div class="card-body">
                    <div class="outertable">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Summary</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($jumlah > 1) : ?>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>Page 1 (Data 1 - 350.000)</td>
                                    <td>
                                        <form target="_blank" action="<?= route('getDataExportSDDPage1') ?>" method="POST">
                                            @csrf
                                            <input type="text" class="form-control" name="userId" value="<?= Request::session()->get('userId') ?>" hidden>
                                            <button type="submit" class="btn btn-dark"><i class="dripicons-download"></i> Download</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if($jumlah > 350000) : ?>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td>Page 2 (Data 350.001 - 700.000)</td>
                                    <td>
                                        <form target="_blank" action="<?= route('getDataExportSDDPage2') ?>" method="POST">
                                            @csrf
                                            <input type="text" class="form-control" name="userId" value="<?= Request::session()->get('userId') ?>" hidden>
                                            <button type="submit" class="btn btn-dark"><i class="dripicons-download"></i> Download</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if($jumlah > 700000) : ?>
                                <tr>
                                    <td class="text-center">3</td>
                                    <td>Page 3 (Data 700.001 - 1.050.000)</td>
                                    <td>
                                        <form target="_blank" action="<?= route('getDataExportSDDPage3') ?>" method="POST">
                                            @csrf
                                            <input type="text" class="form-control" name="userId" value="<?= Request::session()->get('userId') ?>" hidden>
                                            <button type="submit" class="btn btn-dark"><i class="dripicons-download"></i> Download</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="text-center" colspan="2">Total Data</td>
                                    <td><?= number_format($jumlah, 0, ",", ".") ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->
</div><!--end row-->
@endsection('content')