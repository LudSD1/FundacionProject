<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>Certificado de Participación</title>
    <style>
        @page {
            size: 11in 8.5in;
            margin: 0;
        }

        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

        body {

            text-align: center;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .container {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
        }

        .nombre {
            font-family: "Bebas Neue", serif;
            font-weight: 400;
            font-size: 102px;
            font-style: normal;
            color: #2980B9;
            padding-top: 28%;
            position: relative;
        }

        .qr-container {
            position: absolute;
            bottom: 50px;
            left: 10px;

        }

        .qr-code {
            width: 250px;
            height: auto;
        }

        .qr-code svg {
            width: 200px !important;
            height: 200px !important;
            max-width: 100%;
            display: block;
            margin: 0 auto;
        }

        .codigo {
            position: fixed;
            /* o absolute, dependiendo de tu necesidad */
            bottom: 25px;
            left: 0;
            /* O puedes usar 'right: 0;' dependiendo de donde quieras que esté */
            font-size: 28px;
            color: rgb(0, 0, 0);
            width: 100%;
            /* Para que abarque todo el ancho del contenedor */
            text-align: center;
            /* O 'left' o 'right', según tu preferencia */
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    <!-- Primera página (Frontal) -->
    <div class="container">
        <div class="background" style="background-image: url('{{ storage_path('app/public/' . $plantillaf) }}');"></div>
        <p class="nombre"
            style="
        color: {{ $primary_color ?? '#2980B9' }};
        font-family: '{{ $font_family ?? 'Bebas Neue' }}', sans-serif;
        font-size: {{ $font_size ?? 102 }}px;
    ">
            {{ $inscrito->estudiantes->name }} {{ $inscrito->estudiantes->lastname1 }}
            {{ $inscrito->estudiantes->lastname2 }}
        </p>


        <div class="qr-container">
            <div class="qr-code">
                @if(isset($qrCode))
                    <!-- Usar QR generado con el código del certificado -->
                    <img src="{{ $qrCode }}" alt="Código QR de verificación"
                        style="width: 200px; height: 200px; display: block; margin: 0 auto;">
                @elseif(isset($qr_url))
                    <!-- Fallback a URL del QR -->
                    <img src="{{ $qr_url }}" alt="Código QR de verificación"
                        style="width: 200px; height: 200px; display: block; margin: 0 auto;">
                @elseif(isset($qr_base64))
                    <!-- Fallback a SVG base64 -->
                    <img src="{{ $qr_base64 }}" alt="Código QR de verificación"
                        style="width: 200px; height: 200px; display: block; margin: 0 auto;">
                @elseif(isset($qr_svg))
                    <!-- Fallback a SVG directo -->
                    {!! $qr_svg !!}
                @else
                    <!-- Fallback si no hay QR disponible -->
                    <div style="width: 200px; height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border: 1px solid #ccc;">
                        <span style="color: #666; font-size: 12px;">QR no disponible</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Segunda página (Reverso) -->
    <div class="container">
        <div class="background" style="background-image: url('{{ storage_path('app/public/' . $plantillab) }}');">
            <p class="codigo">
                Código de Certificado: {{ $codigo_certificado }}
            </p>
        </div>



    </div>


</body>

</html>
