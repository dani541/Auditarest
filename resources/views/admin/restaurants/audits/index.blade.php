@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Historial de Auditorías - {{ $restaurant->name }}</h1>
        <a href="{{ route('admin.restaurants.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la lista
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($audits->isEmpty())
                <div class="alert alert-info">No hay registros de auditoría para este restaurante.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Evento</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($audits as $audit)
                                <tr>
                                    <td>{{ $audit->id }}</td>
                                    <td>
                                        <span class="badge bg-{{ $audit->event === 'created' ? 'success' : ($audit->event === 'updated' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($audit->event) }}
                                        </span>
                                    </td>
                                    <td>{{ $audit->user->name ?? 'Sistema' }}</td>
                                    <td>{{ $audit->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.restaurants.audits.show', ['restaurant' => $restaurant->id, 'audit' => $audit->id]) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Ver detalles
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $audits->links() }}
            @endif
        </div>
    </div>
</div>
@endsection
