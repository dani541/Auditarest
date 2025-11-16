@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Detalles de Auditoría - {{ $restaurant->name }}</h1>
        <div>
            <a href="{{ route('admin.restaurants.audits.index', $restaurant->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al historial
            </a>
            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list"></i> Ver restaurantes
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Información de la Auditoría</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $audit->id }}</p>
                    <p><strong>Evento:</strong> 
                        <span class="badge bg-{{ $audit->event === 'created' ? 'success' : ($audit->event === 'updated' ? 'warning' : 'danger') }}">
                            {{ ucfirst($audit->event) }}
                        </span>
                    </p>
                    <p><strong>Usuario:</strong> {{ $audit->user->name ?? 'Sistema' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Fecha y Hora:</strong> {{ $audit->created_at->format('d/m/Y H:i:s') }}</p>
                    <p><strong>Dirección IP:</strong> {{ $audit->ip_address }}</p>
                    <p><strong>Agente de Usuario:</strong> {{ $audit->user_agent }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Cambios Realizados</h5>
        </div>
        <div class="card-body">
            @if($audit->event === 'updated')
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Campo</th>
                                <th>Valor Anterior</th>
                                <th>Nuevo Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($audit->getModified() as $field => $values)
                                <tr>
                                    <td><strong>{{ $field }}</strong></td>
                                    <td>{{ is_array($values['old']) ? json_encode($values['old']) : $values['old'] }}</td>
                                    <td>{{ is_array($values['new']) ? json_encode($values['new']) : $values['new'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif($audit->event === 'created' || $audit->event === 'deleted')
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Campo</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($audit->getModified() as $field => $values)
                                <tr>
                                    <td><strong>{{ $field }}</strong></td>
                                    <td>{{ is_array($values) ? json_encode($values) : $values }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .card {
        margin-bottom: 2rem;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.9em;
        padding: 0.4em 0.8em;
    }
</style>
@endsection
