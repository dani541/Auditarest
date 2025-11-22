@extends('layouts.app')

@section('title', 'Listado de Auditorías')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Listado de Auditorías</h1>
        <a href="{{ route('admin.audits.select-restaurant') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Auditoría
        </a>
    </div>

    @if($restaurants->count() > 0)
        @foreach($restaurants as $restaurant)
            @if($restaurant->audits->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">{{ $restaurant->name }}</h6>
                        <a href="{{ route('admin.audits.create', $restaurant) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Nueva Auditoría
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
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
                                        <td>{{ $audit->auditor->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $audit->status === 'completada' ? 'bg-success' : 'bg-warning' }}">
                                                {{ ucfirst($audit->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.audits.show', $audit) }}" 
                                               class="btn btn-sm btn-info" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" 
                                               class="btn btn-sm btn-secondary" title="Descargar PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @else
        <div class="alert alert-info">No hay restaurantes con auditorías registradas.</div>
    @endif
</div>
@endsection