@extends('layouts.app')

@section('title', 'Todas las Auditorías')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Todas las Auditorías</h1>
        <a href="{{ route('audits.select-restaurant') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Auditoría
        </a>
    </div>

    @foreach($restaurants as $restaurant)
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ $restaurant->name }}
                <span class="badge bg-info text-white">
                    {{ $restaurant->audits->count() }} auditorías
                </span>
            </h6>
        </div>
        
        @if($restaurant->audits->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Auditor</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($restaurant->audits as $audit)
                    <tr>
                        <td>{{ $audit->id }}</td>
                        <td>{{ $audit->scheduled_date->format('d/m/Y') }}</td>
                        <td>{{ $audit->auditor->name ?? 'Sin asignar' }}</td>
                        <td>
                            <span class="badge {{ $audit->status === 'completada' ? 'bg-success' : 'bg-warning' }}">
                                {{ ucfirst($audit->status) }}
                            </span>
                        </td>
                        <td class="text-nowrap">
                            <a href="{{ route('audits.edit', $audit) }}" 
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('audits.destroy', $audit) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta auditoría?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="card-body">
            <div class="alert alert-info mb-0">
                Este restaurante no tiene auditorías registradas.
            </div>
        </div>
        @endif
    </div>
    @endforeach
</div>
@endsection