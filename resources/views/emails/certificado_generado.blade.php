<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación</title>
    <style>
        /* Estilos para el encabezado */
        .navbar-main {
            background: rgb(26, 71, 137);
            background: linear-gradient(145deg, rgba(26, 71, 137, 1) 40%, rgba(34, 77, 141, 1) 53%, rgba(255, 255, 255, 1) 53%);
            height: 140px;
            width: 100%;
            border: none;
            border-radius: 0;
            position: relative;
            overflow: hidden;
        }

        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            width: 100%;
        }

        .navbar-brand {
            height: 100%;
            width: auto;
            display: flex;
            align-items: center;
        }

        /* Estilos para el cuerpo del correo */
        .email-content {
            padding: 20px;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .email-content h1 {
            color: #1a4789;
        }

        .email-content p {
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <!-- Encabezado -->
    <div class="navbar-main">
        <div class="navbar-container">
            <a class="navbar-brand logo-izquierdo" href="{{ route('Inicio') }}">
                <img src="{{ asset('../assets/img/logof.png') }}" style="width: auto; height: 80px;">
            </a>
            <a class="navbar-brand logo-derecho" href="{{ route('Inicio') }}">
                <img src="{{ asset('../assets/img/Acceder.png') }}" style="width: auto; height: 50px;">
            </a>
        </div>
    </div>

    <!-- Contenido del correo -->
    <div class="email-content">
        <h1>¡Felicidades, {{ $inscrito->estudiantes->name }} {{ $inscrito->estudiantes->lastname1 }} {{ $inscrito->estudiantes->lastname2 }}!</h1>
        <p>Has completado exitosamente el {{$inscrito->cursos->tipo}}: <strong>{{ $inscrito->cursos->nombreCurso }}</strong>.</p>
        <p>Puedes verificar tu certificado haciendo clic en el siguiente enlace:</p>
        <a href="{{ $url }}">Verificar Certificado</a>
        <p>Gracias por participar en nuestro {{$inscrito->cursos->tipo}}.</p>
    </div>
</body>

</html>
