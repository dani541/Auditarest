@extends('layouts.app')

@section('title', 'Detalle de Auditoría')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-0">
                            <i class="fas fa-clipboard-check"></i> Detalle de Auditoría
                        </h1>
                        <p class="text-muted mb-0">
                            {{ $restaurant->name }} - {{ $audit->scheduled_date->format('d/m/Y') }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('admin.restaurants.audits.create', $restaurant->id) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nueva Auditoría
                        </a>
                        <a href="{{ route('admin.restaurants.show', $restaurant->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Información General -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Información General</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <strong>Auditor:</strong> {{ $audit->auditor->name }}
                                        </li>
                                        <li class="mb-2">
                                            <strong>Fecha:</strong> {{ $audit->scheduled_date->format('d/m/Y') }}
                                        </li>
                                        <li class="mb-2">
                                            <strong>Estado:</strong>
                                            <span class="badge bg-{{ 
                                                $audit->status === 'completada' ? 'success' : 
                                                ($audit->status === 'pendiente' ? 'warning' : 'secondary') 
                                            }}">
                                                {{ ucfirst($audit->status) }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Resumen</h5>
                                    <div class="row">
                                        @php
                                            $items = $audit->verificationItems;
                                            $total = $items->count();
                                            $cumplen = $items->where('pivot.status', 'C')->count();
                                            $noCumplen = $items->where('pivot.status', 'IC')->count();
                                            $noAplica = $items->where('pivot.status', 'NA')->count();
                                            $porcentaje = $total > 0 ? round(($cumplen / $total) * 100, 2) : 0;
                                        @endphp
                                        
                                        <div class="col-md-3 text-center">
                                            <div class="display-4 text-primary">{{ $total }}</div>
                                            <div class="text-muted">Total</div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="display-4 text-success">{{ $cumplen }}</div>
                                            <div class="text-muted">Cumplen</div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="display-4 text-danger">{{ $noCumplen }}</div>
                                            <div class="text-muted">No Cumplen</div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="display-4 text-info">{{ $noAplica }}</div>
                                            <div class="text-muted">No Aplica</div>
                                        </div>
                                        
                                        <div class="col-12 mt-3">
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: {{ $porcentaje }}%" 
                                                     aria-valuenow="{{ $porcentaje }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ $porcentaje }}%
                                                </div>
                                            </div>
                                            <div class="text-center mt-1 small text-muted">
                                                Porcentaje de cumplimiento
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resultados por Categoría -->
                    @foreach($groupedItems as $category => $items)
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    {{ ucfirst(str_replace('_', ' ', $category)) }}
                                </h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60%">Verificación</th>
                                            <th class="text-center">Estado</th>
                                            <th>Observaciones / Medida Correctiva</th>
                                            @if($items->contains('pivot.temperature', '!=', null))
                                                <th>Temperatura</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                            @php
                                                $response = $item->pivot;
                                            @endphp
                                            <tr>
                                                <td>{{ $item->description }}</td>
                                                <td class="text-center">
                                                    @if($response->status === 'C')
                                                        <span class="badge bg-success">Cumple</span>
                                                    @elseif($response->status === 'IC')
                                                        <span class="badge bg-danger">No Cumple</span>
                                                    @else
                                                        <span class="badge bg-secondary">No Aplica</span>
                                                    @endif
                                                </td>
                                                <td>{{ $response->corrective_measure ?? 'N/A' }}</td>
                                                @if($response->temperature !== null)
                                                    <td>{{ $response->temperature }}°C</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach

                    <!-- Observaciones Generales -->
                    @if($audit->observations)
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Observaciones Generales</h5>
                            </div>
                            <div class="card-body">
                                {!! nl2br(e($audit->observations)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Evidencias -->
                    @if($audit->evidences->count() > 0)
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Evidencias</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($audit->evidences as $evidence)
                                        <div class="col-6 col-md-4 col-lg-3 mb-3">
                                            <div class="card h-100">
                                                @if(in_array($evidence->mime_type, ['image/jpeg', 'image/png', 'image/gif']))
                                                    <img src="{{ Storage::url($evidence->path) }}" 
                                                         class="card-img-top img-fluid" 
                                                         alt="Evidencia"
                                                         style="height: 150px; object-fit: cover;">
                                                @else
                                                    <div class="text-center py-4">
                                                        <i class="fas fa-file fa-4x text-muted mb-2"></i>
                                                        <p class="mb-0">{{ $evidence->original_name }}</p>
                                                    </div>
                                                @endif
                                                <div class="card-footer bg-transparent border-top-0">
                                                    <a href="{{ Storage::url($evidence->path) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-primary w-100">
                                                        <i class="fas fa-eye"></i> Ver
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <div>
                        <a href="#" class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print"></i> Imprimir
                        </a>
                        <a href="#" class="btn btn-outline-primary" id="downloadPdf">
                            <i class="fas fa-file-pdf"></i> Exportar PDF
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('admin.restaurants.audits.edit', [$restaurant->id, $audit->id]) }}" 
                           class="btn btn-outline-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-header {
            background-color: transparent !important;
            border-bottom: 1px solid #dee2e6 !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('downloadPdf').addEventListener('click', function(e) {
        e.preventDefault();
        // Aquí puedes implementar la generación de PDF con una librería como jsPDF o hacer una llamada al servidor
        alert('Funcionalidad de exportar a PDF. Se puede implementar con jsPDF o una llamada al servidor.');
    });
</script>
@endpush

@endsection
