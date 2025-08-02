<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Certificado</title>
  <link rel="stylesheet" href="{{ asset('assets/certificado/certificado.css') }}">

</head>
<body>
<div class="border p-3">
  <div class="main-container" id="container">
    <div class="certificate-container">
      <div class="certificate-header">
        <div style="width: 100%; height: auto;"></div>
        <img class="certificate-logo-f" src="{{asset('assets/img/logof.png')}}" alt="Logo F">
        <img class="certificate-logo" src="{{asset('assets/img/logoedin.png')}}" alt="Logo Edin">
      </div>

      <div class="certificate-content-container">
        <h1 class="certificate-title">CERTIFICADO</h1>
        <div class="certificate-content">
          <p class="certificate-text">Se otorga el presente certificado a:</p>
          <h2 class="certificate-nombre">{{$inscritos->estudiantes->name}} {{$inscritos->estudiantes->lastname1}} {{$inscritos->estudiantes->lastname2}}</h2>
          <h4 class="certificate-text">
            Por haber completado con éxito el curso de {{$inscritos->cursos->nombreCurso}}.
            Este certificado se otorga en reconocimiento a su dedicación y logros al haber obtenido como promedio final: {{$boletin->nota_final}}.
            En un periodo de tiempo de {{$inscritos->cursos->fecha_ini}} a {{$inscritos->cursos->fecha_fin}}.
          </h4>

          <p class="certificate-text" style="text-align: right;">
            Bolivia, {{$boletin->created_at->day}} de {{$boletin->created_at->locale('es')->format('F')}} del {{$boletin->created_at->year}}
          </p>
        </div>

        <div class="certificate-signature">
        <img class="firma" src="{{asset('assets/img/firma digital.png')}}" alt="Firma Digital">
          <p>Mba. Roxana Araujo Romay</p>
          <p>Directora Ejecutiva</p>
        </div>
        <br>
      </div>
    </div>
  </div>

  <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Función para generar el PDF
        function generatePdf() {
            var element = document.getElementById('container');

            html2pdf(element, {
                filename: 'certificado.pdf',
                jsPDF: { orientation: 'landscape' },
            }).then(function (pdf) {
                console.log('PDF generado correctamente:', pdf);
            }).catch(function (error) {
                console.error('Error al generar el PDF:', error);
            });
        }

        // Obtén el enlace por su ID
        var generatePdfLink = document.getElementById('generatePdfLink');

        // Agrega un evento de clic al enlace que llame a la función generatePdf
        generatePdfLink.addEventListener('click', function (event) {
            event.preventDefault(); // Evita el comportamiento predeterminado del enlace
            generatePdf();
        });
    });
</script>

</body>
</html>
