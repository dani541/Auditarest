@extends('layouts.auditor')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Mis Auditorías</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Restaurante</th>
                        <th>Última Auditoría</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($audits as $audit)
                        <tr>
                            <td>{{ $audit->restaurant->name }}</td>
                            <td>{{ $audit->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $audit->status === 'completada' ? 'success' : 'warning' }}">
                                    {{ ucfirst($audit->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('audits.show', $audit->id) }}" 
                                   class="btn btn-sm btn-primary">
                                    Ver Detalles
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay auditorías realizadas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection