@extends('layout')

@section('titulo')
    Importar Usuarios
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-modern">
                <div class="card-header-modern">
                    <h4 class="mb-0">Importar Usuarios para Congreso</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}

                            @if(session('results'))
                                <hr>
                                <p><strong>Resultados de la importación:</strong></p>
                                <ul>
                                    <li>Total de registros procesados: {{ session('results')['total'] }}</li>
                                    <li>Usuarios nuevos creados: {{ session('results')['created'] }}</li>
                                    <li>Usuarios existentes: {{ session('results')['existing'] }}</li>
                                    <li>Inscripciones realizadas: {{ session('results')['registered'] }}</li>
                                </ul>

                                @if(count(session('results')['errors']) > 0)
                                    <p><strong>Errores:</strong></p>
                                    <ul class="text-danger">
                                        @foreach(session('results')['errors'] as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('import.users') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <h5>Formato del archivo Excel</h5>
                            <p>El archivo debe contener las siguientes columnas:</p>
                            <ul>
                                <li><strong>nombres</strong>: Nombres del participante</li>
                                <li><strong>apellidos</strong>: Apellidos del participante</li>
                                <li><strong>correo_electronico</strong>: Email del participante</li>
                                <li><strong>telefono</strong>: Número de teléfono (con código de país)</li>
                                <li><strong>pais_de_residencia</strong>: País de residencia</li>
                            </ul>
                        </div>

                        <div class="form-group-modern">
                            <label for="congreso_id" class="form-label-modern">Seleccionar Congreso</label>
                            <select name="congreso_id" id="congreso_id" class="form-select-modern" required>
                                <option value="">Seleccione un congreso</option>
                                @foreach($congresos as $congreso)
                                    <option value="{{ $congreso->id }}">{{ $congreso->nombreCurso }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group-modern">
                            <label for="excel_file" class="form-label-modern">Archivo Excel</label>
                            <input type="file" name="excel_file" id="excel_file" class="form-control-modern" required>
                            <p class="helper-text-modern">Formatos aceptados: xlsx, xls, csv</p>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn-modern btn-submit">
                                <i class="fas fa-file-upload me-1"></i> Importar Usuarios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
