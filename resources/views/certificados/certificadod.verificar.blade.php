@section('title', 'Verificar Certificado')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Certificado de Participación Verificado</h4>
                    </div>
                    <div class="card-body">
                        @if ($certificado)
                            <h5 class="text-success">¡Certificado válido!</h5>
                            <p><strong>Curso:</strong> {{ $certificado->curso->titulo }}</p>
                            <p><strong>Estudiante:</strong> {{ $certificado->inscrito->nombre }}</p>
                            <p><strong>Código del Certificado:</strong> {{ $certificado->codigo_certificado }}</p>
                            <p><strong>Fecha de Emisión:</strong> {{ $certificado->fecha_emision->format('d/m/Y') }}</p>

                            <div class="mt-4">
                                <h5>Detalles del Certificado:</h5>
                                <ul>
                                    <li><strong>Fecha de Finalización del Curso:</strong> {{ $certificado->curso->fecha_finalizacion->format('d/m/Y') }}</li>
                                    <li><strong>Certificado Emitido:</strong> Sí</li>
                                    <li><strong>Ruta del Certificado:</strong> <a href="{{ asset('storage/' . $certificado->ruta_certificado) }}" target="_blank">Ver Certificado PDF</a></li>
                                </ul>
                            </div>

                            <hr>
                            <p class="text-muted">Este certificado es autenticado mediante un código QR único. Escanee el código para verificar la validez del certificado.</p>
                        @else
                            <p class="text-danger">Este certificado no es válido. El código que has escaneado no corresponde a un certificado registrado.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@include('layout')
