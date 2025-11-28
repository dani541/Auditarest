@extends('layouts.app')

@section('title', 'Seleccionar Restaurante para Auditoría')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Seleccionar Restaurante para Nueva Auditoría</h5>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="list-group">
                        @forelse($restaurants as $restaurant)
                            <a href="{{ route('admin.audits.create', $restaurant) }}" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span>{{ $restaurant->name }}</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        @empty
                            <div class="alert alert-warning mb-0">
                                No hay restaurantes disponibles para auditar.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="card-footer text-right">
                    <a href="{{ route('admin.audits.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
