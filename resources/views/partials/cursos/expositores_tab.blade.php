  <div class="tab-pane fade" id="tab-expositores" role="tabpanel">
      <div class="cc-tab-header">
          <div>
              <h4 class="cc-tab-title">
                  <i class="bi bi-mic-fill me-2"></i>Expositores del Congreso
              </h4>
              <p class="cc-tab-sub">Profesionales que impartirán las ponencias</p>
          </div>
          @if ($esDocenteOAdmin)
              <button class="cc-btn cc-btn-primary" data-bs-toggle="modal" data-bs-target="#modalExpositores">
                  <i class="bi bi-person-plus-fill me-2"></i>Asignar Expositores
              </button>
          @endif
      </div>

      {{-- Contador de expositores --}}
      @if ($cursos->expositores->count() > 0)
          <div class="cc-exp-stats">
              <div class="cc-exp-stat">
                  <i class="bi bi-people-fill"></i>
                  <span><strong>{{ $cursos->expositores->count() }}</strong>
                      expositor{{ $cursos->expositores->count() > 1 ? 'es' : '' }}</span>
              </div>
              <div class="cc-exp-stat">
                  <i class="bi bi-chat-quote-fill"></i>
                  <span><strong>{{ $cursos->expositores->filter(fn($e) => $e->pivot->tema)->count() }}</strong>
                      ponencia{{ $cursos->expositores->filter(fn($e) => $e->pivot->tema)->count() > 1 ? 's' : '' }}
                      programada{{ $cursos->expositores->filter(fn($e) => $e->pivot->tema)->count() > 1 ? 's' : '' }}</span>
              </div>
          </div>
      @endif

      <div class="row g-4">
          @forelse($cursos->expositores->sortBy('pivot.orden') as $expositor)
              @php
                  $imgExp =
                      $expositor->imagen && file_exists(public_path('storage/' . $expositor->imagen))
                          ? asset('storage/' . $expositor->imagen)
                          : asset('assets2/img/talker.png');
                  $ordenNum = $expositor->pivot->orden ?? null;
              @endphp
              <div class="col-lg-6 col-md-6">
                  <div class="cc-speaker-card">
                      {{-- Número de orden --}}
                      @if ($ordenNum)
                          <div class="cc-speaker-orden">#{{ $ordenNum }}</div>
                      @endif

                      {{-- Cabecera con foto y nombre --}}
                      <div class="cc-speaker-header">
                          <div class="cc-speaker-avatar-wrap">
                              <img src="{{ $imgExp }}" class="cc-speaker-avatar" alt="{{ $expositor->nombre }}">
                              <div class="cc-speaker-avatar-ring"></div>
                          </div>
                          <div class="cc-speaker-identity">
                              <h5 class="cc-speaker-name">{{ $expositor->nombre }}</h5>
                              @if ($expositor->pivot->cargo)
                                  <span class="cc-speaker-cargo">
                                      <i class="bi bi-briefcase-fill me-1"></i>{{ $expositor->pivot->cargo }}
                                  </span>
                              @else
                                  <span class="cc-speaker-cargo cc-speaker-cargo--empty">
                                      <i class="bi bi-briefcase me-1"></i>Cargo no especificado
                                  </span>
                              @endif
                          </div>
                      </div>

                      {{-- Tema de la ponencia --}}
                      <div class="cc-speaker-topic">
                          <div class="cc-speaker-topic-label">
                              <i class="bi bi-megaphone-fill"></i>
                              <span>Tema de Ponencia</span>
                          </div>
                          @if ($expositor->pivot->tema)
                              <p class="cc-speaker-topic-text">{{ $expositor->pivot->tema }}</p>
                          @else
                              <p class="cc-speaker-topic-text cc-speaker-topic-text--empty">
                                  <i class="bi bi-dash-lg me-1"></i>No especificado aún
                              </p>
                          @endif
                      </div>

                      {{-- Footer con acciones --}}
                      @if ($esDocenteOAdmin)
                          <div class="cc-speaker-footer">
                              <form action="{{ route('cursos.quitarExpositor', [$cursos->id, $expositor->id]) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Deseas quitar este expositor del congreso?')">
                                  @csrf @method('DELETE')
                                  <button type="submit" class="cc-btn cc-btn-danger-sm">
                                      <i class="bi bi-person-x-fill me-1"></i>Quitar Expositor
                                  </button>
                              </form>
                          </div>
                      @endif
                  </div>
              </div>
          @empty
              <div class="col-12">
                  <div class="cc-empty-state">
                      <div class="cc-empty-state-icon-wrap">
                          <i class="bi bi-mic-mute"></i>
                      </div>
                      <h5>No hay expositores asignados</h5>
                      <p>Asigna expositores para comenzar con la organización del congreso</p>
                      @if ($esDocenteOAdmin)
                          <button class="cc-btn cc-btn-primary mt-2" data-bs-toggle="modal"
                              data-bs-target="#modalExpositores">
                              <i class="bi bi-person-plus-fill me-2"></i>Asignar Primer Expositor
                          </button>
                      @endif
                  </div>
              </div>
          @endforelse
      </div>
  </div>


  {{-- ═══ Modal Asignar Expositores (mejorado) ═══ --}}
  @if ($cursos->tipo == 'congreso' && $esDocenteOAdmin)
      <div class="modal fade" id="modalExpositores" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
              <div class="modal-content cc-modal">

                  {{-- Header --}}
                  <div class="cc-modal-header">
                      <div class="cc-modal-icon"><i class="bi bi-person-plus-fill"></i></div>
                      <div>
                          <h5 class="cc-modal-title">Asignar Expositores</h5>
                          <small>{{ $cursos->nombreCurso }}</small>
                      </div>
                      <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                  </div>

                  {{-- Instrucciones + buscador --}}
                  <div class="cc-exp-toolbar">
                      <div class="cc-exp-toolbar-hint">
                          <i class="bi bi-info-circle-fill"></i>
                          <span>Selecciona los expositores y completa su información de ponencia.</span>
                      </div>
                      <div class="cc-modal-search">
                          <i class="bi bi-search cc-modal-search-icon"></i>
                          <input type="text" id="buscadorExpositores" class="cc-modal-search-input"
                              placeholder="Buscar por nombre...">
                          <span class="cc-exp-search-count" id="expSearchCount"></span>
                      </div>
                  </div>

                  <form method="POST" action="{{ route('cursos.asignarExpositores', $cursos->id) }}">
                      @csrf

                      {{-- Lista (scrollable) --}}
                      <div class="modal-body">
                          <div class="cc-exp-list" id="expositoresList">
                              @foreach ($expositores as $expositor)
                                  @php
                                      $imgModalExp =
                                          $expositor->imagen &&
                                          file_exists(public_path('storage/' . $expositor->imagen))
                                              ? asset('storage/' . $expositor->imagen)
                                              : asset('assets2/img/talker.png');
                                  @endphp
                                  <div class="cc-exp-item" data-nombre="{{ strtolower($expositor->nombre) }}"
                                      data-id="{{ $expositor->id }}">
                                      {{-- Cabecera del item --}}
                                      <div class="cc-exp-item-head">
                                          <div class="cc-exp-item-left">
                                              <div class="cc-exp-avatar-wrap">
                                                  <img src="{{ $imgModalExp }}" class="cc-exp-avatar"
                                                      alt="{{ $expositor->nombre }}">
                                              </div>
                                              <div class="cc-exp-item-info">
                                                  <strong class="cc-exp-item-name">{{ $expositor->nombre }}</strong>
                                                  <span class="cc-exp-item-status">
                                                      <i class="bi bi-circle-fill"></i> Disponible
                                                  </span>
                                              </div>
                                          </div>
                                          <label class="cc-exp-toggle" for="exp-check-{{ $expositor->id }}">
                                              <input type="checkbox" class="cc-exp-toggle-input"
                                                  name="expositoresSeleccionados[]" value="{{ $expositor->id }}"
                                                  id="exp-check-{{ $expositor->id }}">
                                              <span class="cc-exp-toggle-slider"></span>
                                          </label>
                                      </div>

                                      {{-- Campos de detalle (se expanden al seleccionar) --}}
                                      <input type="hidden" name="expositores[{{ $expositor->id }}][id]"
                                          value="{{ $expositor->id }}">
                                      <div class="cc-exp-details">
                                          <div class="cc-exp-details-inner">
                                              <div class="cc-exp-detail-row">
                                                  <div class="cc-exp-detail-field">
                                                      <label class="cc-exp-detail-label">
                                                          <i class="bi bi-briefcase-fill"></i> Cargo
                                                      </label>
                                                      <input type="text" class="cc-input"
                                                          name="expositores[{{ $expositor->id }}][cargo]"
                                                          placeholder="Ej: Profesor Titular, Investigador...">
                                                  </div>
                                                  <div class="cc-exp-detail-field">
                                                      <label class="cc-exp-detail-label">
                                                          <i class="bi bi-sort-numeric-down"></i> Orden
                                                      </label>
                                                      <input type="number" class="cc-input"
                                                          name="expositores[{{ $expositor->id }}][orden]"
                                                          placeholder="#" min="1">
                                                  </div>
                                              </div>
                                              <div class="cc-exp-detail-field">
                                                  <label class="cc-exp-detail-label">
                                                      <i class="bi bi-megaphone-fill"></i> Tema de Ponencia
                                                  </label>
                                                  <input type="text" class="cc-input"
                                                      name="expositores[{{ $expositor->id }}][tema]"
                                                      placeholder="Ej: Inteligencia Artificial en la Educación...">
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              @endforeach
                          </div>
                      </div>

                      {{-- Footer --}}
                      <div class="modal-footer cc-exp-modal-footer">
                          <div class="cc-exp-selected-count" id="expSelectedCount">
                              <i class="bi bi-people-fill"></i>
                              <span><strong>0</strong> seleccionados</span>
                          </div>
                          <div class="cc-exp-footer-actions">
                              <button type="button" class="cc-btn cc-btn-outline" data-bs-dismiss="modal">
                                  Cancelar
                              </button>
                              <button type="submit" class="cc-btn cc-btn-success" id="btnGuardarExp">
                                  <i class="bi bi-check-circle-fill me-2"></i>Guardar Asignaciones
                              </button>
                          </div>
                      </div>
                  </form>

              </div>
          </div>
      </div>

      {{-- Script del modal --}}
      <script>
          document.addEventListener('DOMContentLoaded', function() {
              const list = document.getElementById('expositoresList');
              const countEl = document.querySelector('#expSelectedCount strong');
              const searchEl = document.getElementById('buscadorExpositores');
              const searchCount = document.getElementById('expSearchCount');

              if (!list) return;

              // Toggle detalles al seleccionar
              list.querySelectorAll('.cc-exp-toggle-input').forEach(cb => {
                  cb.addEventListener('change', function() {
                      const item = this.closest('.cc-exp-item');
                      item.classList.toggle('cc-exp-item--selected', this.checked);
                      updateCount();
                  });
              });

              function updateCount() {
                  const n = list.querySelectorAll('.cc-exp-toggle-input:checked').length;
                  if (countEl) countEl.textContent = n;
              }

              // Buscador
              if (searchEl) {
                  searchEl.addEventListener('input', function() {
                      const q = this.value.toLowerCase().trim();
                      const items = list.querySelectorAll('.cc-exp-item');
                      let shown = 0;
                      items.forEach(item => {
                          const nombre = item.getAttribute('data-nombre') || '';
                          const match = nombre.includes(q);
                          item.style.display = match ? '' : 'none';
                          if (match) shown++;
                      });
                      if (searchCount) {
                          searchCount.textContent = q ? shown + ' resultado' + (shown !== 1 ? 's' : '') : '';
                      }
                  });
              }
          });
      </script>
  @endif
