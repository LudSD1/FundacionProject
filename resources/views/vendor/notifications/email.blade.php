<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <style>
        /* Estilos para el encabezado */
        .navbar-main {
            background: rgb(26,71,137);
            background: linear-gradient(145deg, rgba(26,71,137,1) 40%, rgba(34,77,141,1) 53%, rgba(255,255,255,1) 53%);
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

        .btn-reset {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1a4789;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
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


    @if($esVerificacion)
    <h1>Verificación de Correo Electrónico</h1>
    <p>Hola,</p>
    <p>Gracias por registrarte. Para completar tu registro, verifica tu correo electrónico haciendo clic en el botón de abajo:</p>
    <p>
        <a href="{{ $actionUrl }}" class="btn">Verificar Correo</a>
    </p>
    <p>Si no creaste esta cuenta, ignora este mensaje.</p>
    <p>Gracias,</p>
    <p>El equipo de  Aprendo Hoy / Fundación Educar Para La Vida</p>
    @else
    <h1>Restablecer Contraseña</h1>
    <p>Hola,</p>
    <p>Recibiste este correo porque solicitaste un restablecimiento de contraseña para tu cuenta.</p>
    <p>
        <a href="{{ $actionUrl }}" class="btn">Restablecer Contraseña</a>
    </p>
    <p>Si no solicitaste este restablecimiento, ignora este mensaje.</p>
    <p>Gracias,</p>
    <p>El equipo de Aprendo Hoy / Fundación Educar Para La Vida</p>
@endif


</body>
</html>
  