@extends('layout.index')
@section('title', 'Form Update User')
@section('titleTab', 'Form Update User')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h5 class="mt-0">Update User</h5>
        <?= $alert ?>
        <form id="form_update_user" action="<?= route('prosesUpdateUser') ?>" method="POST">
          {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-12 col-lg-6">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-sm-3 col-form-label">Full Name</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="fname" value="<?= $user->name ?>" required>
                        </div>
                    </div>
                    <div class="form-group row" hidden>
                        <label for="example-password-input" class="col-sm-3 col-form-label">Old Password</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="password" id="oldPassword" name="oldPassword">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-password-input" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="password" id="password" name="password">
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-3 col-form-label">Role</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="roleId">
                                <option>Silahkan Pilih Role</option>
                                <?php
                                foreach ($roleList as $list) {
                                    if($user->role_id == $list->role_id){
                                        $sel = 'selected';
                                    }else{
                                        $sel = '';
                                    }
                                    echo "<option value='$list->role_id' $sel>$list->role_name</option>";
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
                            <input class="form-control" type="email" name="email" value="<?= $user->email ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-password-input" class="col-sm-3 col-form-label">Confirm Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control equalTo" id="cpassword" name="cpassword" required>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-3 col-form-label">Kelompok</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="kelompokId">
                                <option>Silahkan Pilih Kelompok</option>
                                <?php
                                foreach ($kelompokList as $kelompok) {
                                    if($user->kelompok_id == $kelompok->id){
                                        $sel = 'selected';
                                    }else{
                                        $sel = '';
                                    }
                                    echo "<option value='$kelompok->id' $sel>$kelompok->kelompok</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row text-center" style="margin-left: 200px">
                <div class="ml-3">
                    <button type="submit" class="btn btn-primary waves-effect waves-light" style="width: 200px">Submit</button>
                </div>
                <div class="ml-3">
                    <input type="reset" class="btn btn-dark waves-effect waves-light" style="width: 200px">
                </div>
                <div class="ml-3">
                    <input type="hidden" class="form-control" value="<?= Request::session()->get('userId'); ?>" name="user_id" />
                    <input type="hidden" class="form-control" value="<?= Request::session()->get('username'); ?>" name="name" />
                    <input type="hidden" class="form-control" value="<?= Request::session()->get('oldPassword'); ?>" name="oldPassword" />
                    <input type="hidden" class="form-control" value="<?= $user->user_id ?>" name="id" />
                    <a type="button" href="<?= route('getAllUser') ?>" class="btn btn-danger waves-effect waves-light" style="width: 200px">Back</a>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div> <!-- end col -->
</div> <!-- end row -->
@endsection