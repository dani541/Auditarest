@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-edit me-2"></i>Editar Restaurante
        </h1>
        <div>
            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
            <button type="submit" form="editRestaurantForm" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Guardar Cambios
            </button>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-store me-2"></i> Información del Restaurante
            </h5>
        </div>
        <div class="card-body">
            <form id="editRestaurantForm" action="{{ route('admin.restaurants.update', $restaurant->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Columna izquierda -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-store me-1 text-primary"></i> Nombre del Restaurante <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $restaurant->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="address" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1 text-primary"></i> Dirección <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                           id="address" name="address" value="{{ old('address', $restaurant->address) }}" required>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city" class="form-label">
                                        <i class="fas fa-city me-1 text-primary"></i> Ciudad <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           id="city" name="city" value="{{ old('city', $restaurant->city) }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="postal_code" class="form-label">
                                        <i class="fas fa-mail-bulk me-1 text-primary"></i> Código Postal
                                    </label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                           id="postal_code" name="postal_code" value="{{ old('postal_code', $restaurant->postal_code) }}">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <i class="fas fa-user-tie me-1"></i> Datos de Contacto
                        </h5>
                        
                        <div class="mb-3">
                            <label for="contact_name" class="form-label">
                                <i class="fas fa-user me-1 text-primary"></i> Nombre del Contacto <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('contact_name') is-invalid @enderror" 
                                   id="contact_name" name="contact_name" value="{{ old('contact_name', $restaurant->contact_name) }}" required>
                            @error('contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">
                                        <i class="fas fa-phone me-1 text-primary"></i> Teléfono <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" 
                                           id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $restaurant->contact_phone) }}" required>
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">
                                        <i class="fas fa-envelope me-1 text-primary"></i> Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                           id="contact_email" name="contact_email" value="{{ old('contact_email', $restaurant->contact_email) }}" required>
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="opening_hours" class="form-label">
                                <i class="fas fa-clock me-1 text-primary"></i> Horario
                            </label>
                            <textarea class="form-control @error('opening_hours') is-invalid @enderror" 
                                      id="opening_hours" name="opening_hours" rows="2">{{ old('opening_hours', $restaurant->opening_hours) }}</textarea>
                            @error('opening_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1 text-primary"></i> Descripción
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $restaurant->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.restaurants.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Limpiar mensajes de error al escribir en los campos
        const form = document.getElementById('editRestaurantForm');
        if (form) {
            form.querySelectorAll('input, textarea, select').forEach(input => {
                input.addEventListener('input', function() {
                    if (this.classList.contains('is-invalid')) {
                        this.classList.remove('is-invalid');
                        const errorDiv = this.nextElementSibling;
                        if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                            errorDiv.remove();
                        }
                    }
                });
            });
        }
    });
</script>
@endpush
                                    <div class="form-group mb-3">
                                        <label for="contact_phone" class="form-label">
                                            <i class="fas fa-phone me-1 text-primary"></i> Teléfono <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                            <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" 
                                                   id="contact_phone" name="contact_phone" 
                                                   value="{{ old('contact_phone', $restaurant->contact_phone) }}" required>
                                            @error('contact_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="contact_phone_secondary" class="form-label">
                                            <i class="fas fa-phone-alt me-1 text-primary"></i> Teléfono Secundario
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="tel" class="form-control @error('contact_phone_secondary') is-invalid @enderror" 
                                                   id="contact_phone_secondary" name="contact_phone_secondary" 
                                                   value="{{ old('contact_phone_secondary', $restaurant->contact_phone_secondary) }}">
                                            @error('contact_phone_secondary')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="contact_email" class="form-label">
                                    <i class="fas fa-envelope me-1 text-primary"></i> Correo Electrónico <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-at"></i></span>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                           id="contact_email" name="contact_email" 
                                           value="{{ old('contact_email', $restaurant->contact_email) }}" required>
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="website" class="form-label">
                                    <i class="fas fa-globe me-1 text-primary"></i> Sitio Web
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                                    <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                           id="website" name="website" 
                                           placeholder="https://" 
                                           value="{{ old('website', $restaurant->website) }}">
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="opening_hours" class="form-label">
                                    <i class="fas fa-clock me-1 text-primary"></i> Horario de Atención
                                </label>
                                <input type="text" class="form-control @error('opening_hours') is-invalid @enderror" 
                                       id="opening_hours" name="opening_hours" 
                                       placeholder="Ej: Lunes a Viernes: 9:00 - 22:00 | Sábados: 10:00 - 23:00" 
                                       value="{{ old('opening_hours', $restaurant->opening_hours) }}">
                                @error('opening_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-danger" 
                                    onclick="if(confirm('¿Estás seguro de que deseas eliminar este restaurante?')) { document.getElementById('deleteForm').submit(); }">
                                <i class="fas fa-trash-alt me-1"></i> Eliminar Restaurante
                            </button>
                            
                            <div>
                                <a href="{{ route('admin.restaurants.show', $restaurant->id) }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <!-- Formulario oculto para eliminar -->
                <form id="deleteForm" action="{{ route('admin.restaurants.destroy', $restaurant->id ?? '') }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<style>
    .form-label {
        font-weight: 500;
    }
    .required-field::after {
        content: " *";
        color: #dc3545;
    }
    .input-group-text {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Validación del formulario
        const form = document.getElementById('editRestaurantForm');
        
        form.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Validar campos requeridos
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Este campo es obligatorio.';
                        field.parentNode.insertBefore(errorDiv, field.nextSibling);
                    }
                }
            });
            
            // Validar formato de email
            const emailField = document.getElementById('contact_email');
            if (emailField && emailField.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailField.value)) {
                    isValid = false;
                    emailField.classList.add('is-invalid');
                    if (!emailField.nextElementSibling || !emailField.nextElementSibling.classList.contains('invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Por favor, introduce un correo electrónico válido.';
                        emailField.parentNode.insertBefore(errorDiv, emailField.nextSibling);
                    }
                }
            }
            
            // Validar formato de URL si existe
            const websiteField = document.getElementById('website');
            if (websiteField && websiteField.value) {
                try {
                    new URL(websiteField.value);
                } catch (_) {
                    isValid = false;
                    websiteField.classList.add('is-invalid');
                    if (!websiteField.nextElementSibling || !websiteField.nextElementSibling.classList.contains('invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Por favor, introduce una URL válida (ej: https://ejemplo.com).';
                        websiteField.parentNode.insertBefore(errorDiv, websiteField.nextSibling);
                    }
                }
            }
            
            if (!isValid) {
                event.preventDefault();
                event.stopPropagation();
                
                // Desplazarse al primer campo con error
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
                
                // Mostrar alerta de error
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                alertDiv.role = 'alert';
                alertDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Por favor, corrige los errores en el formulario antes de continuar.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                `;
                
                // Insertar la alerta después del primer h1
                const firstH1 = document.querySelector('h1');
                if (firstH1) {
                    firstH1.parentNode.insertBefore(alertDiv, firstH1.nextSibling);
                } else {
                    form.prepend(alertDiv);
                }
                
                // Cerrar la alerta después de 5 segundos
                setTimeout(() => {
                    const alert = bootstrap.Alert.getOrCreateInstance(alertDiv);
                    if (alert) alert.close();
                }, 5000);
            }
        });
        
        // Limpiar la validación al cambiar los campos
        form.querySelectorAll('input, textarea, select').forEach(input => {
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    const errorDiv = this.nextElementSibling;
                    if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv.remove();
                    }
                }
            });
        });
    });
</script>
@endpush
