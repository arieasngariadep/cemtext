<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myLargeModalLabel">Form Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="general-label">
                            <form action="<?= route('prosesChangePassword') ?>" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group row">
                                    <label for="password" class="col-sm-2 col-form-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                    </div>
                                </div>
                                    
                                <div class="form-group row">
                                    <label for="confirm_password" class="col-sm-2 col-form-label">Confirm Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                                    </div>
                                </div>

                                <div class="form-group row" hidden>
                                    <label for="userId" class="col-sm-2 col-form-label">User ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="userId" name="userId" value="<?= Session::get('userId'); ?>" placeholder="User ID">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-10 ml-auto">
                                        <button type="submit" class="btn btn-gradient-primary">Submit</button>
                                    </div>
                                </div> 
                            </form>           
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->