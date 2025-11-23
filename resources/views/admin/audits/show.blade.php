@extends('layouts.app')

@section('title', 'Detalle de Auditoría')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Auditoría #{{ $audit->id }}</h1>
        <div>
            <a href="{{ route('admin.audits.edit', $audit) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('admin.audits.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Información General</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Restaurante:</strong> {{ $audit->restaurant->name }}</p>
                    <p><strong>Auditor:</strong> {{ $audit->auditor }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Fecha:</strong> {{ $audit->audit_date->format('d/m/Y') }}</p>
                    <p><strong>Estado:</strong> 
                        <span class="badge {{ $audit->status === 'completada' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($audit->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @foreach($audit->answers->groupBy('question.category') as $categoryName => $answers)
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-light">
                <h6 class="m-0 font-weight-bold text-primary">{{ $categoryName }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 70%">Pregunta</th>
                                <th class="text-center" style="width: 15%">Cumple</th>
                                <th>Incidencia / Medida Correctora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($answers as $answer)
                                <tr>
                                    <td>{{ $answer->question->question }}</td>
                                    <td class="text-center">
                                        @if($answer->complies)
                                            <span class="badge bg-success">C</span>
                                        @else
                                            <span class="badge bg-danger">IC</span>
                                        @endif
                                    </td>
                                    <td>{{ $answer->incidence ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    @if($audit->observations)
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-light">
                <h6 class="m-0 font-weight-bold text-primary">Observaciones Generales</h6>
            </div>
            <div class="card-body">
                <p>{{ $audit->observations }}</p>
            </div>
        </div>
    @endif
</div>
@endsection