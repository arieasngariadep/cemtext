@extends('layout.index')
@section('title', 'Form Add New User')
@section('titleTab', 'Form Add New User')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h5 class="mt-0">Add User</h5>
        <?= $alert ?>
        <form id="form_add_user" action="<?= route('prosesAddUser') ?>" method="POST">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-sm-12 col-lg-6">
              <div class="form-group row">
                <label for="example-text-input" class="col-sm-3 col-form-label">Full Name</label>
                <div class="col-sm-8">
                  <input class="form-control" type="text" name="fname" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="example-password-input" class="col-sm-3 col-form-label">Password</label>
                <div class="col-sm-8">
                  <input class="form-control" type="password" id="password" name="password" required>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Role</label>
                <div class="col-sm-8">
                  <select class="form-control" name="roleId">
                    <option>Silahkan Pilih Role</option>
                    <?php
                      foreach ($roleList as $list) {
                        echo "<option value='$list->role_id'>$list->role_name</option>";
                      }
                    ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-sm-12 col-lg-6">
              <div class="form-group row">
                <label for="example-email-input" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-8">
                  <input class="form-control" type="email" name="email" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="example-password-input" class="col-sm-3 col-form-label">Confirm Password</label>
                <div class="col-sm-8">
                  <input type="password" class="form-control equalTo" id="cpassword" name="cpassword" required>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kelompok</label>
                <div class="col-sm-8">
                  <select class="form-control" name="kelompokId">
                    <option>Silahkan Pilih Kelompok</option>
                    <?php
                      foreach ($kelompokList as $kelompok) {
                        echo "<option value='$kelompok->id'>$kelompok->kelompok</option>";
                      }
                    ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="col-md-6 mb-3 text-right">
              <input type="hidden" class="form-control" value="<?= Request::session()->get('username'); ?>" name="name" />
              <button type="submit" class="btn btn-primary waves-effect waves-light" style="width: 200px">Submit</button>
            </div>
            <div class="col-md-6 mb-3">
              <input type="reset" class="btn btn-dark waves-effect waves-light" style="width: 200px">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div> <!-- end col -->
</div> <!-- end row -->
@endsection