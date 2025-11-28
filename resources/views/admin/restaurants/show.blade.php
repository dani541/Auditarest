<!DOCTYPE html>
<html lang="es">
<head>
@extends('layouts.app')

@section('title', 'Detalles del Restaurante' . ($restaurant ? ': ' . $restaurant->name : ''))

@section('content')
<div class="container mt-4">
   <!-- <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-store"></i> {{ $restaurant->name ?? 'Restaurante no encontrado' }}
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.restaurants.edit', $restaurant->id ?? '') }}" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>-->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h1 class="h2 mb-0">
                    <i class="fas fa-store"></i> {{ $restaurant->name ?? 'Restaurante no encontrado' }}
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.restaurants.edit', $restaurant->id) }}" class="btn btn-sm btn-outline-primary me-2">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('admin.restaurants.pdf', $restaurant->id) }}" class="btn btn-sm btn-danger me-2">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </a>
                <a href="{{ route('admin.restaurants.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-info-circle text-primary"></i> Información General
                        </h5>
                        
                        <p><strong><i class="fas fa-store me-2 text-muted"></i> Nombre:</strong> {{ $restaurant->name ?? 'No especificado' }}</p>
                        <p><strong><i class="fas fa-map-marker-alt me-2 text-muted"></i> Dirección:</strong> {{ $restaurant->address ?? 'No especificada' }}</p>
                        <p><strong><i class="fas fa-city me-2 text-muted"></i> Ciudad:</strong> {{ $restaurant->city ?? 'No especificada' }}</p>
                        
                        @if(isset($restaurant->description) && $restaurant->description)
                            <div class="mt-3">
                                <h6 class="text-muted mb-2">Descripción:</h6>
                                <p class="border rounded p-3 bg-light">{{ $restaurant->description }}</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-address-card text-primary"></i> Datos de Contacto
                        </h5>
                        
                        <p><strong><i class="fas fa-user me-2 text-muted"></i> Contacto:</strong> {{ $restaurant->contact_name ?? 'No especificado' }}</p>
                        <p><strong><i class="fas fa-phone me-2 text-muted"></i> Teléfono:</strong> {{ $restaurant->contact_phone ?? 'No especificado' }}</p>
                        <p><strong><i class="fas fa-envelope me-2 text-muted"></i> Email:</strong> {{ $restaurant->contact_email ?? 'No especificado' }}</p>
                        
                        <div class="mt-4">
                            <a href="{{ route('admin.restaurants.edit', $restaurant->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Editar Restaurante
                            </a>
                            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-arrow-left me-1"></i> Volver al listado
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer text-muted">
                <div class="row">
                    <div class="col-md-6">
                        <small>
                            Creado: {{ $restaurant->created_at->format('d/m/Y H:i') }}
                            @if($restaurant->created_at != $restaurant->updated_at)
                                <br>Actualizado: {{ $restaurant->updated_at->format('d/m/Y H:i') }}
                            @endif
                        </small>
                    </div>
                    <div class="col-md-6 text-end">
                        <form action="{{ route('admin.restaurants.destroy', $restaurant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este restaurante?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash me-1"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            </div>
            @if($restaurant->audits->count() > 5)
            <div class="card-footer text-end">
                <a href="{{ route('admin.restaurants.audits.index', $restaurant->id) }}" class="btn btn-sm btn-link">
                    Ver todas las auditorías <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie text-primary"></i> Estadísticas
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="display-4">{{ $restaurant->audits->count() }}</div>
                    <div class="text-muted">Auditorías realizadas</div>
                </div>
                
                @php
                    $completed = $restaurant->audits->where('status', 'completada')->count();
                    $inProgress = $restaurant->audits->where('status', 'en_curso')->count();
                    $pending = $restaurant->audits->where('status', 'pendiente')->count();
                    $total = $restaurant->audits->count();
                    
                    $completedPercent = $total > 0 ? ($completed / $total) * 100 : 0;
                    $inProgressPercent = $total > 0 ? ($inProgress / $total) * 100 : 0;
                    $pendingPercent = $total > 0 ? ($pending / $total) * 100 : 0;
                @endphp
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Completadas</span>
                        <span>{{ $completed }} ({{ round($completedPercent) }}%)</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completedPercent }}%" 
                             aria-valuenow="{{ $completedPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>En curso</span>
                        <span>{{ $inProgress }} ({{ round($inProgressPercent) }}%)</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $inProgressPercent }}%" 
                             aria-valuenow="{{ $inProgressPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Pendientes</span>
                        <span>{{ $pending }} ({{ round($pendingPercent) }}%)</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $pendingPercent }}%" 
                             aria-valuenow="{{ $pendingPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs text-primary"></i> Acciones Rápidas
                </h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action">
                    <i class="fas fa-file-pdf text-danger me-2"></i> Generar Reporte PDF
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <i class="fas fa-envelope text-info me-2"></i> Enviar Recordatorio
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <i class="fas fa-print text-secondary me-2"></i> Imprimir Información
                </a>
                <a href="#" class="list-group-item list-group-item-action text-danger"
                   onclick="return confirm('¿Estás seguro de eliminar este restaurante? Esta acción no se puede deshacer.');">
                    <i class="fas fa-trash-alt me-2"></i> Eliminar Restaurante
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-map-marked-alt text-primary"></i> Ubicación
                </h5>
            </div>
            <div class="card-body">
                <div id="map" style="height: 300px; background-color: #f8f9fa; border-radius: 0.25rem;">
                    <div class="h-100 d-flex justify-content-center align-items-center text-muted">
                        <div class="text-center">
                            <i class="fas fa-map-marker-alt fa-3x mb-3"></i>
                            <p>Mapa de ubicación del restaurante</p>
                            <small>Integración con Google Maps o similar</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .progress {
        border-radius: 10px;
        overflow: hidden;
    }
    .progress-bar {
        transition: width 0.6s ease;
    }
</style>
@endpush

@push('scripts')
<!-- Aquí podrías incluir el script de Google Maps -->
<script>
    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Código para inicializar el mapa
        function initMap() {
            // Aquí iría el código para inicializar el mapa
            console.log('Mapa inicializado');
        }
        
        // Llamar a la función cuando el DOM esté listo
        initMap();
    });
</script>
@endpush
