@extends('layouts.auditor')

@section('content')
<div class="container py-4">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Auditoría #{{ $audit->id }}</h1>
        <div>
            <a href="{{ route('admin.audits.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('audits.export-pdf', $audit->id) }}" class="btn btn-primary">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
        </div>
    </div>

    <!-- Información General -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Información General</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-2">
                        <strong><i class="fas fa-utensils me-2"></i>Restaurante:</strong>
                        <span class="float-end">{{ $audit->restaurant->name ?? 'N/A' }}</span>
                    </p>
                    <p class="mb-2">
                        <strong><i class="far fa-calendar-alt me-2"></i>Fecha:</strong>
                        <span class="float-end">{{ $audit->created_at ? \Carbon\Carbon::parse($audit->date)->format('d/m/Y') : 'N/A' }}</span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2">
                        <strong><i class="fas fa-user-tie me-2"></i>Auditor:</strong>
                        <span class="float-end">{{ $audit->auditor ?? 'N/A' }}</span>
                    </p>
                    <p class="mb-2">
                        <strong><i class="fas fa-user-shield me-2"></i>Supervisor:</strong>
                        <span class="float-end">{{ $audit->supervisor ?? 'N/A' }}</span>
                    </p>
                </div>
            </div>

            @if($audit->general_notes)
            <div class="mt-3 pt-3 border-top">
                <h6 class="text-muted mb-3"><i class="fas fa-clipboard me-2"></i>Observaciones Generales</h6>
                <div class="bg-light p-3 rounded">
                    {!! nl2br(e($audit->general_notes)) !!}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Sección de Infraestructura -->
    @if($audit->infrastructure)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-building me-2"></i> 1. Infraestructura</h5>
            <div class="mt-2">
                <span class="badge bg-{{ $audit->infrastructure->percentage >= 70 ? 'success' : ($audit->infrastructure->percentage >= 40 ? 'warning' : 'danger') }}">
                    Puntuación: {{ $audit->infrastructure->total_score }} ({{ number_format($audit->infrastructure->percentage, 2) }}%)
                </span>
            </div>
        </div>
        <div class="card-body">
            @include('admin.audits.partials.inspection-table', [
                'data' => $audit->infrastructure,
                'exclude' => ['id', 'audit_id', 'created_at', 'updated_at', 'deleted_at', 'total_score', 'percentage']
            ])
            
            @if(!empty(trim($audit->infrastructure->additional_notes ?? '')))
            <div class="mt-3 pt-3 border-top">
                <h6 class="text-muted"><i class="fas fa-clipboard me-2"></i>Observaciones Adicionales</h6>
                <div class="bg-light p-3 rounded">
                    {!! nl2br(e($audit->infrastructure->additional_notes)) !!}
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Sección de Maquinaria -->
    @if($audit->machinery)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-tools me-2"></i> 2. Maquinaria</h5>
            <div class="mt-2">
                <span class="badge bg-{{ $audit->machinery->percentage >= 70 ? 'success' : ($audit->machinery->percentage >= 40 ? 'warning' : 'danger') }}">
                    Puntuación: {{ $audit->machinery->total_score }} ({{ number_format($audit->machinery->percentage, 2) }}%)
                </span>
            </div>
        </div>
        <div class="card-body">
            @include('admin.audits.partials.inspection-table', [
                'data' => $audit->machinery,
                'exclude' => ['id', 'audit_id', 'created_at', 'updated_at', 'deleted_at', 'total_score', 'percentage', 'maintenance_notes', 'last_maintenance_date']
            ])
            
            @if(!empty(trim($audit->machinery->maintenance_notes ?? '')) || !empty($audit->machinery->last_maintenance_date))
            <div class="mt-3 pt-3 border-top">
                @if(!empty(trim($audit->machinery->maintenance_notes ?? '')))
                <h6 class="text-muted"><i class="fas fa-clipboard me-2"></i>Notas de Mantenimiento</h6>
                <div class="bg-light p-3 rounded mb-3">
                    {!! nl2br(e($audit->machinery->maintenance_notes)) !!}
                </div>
                @endif
                
                @if(!empty($audit->machinery->last_maintenance_date))
                <p class="text-muted mb-0">
                    <i class="far fa-calendar-alt me-2"></i>Último mantenimiento: {{ \Carbon\Carbon::parse($audit->machinery->last_maintenance_date)->format('d/m/Y') }}
                </p>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Sección de Higiene -->
    @if($audit->hygiene)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-hand-holding-water me-2"></i> 3. Higiene</h5>
            <div class="mt-2">
                <span class="badge bg-{{ $audit->hygiene->percentage >= 70 ? 'success' : ($audit->hygiene->percentage >= 40 ? 'warning' : 'danger') }}">
                    Puntuación: {{ $audit->hygiene->total_score }} ({{ number_format($audit->hygiene->percentage, 2) }}%)
                </span>
            </div>
        </div>
        <div class="card-body">
            @include('admin.audits.partials.inspection-table', [
                'data' => $audit->hygiene,
                'exclude' => ['id', 'audit_id', 'created_at', 'updated_at', 'deleted_at', 'total_score', 'percentage']
            ])
        </div>
    </div>
    @endif

    <!-- Sección de Firma y Sello -->
  <!--  <div class="card shadow-sm">
        <div class="card-body text-center">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="border-bottom pb-2 mb-2">
                        <strong>Firma del Auditor</strong>
                    </div>
                    <div style="height: 80px;"></div>
                    <div class="text-muted small">
                        {{ $audit->auditor ?? 'Nombre del Auditor' }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border-bottom pb-2 mb-2">
                        <strong>Sello de la Empresa</strong>
                    </div>
                    <div style="height: 80px;"></div>
                    <div class="text-muted small">
                        {{ $audit->restaurant->name ?? 'Nombre del Restaurante' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->
@endsection