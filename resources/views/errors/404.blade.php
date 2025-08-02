<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error 404</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> -->
    <!--  <link rel="stylesheet" id="picostrap-styles-css" href="https://cdn.livecanvas.com/media/css/library/bundle.css" media="all"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/livecanvas-team/ninjabootstrap/dist/css/bootstrap.min.css" media="all">

</head>

<body>


    <section class="bg-light">
        <div class="container-fluid">
            <div class="row row-cols-1 justify-content-center py-5">
                <div class="col-xxl-7 mb-4">
                    <div class="lc-block">
                        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                        <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_u1xuufn3.json" class="mx-auto" background="transparent" speed="1" loop="" autoplay=""></lottie-player>
                    </div><!-- /lc-block -->
                </div><!-- /col -->
                <div class="col text-center">
                    <div class="lc-block">
                        <!-- /lc-block -->
                        <div class="lc-block mb-4">
                            <div editable="rich">
                                <p class="rfs-11 fw-light"> La página que buscas fue movida, eliminada o tal vez nunca existió.</p>
                            </div>
                        </div><!-- /lc-block -->
                        <div class="lc-block">
                            @if (auth()->user())
                            <a class="btn btn-lg btn-primary" href="{{route('Inicio')}}" role="button">Volver al inicio</a>
                            @else
                            <a class="btn btn-lg btn-primary" href="{{route('home')}}" role="button">Volver al inicio</a>
                            @endif
                        </div><!-- /lc-block -->
                    </div><!-- /lc-block -->
                </div><!-- /col -->
            </div>

        </div>
    </section>




    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>
