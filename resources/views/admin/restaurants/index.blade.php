<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Restaurantes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="container mt-4">
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-utensils"></i> Gestión de Restaurantes
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.restaurants.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Nuevo Restaurante
        </a>
    </div>
</div>

<div class="row">
    @forelse($restaurants as $restaurant)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-store text-primary"></i> {{ $restaurant->name }}
                </h5>
                <div class="btn-group">
                    <a href="{{ route('admin.restaurants.edit', $restaurant->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.restaurants.destroy', $restaurant->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar este restaurante?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <p class="mb-1"><i class="fas fa-map-marker-alt text-muted me-2"></i>{{ $restaurant->address }}</p>
                <p class="mb-1"><i class="fas fa-city text-muted me-2"></i>{{ $restaurant->city }}</p>
                <p class="mb-1"><i class="fas fa-phone text-muted me-2"></i>{{ $restaurant->contact_phone }}</p>
                <p class="mb-1"><i class="fas fa-envelope text-muted me-2"></i>{{ $restaurant->contact_email }}</p>
                <p class="mb-0"><i class="fas fa-user text-muted me-2"></i>Contacto: {{ $restaurant->contact_name }}</p>
            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('admin.restaurants.show', $restaurant->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i> Ver Detalles
                </a>
            </div>
                <span class="badge bg-info">{{ $restaurant->city }}</span>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item px-0">
                        <i class="fas fa-map-marker-alt text-muted me-2"></i>
                        <strong>Dirección:</strong> {{ $restaurant->address }}
                    </li>
                    <li class="list-group-item px-0">
                        <i class="fas fa-user text-muted me-2"></i>
                        <strong>Contacto:</strong> {{ $restaurant->contact_name }}
                    </li>
                    <li class="list-group-item px-0">
                        <i class="fas fa-phone text-muted me-2"></i>
                        <strong>Teléfono:</strong> {{ $restaurant->contact_phone }}
                    </li>
                    <li class="list-group-item px-0">
                        <i class="fas fa-envelope text-muted me-2"></i>
                        <strong>Email:</strong> {{ $restaurant->contact_email }}
                    </li>
                </ul>

                @if($restaurant->audits->count() > 0)
                <div class="audits mt-3">
                    <h6 class="text-muted">
                        <i class="fas fa-clipboard-list me-2"></i>Últimas Auditorías
                    </h6>
                    <div class="list-group">
                        @foreach($restaurant->audits->take(2) as $audit)
                        <div class="list-group-item list-group-item-action py-2">
                            <div class="d-flex w-100 justify-content-between">
                                <span class="badge bg-{{ 
                                    $audit->status == 'completada' ? 'success' : 
                                    ($audit->status == 'en_curso' ? 'warning' : 'secondary') 
                                }}">
                                    {{ ucfirst($audit->status) }}
                                </span>
                                <small>{{ $audit->scheduled_date->format('d/m/Y') }}</small>
                            </div>
                            <small class="text-muted">
                                {{ $audit->notes ? Str::limit($audit->notes, 50) : 'Sin notas' }}
                            </small>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="alert alert-warning py-2 mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Este restaurante no tiene auditorías registradas.
                </div>
                @endif
            </div>
            <div class="card-footer bg-white">
                <div class="btn-group w-100">
                    <a href="{{ route('admin.restaurants.show', $restaurant->id) }}" 
                       class="btn btn-sm btn-outline-primary"
                       data-bs-toggle="tooltip" 
                       title="Ver detalles">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.restaurants.edit', $restaurant->id) }}" 
                       class="btn btn-sm btn-outline-secondary"
                       data-bs-toggle="tooltip" 
                       title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('admin.restaurants.audits.index', $restaurant->id) }}" 
                       class="btn btn-sm btn-outline-info"
                       data-bs-toggle="tooltip" 
                       title="Ver auditorías">
                        <i class="fas fa-clipboard-list"></i>
                    </a>
                    <form action="{{ route('admin.restaurants.destroy', $restaurant->id) }}" 
                          method="POST" 
                          class="d-inline"
                          onsubmit="return confirm('¿Estás seguro de eliminar este restaurante? Esta acción no se puede deshacer.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="tooltip" 
                                title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            No se encontraron restaurantes registrados.
        </div>
    </div>
    @endforelse
</div>

@if($restaurants->hasPages())
<div class="row">
    <div class="col-12">
        {{ $restaurants->links() }}
    </div>
</div>
@endif

<!-- Botón flotante para crear restaurante (solo móvil) -->
<div class="d-block d-md-none fixed-bottom text-end mb-4 me-4">
    <a href="{{ route('admin.restaurants.create') }}" 
       class="btn btn-primary btn-lg rounded-circle shadow-lg"
       data-bs-toggle="tooltip" 
       title="Agregar restaurante">
        <i class="fas fa-plus"></i>
    </a>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

@push('scripts')
<script>
    // Activar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
