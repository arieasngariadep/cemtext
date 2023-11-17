@extends('layout.index')
@section('title', 'List User')
@section('titleTab', 'List User')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <?= $alert ?>
        <h5 class="mt-0">List User</h5>
        <div class="text-right mb-2">
          <a href="<?= route('formAddUser') ?>" class="btn btn-purple btn-round"><i class="dripicons-plus"></i> Add New</a>
        </div>
        <div class="table-responsive">
          <table id="datatable" class="table table-hover mb-0">
            <thead class="thead-default">
              <tr>
                <th class="text-center">No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Kelompok</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $no = 1;
                foreach($userList as $list){
                  $buttonData = "
                    <a target='_blank' href='".route('formUpdateUser', ['id' => $list->user_id])."' class='btn btn-warning btn-circle waves-effect waves-circle waves-float' data-toggle='tooltip' data-placement='top' title='Edit'>
                      <i class='mdi mdi-pencil-outline'></i>
                    </a> |
                    <a href='".route('deleteUser', ['id' => $list->user_id])."' class='btn btn-danger btn-circle waves-effect waves-circle waves-float' data-toggle='tooltip' data-placement='top' title='Delete'>
                      <i class='mdi mdi-trash-can-outline'></i>
                    </a>
                  ";
                  echo "
                    <tr>
                      <td class='text-center'>$no</td>
                      <td>$list->name</td>
                      <td>$list->email</td>
                      <td>$list->role_name</td>
                      <td>$list->kelompok</td>
                      <td class='text-center' width='15%'>$buttonData</td>
                    </tr>
                  ";
                  $no++;
                }
              ?>
            </tbody>
            <tfoot>
              <tr>
                <th class="text-center">No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Kelompok</th>
                <th>Action</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div> <!-- end col -->
</div> <!-- end row -->
@endsection('content')