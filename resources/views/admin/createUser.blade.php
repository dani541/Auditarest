@extends('layouts.app')

@section('title', 'Panel de Control')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt"></i> Panel de Control
    </h1>
</div>

<div class="row">
    
    <div class="col-md-4 mb-4">
        <div class="card border-left-primary h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total de Restaurantes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ App\Models\Restaurant::count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-utensils fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('admin.restaurants.index') }}" class="text-primary text-decoration-none small">
                    Ver detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    
    <div class="col-md-4 mb-4">
        <div class="card border-left-warning h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="#" class="text-warning text-decoration-none small">
                    Ver detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjeta de Usuarios Activos -->
    <div class="col-md-4 mb-4">
        <div class="card border-left-success h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Usuarios Activos</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ App\Models\User::count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="#" class="text-success text-decoration-none small">
                    Ver detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sección de Creación de Usuario -->
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Crear Nuevo Usuario</h6>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nombre:</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contraseña:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirmar Contraseña:</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rol:</label>
                        <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                            <option value="">Seleccionar Rol</option>
                            @php
                                // Add default roles if none exist
                                $defaultRoles = [
                                    (object)['id' => 1, 'name' => 'Administrador'],
                                    (object)['id' => 2, 'name' => 'Auditor'],
                                    (object)['id' => 3, 'name' => 'Usuario']
                                ];
                                $roles = $roles->isEmpty() ? collect($defaultRoles) : $roles;
                            @endphp
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <!--
                    <div class="mb-3">
                        <label class="form-label">Restaurante (opcional):</label>
                        <select name="restaurant_id" class="form-select">
                            <option value="">Seleccionar Restaurante</option>
                            @foreach($restaurantes as $restaurante)
                                <option value="{{ $restaurante->id }}" {{ old('restaurant_id') == $restaurante->id ? 'selected' : '' }}>
                                    {{ $restaurante->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Crear Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para gráficos (puedes activarlos más adelante) -->
@push('scripts')
<!-- Incluir Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
@endsection
