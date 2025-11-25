@extends('layouts.auditor')

@section('title', 'Detalle de Auditoría')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Auditoría #{{ $audit->id }}</h1>
        <div>
            <a href="{{ route('audits.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Información General -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Datos de la Auditoría</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Restaurante:</strong> {{ $audit->restaurant->name ?? 'No especificado' }}</p>
                    <p><strong>Auditor:</strong> {{ $audit->auditor ?? 'No asignado' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Fecha:</strong> {{ $audit->date ? $audit->date->format('d/m/Y') : 'No especificada' }}</p>
                    <p><strong>Supervisor:</strong> {{ $audit->supervisor ?? 'No especificado' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Estado:</strong> 
                        <span class="badge {{ $audit->status === 'completada' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($audit->status ?? 'pendiente') }}
                        </span>
                    </p>
                </div>
            </div>
            @if($audit->general_notes)
                <div class="row mt-3">
                    <div class="col-12">
                        <p><strong>Notas Generales:</strong></p>
                        <p>{{ $audit->general_notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Sección 1: Infraestructura -->
    @if($audit->infrastructure)
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">I. 🏗️ Infraestructura (Infrastructure)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50%">Elemento de Verificación</th>
                            <th class="text-center" style="width: 15%">Estado</th>
                            <th style="width: 35%">Incidencia / Medida Correctora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $infrastructureItems = [
                                'floor_condition' => 'Suelo en buen estado',
                                'walls_condition' => 'Paredes en buen estado',
                                'ceiling_condition' => 'Techos en buen estado',
                                'lighting_condition' => 'Lámparas y luminarias en buen estado',
                                'ventilation_condition' => 'Ventilación adecuada',
                                'sanitary_condition' => 'Condiciones sanitarias adecuadas',
                                'equipment_condition' => 'Equipos en buen estado',
                                'refrigeration_condition' => 'Refrigeración en buen estado',
                                'food_storage_condition' => 'Almacenamiento de alimentos adecuado',
                                'waste_management_condition' => 'Manejo de residuos adecuado'
                            ];
                        @endphp

                        @foreach($infrastructureItems as $field => $label)
                            @php
                                $condition = $audit->infrastructure->$field ?? null;
                                $notesField = str_replace('_condition', '_notes', $field);
                                $notes = $audit->infrastructure->$notesField ?? '';
                            @endphp
                            <tr>
                                <td>{{ $label }}</td>
                                <td class="text-center">
                                    @if($condition === 1)
                                        <span class="badge bg-success">Conforme</span>
                                    @elseif($condition === 0)
                                        <span class="badge bg-danger">No Conforme</span>
                                    @else
                                        <span class="badge bg-secondary">No evaluado</span>
                                    @endif
                                </td>
                                <td>{{ $notes ?: 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Sección 2: Maquinaria y Equipos -->
    @if($audit->machinery)
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">II. 🍳 Maquinaria y Equipos (Machinery and Equipment)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50%">Elemento de Verificación</th>
                            <th class="text-center" style="width: 15%">Estado</th>
                            <th style="width: 35%">Incidencia / Medida Correctora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $machineryItems = [
                                'equipment_condition' => 'Equipos en buen estado',
                                'maintenance_status' => 'Mantenimiento al día',
                                'safety_devices' => 'Dispositivos de seguridad funcionando',
                                'calibration_status' => 'Equipos calibrados',
                                'operational_status' => 'Estado operativo adecuado',
                                'cleaning_status' => 'Limpieza de equipos',
                                'temperature_control' => 'Control de temperatura adecuado',
                                'safety_measures' => 'Medidas de seguridad implementadas',
                                'documentation' => 'Documentación en regla',
                                'training' => 'Personal capacitado en el uso de equipos'
                            ];
                        @endphp

                        @foreach($machineryItems as $field => $label)
                            @php
                                $condition = $audit->machinery->$field ?? null;
                                $notesField = str_replace('_condition', '_notes', $field);
                                $notes = $audit->machinery->$notesField ?? '';
                            @endphp
                            <tr>
                                <td>{{ $label }}</td>
                                <td class="text-center">
                                    @if($condition === 1)
                                        <span class="badge bg-success">Conforme</span>
                                    @elseif($condition === 0)
                                        <span class="badge bg-danger">No Conforme</span>
                                    @else
                                        <span class="badge bg-secondary">No evaluado</span>
                                    @endif
                                </td>
                                <td>{{ $notes ?: 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Sección 3: Buenas Prácticas y Condiciones Higiénicas -->
    @if($audit->hygiene)
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">III. ✅ Buenas Prácticas y Condiciones Higiénicas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50%">Elemento de Verificación</th>
                            <th class="text-center" style="width: 15%">Estado</th>
                            <th style="width: 35%">Incidencia / Medida Correctora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $hygieneItems = [
                                'uniforms_condition' => 'Uso de uniformes adecuados',
                                'hand_washing_condition' => 'Lavado de manos correcto',
                                'hygiene_kits_condition' => 'Kits de higiene disponibles',
                                'food_handling_condition' => 'Manejo adecuado de alimentos',
                                'gloves_usage' => 'Uso de guantes',
                                'hair_restraint_usage' => 'Uso de cofias/redes para el cabello',
                                'cleaning_supplies_condition' => 'Suministros de limpieza adecuados',
                                'sanitization_procedures' => 'Procedimientos de saneamiento',
                                'food_storage_condition' => 'Almacenamiento de alimentos adecuado',
                                'chemical_storage_condition' => 'Almacenamiento de químicos adecuado'
                            ];
                        @endphp

                        @foreach($hygieneItems as $field => $label)
                            @php
                                $condition = $audit->hygiene->$field ?? null;
                                $notesField = str_replace('_condition', '_notes', $field);
                                $notes = $audit->hygiene->$notesField ?? '';
                            @endphp
                            <tr>
                                <td>{{ $label }}</td>
                                <td class="text-center">
                                    @if($condition === 1)
                                        <span class="badge bg-success">Conforme</span>
                                    @elseif($condition === 0)
                                        <span class="badge bg-danger">No Conforme</span>
                                    @else
                                        <span class="badge bg-secondary">No evaluado</span>
                                    @endif
                                </td>
                                <td>{{ $notes ?: 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Sección de Notas Adicionales -->
    @if($audit->additional_notes)
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">IV. 📝 Notas Adicionales</h6>
        </div>
        <div class="card-body">
            <p>{{ $audit->additional_notes }}</p>
        </div>
    </div>
    @endif

    <!-- Botón para generar PDF -->
    <div class="text-center mt-4">
        <a href="{{ url('admin/audits/' . $audit->id . '/export-pdf') }}" 
           class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Generar PDF
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .progress {
        height: 1.5rem;
        margin-top: 0.5rem;
    }
    .progress-bar {
        font-size: 0.9rem;
        line-height: 1.5rem;
    }
    .badge {
        font-size: 0.9rem;
        padding: 0.35em 0.65em;
    }
    .card-header h6 {
        margin: 0;
    }
    table th {
        white-space: nowrap;
    }
</style>
@endpush