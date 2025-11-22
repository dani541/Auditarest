@extends('layouts.app')

@section('title', 'Nuevo Restaurante')

@section('content')
<div class="container mt-4">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle text-success"></i> Nuevo Restaurante
                    </h5>
                    <a href="{{ route('admin.restaurants.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.restaurants.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Restaurante</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                   id="address" name="address" value="{{ old('address') }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                            
                        <div class="mb-3">
                            <label for="city" class="form-label">
                                <i class="fas fa-city me-1 text-primary"></i> Ciudad
                            </label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                   id="city" name="city" value="{{ old('city') }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact_name" class="form-label">
                                <i class="fas fa-user me-1 text-primary"></i> Nombre de Contacto
                            </label>
                            <input type="text" class="form-control @error('contact_name') is-invalid @enderror" 
                                   id="contact_name" name="contact_name" value="{{ old('contact_name') }}" required>
                            @error('contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">
                                <i class="fas fa-phone me-1 text-primary"></i> Teléfono de Contacto
                            </label>
                            <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" 
                                   id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}" required>
                            @error('contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact_email" class="form-label">
                                <i class="fas fa-envelope me-1 text-primary"></i> Email de Contacto
                            </label>
                            <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                   id="contact_email" name="contact_email" value="{{ old('contact_email') }}" required>
                            @error('contact_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Guardar Restaurante
                            </button>
                            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- 
    @endsection 
--}}

@push('styles')
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
        const form = document.getElementById('createRestaurantForm');
        
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
                const existingAlert = document.querySelector('.alert.alert-danger');
                if (!existingAlert) {
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
