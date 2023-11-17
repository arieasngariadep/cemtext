@extends('layout.index')
@section('title', 'Dashboard')
@section('titleTab', 'Dashboard')

@section('content')
<style>
    .carousel-inner .carousel-item {
        transition: -webkit-transform 2s ease;
        transition: transform 2s ease;
        transition: transform 2s ease,
        -webkit-transform 2s ease;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <?php if($alert): ?>
            <div class="card">
                <div class="card-body">
                    <?= $alert ?>
                </div>
            </div>
        <?php endif;?>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block w-100" src="{{asset('img/image-gallery/21.jpg')}}" alt="First slide">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="{{asset('img/image-gallery/22.jpg')}}" alt="Second slide">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="{{asset('img/image-gallery/23.jpg')}}" alt="Third slide">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection('content')
