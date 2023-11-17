<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layout.header')
    </head>

    <body>
        @include('layout.topbar')
        <div class="page-wrapper" id="page_content">

            <!-- Left Sidenav -->
            @include('layout.sidebar')
            <!-- end left-sidenav-->

            <!-- Page Content-->
            <div class="page-content">
                <div class="container-fluid">

                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="float-right">
                                    @yield('breadcrumbs')
                                </div>
                                <h4 class="page-title">@yield('title')</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>
                    <!-- end page title end breadcrumb -->

                    @yield('content')
                </div><!-- container -->
                
                @include('layout.footer')
            </div>
            <!-- end page content -->

        </div>
        <!-- end page-wrapper -->

        @include('layout.javascript')
        @include('ChangePasswordModal')

        <script>
            $(document).ready(function(){
                $("#jenis_rekening").on('click', function(){
                    if($("#Report_767").prop("selected")){
                        $("#CEMTEXT767").show();
                    }else{
                        $("#CEMTEXT767").hide();
                    }
                });
                $("#jenis_rekening").on('click', function(){
                    if($("#Report_777").prop("selected")){
                        $("#CEMTEXT777").show();
                    }else{
                        $("#CEMTEXT777").hide();
                    }
                });
            });

            $("#form-incoming").steps({
                headerTag: "h3",
                bodyTag: "fieldset",
                transitionEffect: "slide",
                autoFocus : true,
                labels: {
                    cancel: "Batalkan",
                    current: "current step:",
                    pagination: "Halaman",
                    next: "Berikutnya",
                    previous: "Sebelumnya",
                    loading: "Sedang Memuat Data ...",
                    finish : "Submit Data"
                },
                onFinishing : function(event){
                    //e.preventDefault();
                    var form = $("#submit");
                    Swal.fire({
                        title: 'Info',
                        text: "Apakah Anda Sudah Yakin Dengan Data Ini?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Iya',
                        cancelButtonText: 'Tidak'
                        }).then((result) => {
                        if (result.value) {
                            form.submit();
                        }
                    });
                }
            });
        </script>
    </body>
</html>