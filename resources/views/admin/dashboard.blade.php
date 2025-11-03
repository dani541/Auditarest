@extends('layouts.app')

@section('title', 'Panel de Control')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt"></i> Panel de Control
    </h1>
</div>

<div class="row">
    <!-- Tarjeta de Resumen de Restaurantes -->
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

    <!-- Tarjeta de Auditorías Pendientes -->
    <div class="col-md-4 mb-4">
        <div class="card border-left-warning h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Auditorías Pendientes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ App\Models\Audit::where('status', 'pendiente')->count() }}
                        </div>
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
    <!-- Sección de Actividad Reciente -->
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Actividad Reciente</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuLink">
                        <li><a class="dropdown-item" href="#">Ver todo</a></li>
                        <li><a class="dropdown-item" href="#">Exportar</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-gray-300 mb-3"></i>
                    <p class="text-muted">No hay actividad reciente para mostrar</p>
                </div>
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
