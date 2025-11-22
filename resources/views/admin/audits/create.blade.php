@extends('layouts.app')

@section('title', 'Nueva Auditoría')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="h2 mb-0">
                        <i class="fas fa-clipboard-check"></i> Nueva Auditoría
                    </h1>
                    <p class="text-muted mb-0">
                        {{ $restaurant->name }} - {{ now()->format('d/m/Y') }}
                    </p>
                </div>

                <form action="{{ route('admin.restaurants.audits.store', $restaurant->id) }}" method="POST" id="auditForm">
                    @csrf
                    <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                    
                    <div class="card-body">
                        <!-- Sección de Información General -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle"></i> Información General
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Auditor</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fecha de la Auditoría</label>
                                    <input type="date" class="form-control" name="audit_date" value="{{ now()->format('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Verificaciones -->
                        @foreach(['INFRAESTRUCTURA', 'MAQUINARIA', 'SUPERFICIES_DE_TRABAJO', 'BUENAS_PRACTICAS'] as $category)
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-{{ $category === 'BUENAS_PRACTICAS' ? 'check-circle' : 'tools' }}"></i>
                                    {{ ucfirst(str_replace('_', ' ', $category)) }}
                                </h5>
                                
                                @php
                                    $items = $verificationItems->where('category', $category);
                                @endphp
                                
                                @if($items->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 60%">Verificación</th>
                                                    <th class="text-center">Cumple</th>
                                                    <th class="text-center">No Cumple</th>
                                                    <th class="text-center">No Aplica</th>
                                                    <th>Observaciones / Medida Correctiva</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($items as $item)
                                                    <tr>
                                                        <td>
                                                            {{ $item->description }}
                                                            @if(str_contains(strtoupper($item->description), 'TEMPERATURA'))
                                                                <div class="input-group mt-2 temperature-input" style="display: none;">
                                                                    <input type="number" step="0.01" 
                                                                           name="temperature_{{ $item->id }}" 
                                                                           class="form-control form-control-sm" 
                                                                           placeholder="Temperatura">
                                                                    <span class="input-group-text">°C</span>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check d-flex justify-content-center">
                                                                <input class="form-check-input" type="radio" 
                                                                       name="status_{{ $item->id }}" 
                                                                       id="status_c_{{ $item->id }}" 
                                                                       value="C" required>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check d-flex justify-content-center">
                                                                <input class="form-check-input" type="radio" 
                                                                       name="status_{{ $item->id }}" 
                                                                       id="status_ic_{{ $item->id }}" 
                                                                       value="IC" required>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check d-flex justify-content-center">
                                                                <input class="form-check-input" type="radio" 
                                                                       name="status_{{ $item->id }}" 
                                                                       id="status_na_{{ $item->id }}" 
                                                                       value="NA">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" 
                                                                   name="corrective_measure_{{ $item->id }}" 
                                                                   class="form-control form-control-sm" 
                                                                   placeholder="Observaciones...">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        No hay verificaciones disponibles para esta categoría.
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <!-- Sección de Observaciones Generales -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-clipboard"></i> Observaciones Generales
                            </h5>
                            <textarea name="general_observations" class="form-control" rows="3" 
                                      placeholder="Escribe aquí cualquier observación general sobre la auditoría..."></textarea>
                        </div>

                        <!-- Sección de Evidencias -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-camera"></i> Evidencias Fotográficas
                            </h5>
                            <div class="dropzone" id="evidenceDropzone">
                                <div class="dz-message" data-dz-message>
                                    <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                                    <h5>Arrastra y suelta archivos aquí o haz clic para seleccionar</h5>
                                    <p class="text-muted">Puedes subir múltiples archivos (máx. 5MB por archivo)</p>
                                </div>
                            </div>
                            <div id="filePreview" class="row mt-3"></div>
                        </div>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-between">
                        <a href="{{ route('admin.restaurants.show', $restaurant->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Auditoría
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table th {
        white-space: nowrap;
    }
    .form-check-input {
        transform: scale(1.3);
    }
    .dropzone {
        border: 2px dashed #dee2e6;
        border-radius: 0.375rem;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    .dropzone:hover {
        border-color: #0d6efd;
        background-color: #f8f9fa;
    }
    .dz-message {
        color: #6c757d;
    }
    .evidence-thumbnail {
        position: relative;
        margin: 5px;
        max-width: 100px;
    }
    .evidence-thumbnail img {
        border-radius: 4px;
        max-height: 80px;
        object-fit: cover;
    }
    .remove-evidence {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mostrar/ocultar campos de temperatura según la selección
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const row = this.closest('tr');
                const temperatureInput = row.querySelector('.temperature-input');
                
                if (temperatureInput) {
                    temperatureInput.style.display = this.value === 'IC' ? 'block' : 'none';
                    if (this.value !== 'IC') {
                        temperatureInput.querySelector('input').value = '';
                    }
                }
            });
        });

        // Inicializar Dropzone
        const myDropzone = new Dropzone("#evidenceDropzone", {
            url: "{{ route('admin.audits.upload-evidence') }}",
            maxFilesize: 5, // MB
            maxFiles: 10,
            acceptedFiles: 'image/*,.pdf,.doc,.docx',
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            init: function() {
                this.on("success", function(file, response) {
                    // Agregar input hidden con la ruta del archivo
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'evidence[]';
                    input.value = response.path;
                    document.getElementById('auditForm').appendChild(input);
                    
                    // Mostrar vista previa
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const preview = `
                                <div class="col-6 col-md-3 col-lg-2 evidence-thumbnail">
                                    <span class="remove-evidence" data-file="${response.path}">&times;</span>
                                    <img src="${e.target.result}" class="img-thumbnail" alt="Preview">
                                </div>
                            `;
                            document.getElementById('filePreview').insertAdjacentHTML('beforeend', preview);
                        };
                        reader.readAsDataURL(file);
                    }
                });
                
                this.on("removedfile", function(file) {
                    // Eliminar el input hidden correspondiente
                    const inputs = document.querySelectorAll('input[name="evidence[]"]');
                    inputs.forEach(input => {
                        if (input.value === file.name) {
                            input.remove();
                        }
                    });
                    
                    // Eliminar la vista previa
                    const previews = document.querySelectorAll('.evidence-thumbnail');
                    previews.forEach(preview => {
                        if (preview.querySelector('img').alt === file.name) {
                            preview.remove();
                        }
                    });
                });
            }
        });

        // Manejar la eliminación de evidencias
        document.getElementById('filePreview').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-evidence')) {
                const filePath = e.target.getAttribute('data-file');
                // Aquí podrías hacer una llamada AJAX para eliminar el archivo del servidor si es necesario
                e.target.closest('.evidence-thumbnail').remove();
                
                // Eliminar el input hidden correspondiente
                const inputs = document.querySelectorAll('input[name="evidence[]"]');
                inputs.forEach(input => {
                    if (input.value === filePath) {
                        input.remove();
                    }
                });
            }
        });
    });
</script>
@endpush

@endsection
