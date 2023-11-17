<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <title>Login | CEMTEXT</title>
  <!-- Favicon-->
  <link rel="icon" href="{{ asset('img/icon.png') }}" type="image/x-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

  <!-- Bootstrap Core Css -->
  <link href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

  <!-- Waves Effect Css -->
  <link href="{{ asset('plugins/node-waves/waves.css') }}" rel="stylesheet" />

  <!-- Animation Css -->
  <link href="{{ asset('plugins/animate-css/animate.css') }}" rel="stylesheet" />

  <!-- App css -->
    <link href="<?= asset('assets/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/css/icons.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/css/metisMenu.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/css/style.css') ?>" rel="stylesheet" type="text/css" />
  <!-- Custom Css -->
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>

<body class="account-body accountbg">
    <!-- Log In page -->
    <div class="row vh-100 ">
        <div class="col-12 align-self-center">
            <div class="auth-page">
                <div class="auth-card">
                    <div class="card-body">
                        <div class="px-7">
                            <div class="text-center auth-logo-text">
                                <h1 class="mt-0 mb-3 mt-5 text-dark">SIGN IN</h1>
                                <p class="text-muted mb-3">Sign in to continue to CemText.</p>
                            </div> <!--end auth-logo-text-->
                            <form class="needs-validation" action="{{ route('loginProcess') }}" method="POST">
                            {{ csrf_field() }}
                                <div class="msg mb-2" style="color: #1EAE98;">Sign in to start your session</div>
                                <?= $alert ?>
                                <div class="input-group  mb-3">
                                    <span class="input-group-addon">
                                        <i class="material-icons" style="color: #fc5404;">person</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="email" placeholder="Email" required autofocus style="width: 300px">
                                    </div>
                                </div>
                                <div class="input-group  mb-3">
                                    <span class="input-group-addon">
                                        <i class="material-icons" style="color: #fc5404;">lock</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="password" placeholder="Password" required style="width: 300px">
                                    </div>
                                </div>
                                <div class="form-group mb-3 row">
                                    <div class="col-12 mt-2 text-center">
                                        <button class="btn btn-purple waves-effect waves-light" type="submit" style="color: white; width: 200px;">Log In <i class="fas fa-sign-in-alt ml-1"></i></button>
                                    </div>
                                </div>
                            </form>
                            <div class="col-12 mt-5">
                                <div class="text-center">
                                    <p>Divisi Operasional Digital - 2021 &nbsp;</p>
                                </div>
                            </div>
                        </div><!--end /div-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end auth-page-->
        </div><!--end col-->


    </div><!--end row-->
    <!-- End Log In page -->

   <!-- jQuery  -->
   <script src="<?= asset('assets/js/jquery.min.js') ?>"></script>
   <script src="<?= asset('assets/js/bootstrap.bundle.min.js') ?>"></script>
   <script src="<?= asset('assets/js/metisMenu.min.js') ?>"></script>
   <script src="<?= asset('assets/js/waves.min.js') ?>"></script>
   <script src="<?= asset('assets/js/jquery.slimscroll.min.js') ?>"></script>

   <!-- App js -->
   <script src="<?= asset('assets/js/app.js') ?>"></script>

</body>
</html>
