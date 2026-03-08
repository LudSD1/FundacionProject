@extends('layout')

@section('titulo', 'Editar Curso')

@section('content')

    <div class="ec-wrapper">
    <div class="container" style="max-width:880px">

        <div class="ec-header">
            <a href="{{ route('Curso', $cursos->codigoCurso) }}" class="ec-back">
                <i class="bi bi-arrow-left"></i> Volver al Curso
            </a>
            <div class="text-end">
                <div class="ec-course-title">Editando: <span>{{ ucfirst(strtolower($cursos->nombreCurso)) }}</span></div>
                <div class="mt-1"><span class="ec-role-badge"><i class="bi bi-shield-fill"></i> {{ auth()->user()->getRoleNames()->first() }}</span></div>
            </div>
        </div>

        <div class="ec-progress"><div class="ec-progress-bar" id="ecProgressBar" style="width:20%"></div></div>

        <div class="ec-steps">
            <div class="ec-step active" onclick="goStep(1)" id="step-btn-1">
                <div class="ec-step-num" id="step-num-1">1</div>
                <div><span class="ec-step-label">Información</span><span class="ec-step-sub">Nombre y descripción</span></div>
            </div>
            <div class="ec-step" onclick="goStep(2)" id="step-btn-2">
                <div class="ec-step-num" id="step-num-2">2</div>
                <div><span class="ec-step-label">Configuración</span><span class="ec-step-sub">Fechas y formato</span></div>
            </div>
            @if(auth()->user()->hasRole('Administrador'))
            <div class="ec-step" onclick="goStep(3)" id="step-btn-3">
                <div class="ec-step-num" id="step-num-3">3</div>
                <div><span class="ec-step-label">Detalles</span><span class="ec-step-sub">Cupos y precio</span></div>
            </div>
            @endif
            <div class="ec-step" onclick="goStep(4)" id="step-btn-4">
                <div class="ec-step-num" id="step-num-4">{{ auth()->user()->hasRole('Administrador') ? 4 : 3 }}</div>
                <div><span class="ec-step-label">Archivos</span><span class="ec-step-sub">PDF e imagen</span></div>
            </div>
            <div class="ec-step" onclick="goStep(5)" id="step-btn-5">
                <div class="ec-step-num" id="step-num-5">{{ auth()->user()->hasRole('Administrador') ? 5 : 4 }}</div>
                <div><span class="ec-step-label">Categorías</span><span class="ec-step-sub">Clasificación</span></div>
            </div>
        </div>

        @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded',()=>Swal.fire({icon:'success',title:'Guardado',text:'{{ session('success') }}',confirmButtonColor:'#1a4789',timer:3000}))</script>
        @endif
        @if($errors->any())
        <script>document.addEventListener('DOMContentLoaded',()=>Swal.fire({icon:'error',title:'Errores',html:'<ul style="text-align:left;color:#dc3545">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>',confirmButtonColor:'#1a4789'}))</script>
        @endif

        <form action="{{ route('editarCursoPost', $cursos->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- PASO 1: Información --}}
            <div class="ec-panel active" id="panel-1">
                <div class="ec-card">
                    <div class="ec-card-header">
                        <div class="ec-card-header-icon"><i class="bi bi-info-circle-fill"></i></div>
                        <div><h6>Información Básica</h6><p>Nombre, descripción y público objetivo</p></div>
                    </div>
                    <div class="ec-card-body">
                        <div class="ec-field">
                            <label class="ec-label"><i class="bi bi-book-fill"></i> Nombre del Curso <span class="req">*</span></label>
                            @if(auth()->user()->hasRole('Administrador'))
                                <input type="text" name="nombre" class="ec-input" value="{{ old('nombre', $cursos->nombreCurso) }}" placeholder="Ej. Programación Web Avanzada" required>
                            @else
                                <input type="hidden" name="nombre" value="{{ $cursos->nombreCurso }}">
                                <input type="text" class="ec-input" value="{{ $cursos->nombreCurso }}" disabled>
                            @endif
                        </div>
                        <div class="ec-field">
                            <label class="ec-label"><i class="bi bi-card-text"></i> Descripción <span class="req">*</span></label>
                            <textarea name="descripcion" id="descripcionTA" class="ec-textarea" rows="4" maxlength="500" required placeholder="Describe el contenido y objetivos...">{{ old('descripcion', $cursos->descripcionC) }}</textarea>
                            <div class="ec-char-count" id="charCount">0/500 caracteres</div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="ec-field mb-0">
                                    <label class="ec-label"><i class="bi bi-people-fill"></i> Edad Dirigida</label>
                                    <input type="text" name="edad_id" class="ec-input" value="{{ old('edad_id', $cursos->edad_dirigida) }}" placeholder="Ej: 18-30 años">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="ec-field mb-0">
                                    <label class="ec-label"><i class="bi bi-bar-chart-fill"></i> Nivel</label>
                                    <input type="text" name="nivel_id" class="ec-input" value="{{ old('nivel_id', $cursos->nivel) }}" placeholder="Ej: Básico, Intermedio">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ec-nav-btns">
                    <span></span>
                    <button type="button" class="ec-btn ec-btn-next" onclick="goStep(2)">Siguiente <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>

            {{-- PASO 2: Configuración --}}
            <div class="ec-panel" id="panel-2">
                <div class="ec-card">
                    <div class="ec-card-header">
                        <div class="ec-card-header-icon"><i class="bi bi-gear-fill"></i></div>
                        <div><h6>Configuración del Curso</h6><p>Fechas, formato y tipo</p></div>
                    </div>
                    <div class="ec-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="ec-field">
                                    <label class="ec-label"><i class="bi bi-calendar-check-fill"></i> Fecha de Inicio <span class="req">*</span></label>
                                    <input type="datetime-local" name="fecha_ini" class="ec-input" required
                                        value="{{ old('fecha_ini', $cursos->fecha_ini ? \Carbon\Carbon::parse($cursos->fecha_ini)->format('Y-m-d\TH:i') : '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="ec-field">
                                    <label class="ec-label"><i class="bi bi-calendar-x-fill"></i> Fecha de Fin <span class="req">*</span></label>
                                    <input type="datetime-local" name="fecha_fin" class="ec-input" required
                                        value="{{ old('fecha_fin', $cursos->fecha_fin ? \Carbon\Carbon::parse($cursos->fecha_fin)->format('Y-m-d\TH:i') : '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="ec-field">
                            <label class="ec-label"><i class="bi bi-display-fill"></i> Formato</label>
                            @if(auth()->user()->hasRole('Administrador'))
                                <div class="ec-pill-group">
                                    @foreach(['Presencial','Virtual','Híbrido'] as $fmt)
                                    <div class="ec-pill">
                                        <input type="radio" name="formato" id="fmt_{{ $fmt }}" value="{{ $fmt }}" {{ $cursos->formato==$fmt?'checked':'' }}>
                                        <label for="fmt_{{ $fmt }}">{{ $fmt }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <input type="hidden" name="formato" value="{{ $cursos->formato }}">
                                <input type="text" class="ec-input" value="{{ $cursos->formato }}" disabled>
                            @endif
                        </div>
                        <div class="ec-field mb-0">
                            <label class="ec-label"><i class="bi bi-tags-fill"></i> Tipo</label>
                            @if(auth()->user()->hasRole('Administrador'))
                                <div class="ec-pill-group">
                                    <div class="ec-pill">
                                        <input type="radio" name="tipo" id="tipo_curso" value="curso" {{ $cursos->tipo=='curso'?'checked':'' }}>
                                        <label for="tipo_curso">🎓 Curso</label>
                                    </div>
                                    <div class="ec-pill">
                                        <input type="radio" name="tipo" id="tipo_congreso" value="congreso" {{ $cursos->tipo=='congreso'?'checked':'' }}>
                                        <label for="tipo_congreso">📅 Evento / Congreso</label>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="tipo" value="{{ $cursos->tipo }}">
                                <input type="text" class="ec-input" value="{{ $cursos->tipo=='congreso'?'Evento':'Curso' }}" disabled>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="ec-nav-btns">
                    <button type="button" class="ec-btn ec-btn-prev" onclick="goStep(1)"><i class="bi bi-arrow-left"></i> Anterior</button>
                    <button type="button" class="ec-btn ec-btn-next" onclick="goStep({{ auth()->user()->hasRole('Administrador') ? 3 : 4 }})">Siguiente <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>

            {{-- PASO 3: Admin --}}
            @if(auth()->user()->hasRole('Administrador'))
            <div class="ec-panel" id="panel-3">
                <div class="ec-card">
                    <div class="ec-card-header">
                        <div class="ec-card-header-icon"><i class="bi bi-sliders"></i></div>
                        <div><h6>Detalles Administrativos</h6><p>Docente, cupos, precio y visibilidad</p></div>
                    </div>
                    <div class="ec-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="ec-field">
                                    <label class="ec-label"><i class="bi bi-person-badge-fill"></i> Docente <span class="req">*</span></label>
                                    <select class="ec-select" name="docente_id">
                                        @foreach($docente as $doc)
                                            <option value="{{ $doc->id }}" {{ $cursos->docente_id==$doc->id?'selected':'' }}>{{ $doc->name }} {{ $doc->lastname1 }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="ec-field">
                                    <label class="ec-label"><i class="bi bi-eye-fill"></i> Visibilidad</label>
                                    <div class="ec-pill-group">
                                        <div class="ec-pill">
                                            <input type="radio" name="visibilidad" id="vis_pub" value="publico" {{ $cursos->visibilidad=='publico'?'checked':'' }}>
                                            <label for="vis_pub">🌐 Público</label>
                                        </div>
                                        <div class="ec-pill">
                                            <input type="radio" name="visibilidad" id="vis_priv" value="privado" {{ $cursos->visibilidad=='privado'?'checked':'' }}>
                                            <label for="vis_priv">🔒 Privado</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ec-field mb-0">
                                    <label class="ec-label"><i class="bi bi-clock-fill"></i> Duración (horas) <span class="req">*</span></label>
                                    <input type="number" name="duracion" class="ec-input" value="{{ old('duracion', $cursos->duracion) }}" min="1" required placeholder="40">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ec-field mb-0">
                                    <label class="ec-label"><i class="bi bi-people-fill"></i> Cupos <span class="req">*</span></label>
                                    <input type="number" name="cupos" class="ec-input" value="{{ old('cupos', $cursos->cupos) }}" min="1" required placeholder="30">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ec-field mb-0">
                                    <label class="ec-label"><i class="bi bi-currency-dollar"></i> Precio (Bs) <span class="req">*</span></label>
                                    <input type="number" name="precio" class="ec-input" value="{{ old('precio', $cursos->precio) }}" step="0.01" min="0" required placeholder="250.00">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ec-nav-btns">
                    <button type="button" class="ec-btn ec-btn-prev" onclick="goStep(2)"><i class="bi bi-arrow-left"></i> Anterior</button>
                    <button type="button" class="ec-btn ec-btn-next" onclick="goStep(4)">Siguiente <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>
            @else
                <input type="hidden" name="docente_id" value="{{ auth()->user()->id }}">
            @endif

            {{-- PASO 4: Archivos --}}
            <div class="ec-panel" id="panel-4">
                <div class="ec-card">
                    <div class="ec-card-header">
                        <div class="ec-card-header-icon"><i class="bi bi-folder-fill"></i></div>
                        <div><h6>Archivos y Recursos</h6><p>PDF del curso e imagen de portada</p></div>
                    </div>
                    <div class="ec-card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="ec-label"><i class="bi bi-file-earmark-pdf-fill"></i> Archivo PDF</label>
                                <div class="ec-file-wrap">
                                    <input type="file" name="archivo" accept=".pdf" id="archivoInput">
                                    <div class="ec-file-icon"><i class="bi bi-file-earmark-pdf"></i></div>
                                    <div class="ec-file-text"><strong>Haz clic</strong> o arrastra tu PDF<br><span style="font-size:.72rem">Solo archivos .pdf</span></div>
                                </div>
                                @if($cursos->archivoContenidodelCurso)
                                    <div class="ec-file-preview">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size:1.8rem;flex-shrink:0"></i>
                                        <div>
                                            <div class="ec-file-sub">Archivo actual</div>
                                            <a href="{{ asset('storage/'.$cursos->archivoContenidodelCurso) }}" target="_blank" class="ec-file-name">{{ basename($cursos->archivoContenidodelCurso) }}</a>
                                        </div>
                                    </div>
                                    <div class="ec-del-check">
                                        <input type="checkbox" name="eliminar_archivo" id="eliminar_archivo">
                                        <label for="eliminar_archivo"><i class="bi bi-trash-fill me-1"></i>Eliminar archivo actual</label>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="ec-label"><i class="bi bi-image-fill"></i> Imagen de Portada</label>
                                <div class="ec-file-wrap">
                                    <input type="file" name="imagen" accept="image/*" id="imagenInput">
                                    <div class="ec-file-icon"><i class="bi bi-image"></i></div>
                                    <div class="ec-file-text"><strong>Haz clic</strong> o arrastra tu imagen<br><span style="font-size:.72rem">JPG, PNG, WEBP</span></div>
                                </div>
                                @if($cursos->imagen)
                                    <div class="ec-file-preview">
                                        <img src="{{ asset('storage/'.$cursos->imagen) }}" alt="Portada">
                                        <div><div class="ec-file-sub">Imagen actual</div><div class="ec-file-name">Portada del curso</div></div>
                                    </div>
                                    <div class="ec-del-check">
                                        <input type="checkbox" name="eliminar_imagen" id="eliminar_imagen">
                                        <label for="eliminar_imagen"><i class="bi bi-trash-fill me-1"></i>Eliminar imagen actual</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ec-nav-btns">
                    <button type="button" class="ec-btn ec-btn-prev" onclick="goStep({{ auth()->user()->hasRole('Administrador') ? 3 : 2 }})"><i class="bi bi-arrow-left"></i> Anterior</button>
                    <button type="button" class="ec-btn ec-btn-next" onclick="goStep(5)">Siguiente <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>

            {{-- PASO 5: Categorías --}}
            <div class="ec-panel" id="panel-5">
                <div class="ec-card">
                    <div class="ec-card-header">
                        <div class="ec-card-header-icon"><i class="bi bi-tag-fill"></i></div>
                        <div><h6>Categorías del Curso</h6><p>Selecciona las categorías que aplican</p></div>
                    </div>
                    <div class="ec-card-body">
                        <div class="search-box-table mb-3">
                            <i class="bi bi-search search-icon-table"></i>
                            <input type="text" class="form-control search-input-table" id="buscarCat" placeholder="Buscar categoría...">
                        </div>
                        <div class="ec-cat-grid" id="catGrid">
                            @foreach($categorias as $categoria)
                                <div class="ec-cat-item {{ $cursos->categorias->contains($categoria->id) ? 'checked' : '' }}" onclick="toggleCat(this)">
                                    <input type="checkbox" name="categorias[]" value="{{ $categoria->id }}" {{ $cursos->categorias->contains($categoria->id)?'checked':'' }}>
                                    <div class="ec-cat-check"><i class="bi bi-check"></i></div>
                                    <span class="ec-cat-name">{{ $categoria->name }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-2" style="font-size:.78rem;color:#94a3b8">
                            <i class="bi bi-check2-circle me-1" style="color:var(--color-success)"></i>
                            <span id="catCount">{{ $cursos->categorias->count() }}</span> categoría(s) seleccionada(s)
                        </div>
                    </div>
                </div>
                <div class="ec-nav-btns">
                    <button type="button" class="ec-btn ec-btn-prev" onclick="goStep(4)"><i class="bi bi-arrow-left"></i> Anterior</button>
                    <button type="submit" class="ec-btn ec-btn-save"><i class="bi bi-save2-fill"></i> Guardar Cambios</button>
                </div>
            </div>

        </form>
    </div>
    </div>

    <script>
    const TOTAL = {{ auth()->user()->hasRole('Administrador') ? 5 : 4 }};
    let cur = 1;
    function goStep(n){
        const p=cur;
        document.getElementById('panel-'+p)?.classList.remove('active');
        document.getElementById('step-btn-'+p)?.classList.remove('active');
        if(n>p){
            document.getElementById('step-btn-'+p)?.classList.add('done');
            document.getElementById('step-num-'+p).innerHTML='<i class="bi bi-check-lg"></i>';
        }else{
            document.getElementById('step-btn-'+p)?.classList.remove('done');
            document.getElementById('step-num-'+p).textContent=p;
        }
        cur=n;
        document.getElementById('panel-'+cur)?.classList.add('active');
        document.getElementById('step-btn-'+cur)?.classList.add('active');
        document.getElementById('step-btn-'+cur)?.classList.remove('done');
        document.getElementById('ecProgressBar').style.width=((cur/TOTAL)*100)+'%';
        window.scrollTo({top:0,behavior:'smooth'});
    }
    const ta=document.getElementById('descripcionTA'),cc=document.getElementById('charCount');
    if(ta){const u=()=>{const l=ta.value.length;cc.textContent=l+'/500 caracteres';cc.className='ec-char-count'+(l>450?' warn':'')};ta.addEventListener('input',u);u();}
    function toggleCat(el){el.classList.toggle('checked');el.querySelector('input[type="checkbox"]').checked=el.classList.contains('checked');document.getElementById('catCount').textContent=document.querySelectorAll('#catGrid .ec-cat-item.checked').length;}
    document.getElementById('buscarCat')?.addEventListener('input',function(){const q=this.value.toLowerCase();document.querySelectorAll('.ec-cat-item').forEach(i=>i.style.display=i.querySelector('.ec-cat-name').textContent.toLowerCase().includes(q)?'':'none');});
    ['archivoInput','imagenInput'].forEach(id=>{const el=document.getElementById(id);if(!el)return;el.addEventListener('change',function(){if(!this.files[0])return;this.closest('.ec-file-wrap').querySelector('.ec-file-text').innerHTML='<strong>'+this.files[0].name+'</strong><br><span style="font-size:.72rem;color:var(--color-success)">Listo para subir</span>';});});
    </script>
    @endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ========== CONTADOR DE CARACTERES ==========
        const descripcion = document.getElementById('descripcion');
        const contador = document.getElementById('contadorDescripcion');

        if (descripcion && contador) {
            const actualizarContador = () => {
                const length = descripcion.value.length;
                contador.textContent = length;
                contador.parentElement.classList.toggle('warning', length > 450);
            };

            actualizarContador();
            descripcion.addEventListener('input', actualizarContador);
        }

        // ========== BÚSQUEDA DE CATEGORÍAS ==========
        const buscadorCategorias = document.getElementById('buscadorCategorias');
        if (buscadorCategorias) {
            buscadorCategorias.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('.categoria-item-compact').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? 'block' : 'none';
                });
            });
        }

        // ========== VALIDACIÓN DE FECHAS ==========
        const fechaInicio = document.querySelector('input[name="fecha_ini"]');
        const fechaFin = document.querySelector('input[name="fecha_fin"]');

        if (fechaInicio && fechaFin) {
            fechaInicio.addEventListener('change', function() {
                if (fechaFin.value && new Date(this.value) > new Date(fechaFin.value)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fecha inválida',
                        text: 'La fecha de inicio no puede ser posterior a la fecha de fin',
                        confirmButtonColor: '#1a4789',
                        confirmButtonText: 'Entendido'
                    });
                    this.value = '';
                }
            });

            fechaFin.addEventListener('change', function() {
                if (fechaInicio.value && new Date(this.value) < new Date(fechaInicio.value)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fecha inválida',
                        text: 'La fecha de fin no puede ser anterior a la fecha de inicio',
                        confirmButtonColor: '#1a4789',
                        confirmButtonText: 'Entendido'
                    });
                    this.value = '';
                }
            });
        }

        // ========== VALIDACIÓN DE ARCHIVOS PDF ==========
        const archivoInput = document.getElementById('archivoInput');
        if (archivoInput) {
            archivoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.type !== 'application/pdf') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Archivo no válido',
                            text: 'Solo se permiten archivos PDF',
                            confirmButtonColor: '#1a4789',
                            confirmButtonText: 'Entendido'
                        });
                        this.value = '';
                        return;
                    }

                    // Validar tamaño (máximo 10MB)
                    const maxSize = 10 * 1024 * 1024; // 10MB en bytes
                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Archivo muy grande',
                            text: 'El archivo no debe superar los 10MB',
                            confirmButtonColor: '#1a4789',
                            confirmButtonText: 'Entendido'
                        });
                        this.value = '';
                    }
                }
            });
        }

        // ========== VALIDACIÓN DE ARCHIVOS DE IMAGEN ==========
        const imagenInput = document.getElementById('imagenInput');
        if (imagenInput) {
            imagenInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (!file.type.startsWith('image/')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Archivo no válido',
                            text: 'Solo se permiten archivos de imagen (JPG, PNG, GIF, etc.)',
                            confirmButtonColor: '#1a4789',
                            confirmButtonText: 'Entendido'
                        });
                        this.value = '';
                        return;
                    }

                    // Validar tamaño (máximo 5MB)
                    const maxSize = 5 * 1024 * 1024; // 5MB en bytes
                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Imagen muy grande',
                            text: 'La imagen no debe superar los 5MB',
                            confirmButtonColor: '#1a4789',
                            confirmButtonText: 'Entendido'
                        });
                        this.value = '';
                    }
                }
            });
        }

        // ========== CONFIRMACIÓN AL GUARDAR CURSO ==========
        const btnGuardarCurso = document.getElementById('btnGuardarCurso');
        const cursoForm = document.getElementById('cursoForm');

        if (btnGuardarCurso && cursoForm) {
            btnGuardarCurso.addEventListener('click', function(e) {
                e.preventDefault();

                // Validar campos obligatorios
                if (!cursoForm.checkValidity()) {
                    cursoForm.reportValidity();
                    return;
                }

                Swal.fire({
                    title: '¿Guardar cambios?',
                    text: '¿Está seguro de que desea modificar la información del curso?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>Sí, guardar',
                    cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loading
                        Swal.fire({
                            title: 'Guardando cambios...',
                            html: 'Por favor espere un momento',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Enviar formulario
                        cursoForm.submit();
                    }
                });
            });
        }

        // ========== CONFIRMACIÓN AL GUARDAR CATEGORÍAS ==========
        const btnGuardarCategorias = document.getElementById('btnGuardarCategorias');
        const categoriasForm = document.getElementById('categoriasForm');

        if (btnGuardarCategorias && categoriasForm) {
            btnGuardarCategorias.addEventListener('click', function(e) {
                e.preventDefault();

                // Contar categorías seleccionadas
                const categoriasSeleccionadas = categoriasForm.querySelectorAll('input[type="checkbox"]:checked');
                const totalSeleccionadas = categoriasSeleccionadas.length;

                if (totalSeleccionadas === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin categorías',
                        text: 'Debe seleccionar al menos una categoría para el curso',
                        confirmButtonColor: '#1a4789',
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }

                Swal.fire({
                    title: '¿Guardar categorías?',
                    html: `
                        <p>¿Está seguro de asignar <strong>${totalSeleccionadas}</strong> categoría(s) a este curso?</p>
                        <small class="text-muted">Esto reemplazará las categorías actuales</small>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>Sí, guardar',
                    cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loading
                        Swal.fire({
                            title: 'Guardando categorías...',
                            html: 'Por favor espere un momento',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Si estás usando AJAX (opcional)
                        // Descomenta esto si prefieres envío AJAX en lugar de submit normal
                        /*
                        const formData = new FormData(categoriasForm);

                        fetch(categoriasForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Guardado!',
                                text: 'Las categorías se actualizaron correctamente',
                                confirmButtonColor: '#1a4789',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al guardar las categorías',
                                confirmButtonColor: '#1a4789'
                            });
                        });
                        */

                        // Envío normal del formulario
                        categoriasForm.submit();
                    }
                });
            });
        }

        // ========== RESALTAR CATEGORÍAS AL BUSCAR ==========
        if (buscadorCategorias) {
            buscadorCategorias.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const items = document.querySelectorAll('.categoria-item-compact');

                items.forEach(item => {
                    const label = item.querySelector('label');
                    const text = label.textContent.toLowerCase();

                    if (searchTerm === '') {
                        item.style.display = 'block';
                        label.innerHTML = label.textContent; // Remover highlights
                    } else if (text.includes(searchTerm)) {
                        item.style.display = 'block';

                        // Resaltar texto coincidente
                        const regex = new RegExp(`(${searchTerm})`, 'gi');
                        const icon = '<i class="fas fa-folder me-1"></i>';
                        const originalText = label.textContent.replace('📁 ', '');
                        const highlighted = originalText.replace(regex, '<mark>$1</mark>');
                        label.innerHTML = icon + highlighted;
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        // ========== CONTADOR DE CATEGORÍAS SELECCIONADAS ==========
        const categoriaCheckboxes = document.querySelectorAll('.categoria-checkbox');
        if (categoriaCheckboxes.length > 0) {
            const actualizarContadorCategorias = () => {
                const seleccionadas = document.querySelectorAll('.categoria-checkbox:checked').length;
                const total = categoriaCheckboxes.length;

                // Puedes agregar un elemento para mostrar esto si lo deseas
                console.log(`Categorías seleccionadas: ${seleccionadas} de ${total}`);
            };

            categoriaCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', actualizarContadorCategorias);
            });

            actualizarContadorCategorias();
        }

        // ========== ANIMACIÓN AL CAMBIAR DE TAB ==========
        const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
        tabButtons.forEach(button => {
            button.addEventListener('shown.bs.tab', function(e) {
                const targetPane = document.querySelector(this.getAttribute('data-bs-target'));
                if (targetPane) {
                    targetPane.style.opacity = '0';
                    setTimeout(() => {
                        targetPane.style.transition = 'opacity 0.3s ease';
                        targetPane.style.opacity = '1';
                    }, 10);
                }
            });
        });

        // ========== PREVENIR ENVÍO DOBLE DEL FORMULARIO ==========
        let formSubmitting = false;

        if (cursoForm) {
            cursoForm.addEventListener('submit', function(e) {
                if (formSubmitting) {
                    e.preventDefault();
                    return false;
                }
                formSubmitting = true;

                // Deshabilitar botón de envío
                if (btnGuardarCurso) {
                    btnGuardarCurso.disabled = true;
                    btnGuardarCurso.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
                }
            });
        }

        if (categoriasForm) {
            categoriasForm.addEventListener('submit', function(e) {
                if (formSubmitting) {
                    e.preventDefault();
                    return false;
                }
                formSubmitting = true;

                // Deshabilitar botón de envío
                if (btnGuardarCategorias) {
                    btnGuardarCategorias.disabled = true;
                    btnGuardarCategorias.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
                }
            });
        }

        // ========== VALIDACIÓN DE NÚMEROS ==========
        const numerosInputs = document.querySelectorAll('input[type="number"]');
        numerosInputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.value < 0) {
                    this.value = 0;
                }
            });
        });

        // ========== CONFIRMACIÓN AL ELIMINAR ARCHIVOS ==========
        const eliminarArchivo = document.getElementById('eliminar_archivo');
        if (eliminarArchivo) {
            eliminarArchivo.addEventListener('change', function() {
                if (this.checked) {
                    Swal.fire({
                        icon: 'warning',
                        title: '¿Eliminar archivo?',
                        text: 'El archivo PDF actual será eliminado permanentemente',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            this.checked = false;
                        }
                    });
                }
            });
        }

        const eliminarImagen = document.getElementById('eliminar_imagen');
        if (eliminarImagen) {
            eliminarImagen.addEventListener('change', function() {
                if (this.checked) {
                    Swal.fire({
                        icon: 'warning',
                        title: '¿Eliminar imagen?',
                        text: 'La imagen actual será eliminada permanentemente',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            this.checked = false;
                        }
                    });
                }
            });
        }
    });
</script>
