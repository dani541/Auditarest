@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-utensils text-primary me-2"></i>Gestión de Restaurantes
            </h1>
            <p class="text-muted mb-0">Administra los restaurantes registrados en el sistema</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.restaurants.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Nuevo Restaurante
            </a>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4">
        <div class="col-12 col-md-6 col-xl-3 mb-4">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Total Restaurantes</h6>
                            <h2 class="mb-0">{{ $restaurants->total() }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-store text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

   
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Buscar restaurantes...">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <select class="form-select" id="filterCity">
                        <option value="">Todas las ciudades</option>
                        @foreach($cities ?? [] as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="sortBy">
                        <option value="name_asc">Nombre (A-Z)</option>
                        <option value="name_desc">Nombre (Z-A)</option>
                        <option value="recent">Más recientes</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de restaurantes -->
    <div class="row" id="restaurantsContainer">
        @forelse($restaurants as $restaurant)
        <div class="col-12 col-md-6 col-xl-4 mb-4" data-created-at="{{ $restaurant->created_at }}">
            <div class="card h-100 shadow-sm hover-shadow-lg transition-all">
                <div class="position-relative">
                    <div class="position-absolute top-0 end-0 m-3">
                       <!-- <span class="badge bg-{{ $restaurant->is_active ? 'success' : 'secondary' }} rounded-pill">
                            {{ $restaurant->is_active ? 'Activo' : 'Inactivo' }}
                        </span> -->
                    </div>
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="fas fa-store fa-4x text-muted"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-store text-primary me-2"></i>{{ $restaurant->name }}
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary rounded-circle" type="button" id="dropdownMenuButton{{ $restaurant->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $restaurant->id }}">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.restaurants.show', $restaurant->id) }}">
                                        <i class="fas fa-eye me-2"></i>Ver detalles
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.restaurants.edit', $restaurant->id) }}">
                                        <i class="fas fa-edit me-2"></i>Editar
                                    </a>
                                </li>
                                <li>
                                   <a class="dropdown-item" href="{{ route('audits.create') }}?restaurant_id={{ $restaurant->id }}">
                                        <i class="fas fa-history me-2"></i>Ver auditorías
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.restaurants.destroy', $restaurant->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Estás seguro de eliminar este restaurante?')">
                                            <i class="fas fa-trash-alt me-2"></i>Eliminar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <span class="text-muted">{{ $restaurant->address }}, {{ $restaurant->city }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-user-tie text-muted me-2"></i>
                            <span class="text-muted">{{ $restaurant->contact_name }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <a href="tel:{{ $restaurant->contact_phone }}" class="text-decoration-none">{{ $restaurant->contact_phone }}</a>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <i class="fas fa-envelope text-muted me-2"></i>
                            <a href="mailto:{{ $restaurant->contact_email }}" class="text-decoration-none text-truncate" style="max-width: 200px;">
                                {{ $restaurant->contact_email }}
                            </a>
                        </div>
                    </div>
                    
                    @if($restaurant->audits->count() > 0)
                    <div class="border-top pt-3 mt-3">
                        <h6 class="text-uppercase text-muted small mb-2">
                            <i class="fas fa-history me-1"></i> Última auditoría
                        </h6>
                        @php
                            $lastAudit = $restaurant->audits->sortByDesc('created_at')->first();
                        @endphp
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-{{ 
                                $lastAudit->event === 'created' ? 'success' : 
                                ($lastAudit->event === 'updated' ? 'info' : 'warning') 
                            }}">
                                {{ ucfirst($lastAudit->event) }}
                            </span>
                            <small class="text-muted">
                                {{ $lastAudit->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-white border-top-0 pt-0">
                    <div class="d-grid">
                        <a href="{{ route('admin.restaurants.show', $restaurant->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> Ver detalles
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h4>No hay restaurantes registrados</h4>
                    <p class="text-muted mb-4">Comienza agregando tu primer restaurante al sistema</p>
                    <a href="{{ route('admin.restaurants.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Agregar Restaurante
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>


    @if($restaurants->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Paginación de restaurantes">
                {{ $restaurants->links() }}
            </nav>
        </div>
    </div>
    @endif
</div>


<div class="d-block d-lg-none fixed-bottom text-end mb-4 me-4">
    <a href="{{ route('admin.restaurants.create') }}" 
       class="btn btn-primary btn-lg rounded-circle shadow-lg d-inline-flex align-items-center justify-content-center"
       style="width: 56px; height: 56px;"
       data-bs-toggle="tooltip" 
       data-bs-placement="left"
       title="Agregar restaurante">
        <i class="fas fa-plus"></i>
    </a>
</div>

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border: none;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
    }
    .card-img-top {
        object-fit: cover;
        height: 160px;
    }
    .dropdown-menu {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        border-radius: 0.5rem;
    }
    .dropdown-item:active {
        background-color: #f8f9fa;
        color: #0d6efd;
    }
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush
<!--
@push('scripts')
<script>
    // Activar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Funcionalidad de búsqueda
        const searchInput = document.getElementById('searchInput');
        const filterCity = document.getElementById('filterCity');
        const sortBy = document.getElementById('sortBy');
        const restaurantsContainer = document.getElementById('restaurantsContainer');

        function filterRestaurants() {
            const searchTerm = searchInput.value.toLowerCase();
            const cityFilter = filterCity.value.toLowerCase();
            const sortValue = sortBy.value;
            
            // Aquí iría la lógica para filtrar y ordenar los restaurantes
            // Puedes implementar esto con AJAX o con JavaScript puro
            // dependiendo de tus necesidades
            
            console.log('Filtrando por:', { searchTerm, cityFilter, sortValue });
            // Ejemplo de cómo podrías implementar el filtrado en el cliente
            // (asumiendo que todos los datos ya están cargados en la página)
            const cards = document.querySelectorAll('.col-12.col-md-6.col-xl-4');
            
            cards.forEach(card => {
                const name = card.querySelector('.card-title').textContent.toLowerCase();
                const city = card.querySelector('.fa-map-marker-alt').parentElement.textContent.toLowerCase();
                const matchesSearch = name.includes(searchTerm);
                const matchesCity = cityFilter === '' || city.includes(cityFilter);
                
                if (matchesSearch && matchesCity) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Event listeners para los filtros
        searchInput.addEventListener('input', filterRestaurants);
        filterCity.addEventListener('change', filterRestaurants);
        sortBy.addEventListener('change', filterRestaurants);
    });
</script>
@endpush-->

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Referencias a los elementos del DOM
        const searchInput = document.getElementById('searchInput');
        const filterCity = document.getElementById('filterCity');
        const sortBy = document.getElementById('sortBy');
        const restaurantsContainer = document.getElementById('restaurantsContainer');
        let restaurantCards = Array.from(document.querySelectorAll('#restaurantsContainer > .col-12'));

        // Función para extraer la ciudad del texto de ubicación
        function extractCity(locationText) {
            // Eliminar espacios en blanco y convertir a minúsculas
            const text = locationText.trim().toLowerCase();
            // Dividir por comas y obtener el último elemento (la ciudad)
            const parts = text.split(',');
            return parts[parts.length - 1].trim();
        }

        // Función para formatear la fecha
        function formatDate(dateString) {
            // Si la fecha ya es un objeto Date, devolverlo directamente
            if (dateString instanceof Date) return dateString;
            
            // Intentar convertir la cadena de fecha a un objeto Date
            const date = new Date(dateString);
            
            // Si la conversión falla, devolver la fecha actual
            return isNaN(date.getTime()) ? new Date() : date;
        }

        function filterAndSortRestaurants() {
            const searchTerm = searchInput.value.toLowerCase();
            const cityFilter = filterCity.value.toLowerCase();
            const sortValue = sortBy.value;

            // Filtrar restaurantes
            const filteredCards = restaurantCards.filter(card => {
                const name = card.querySelector('.card-title').textContent.toLowerCase();
                const locationElement = card.querySelector('.fa-map-marker-alt').parentNode;
                const locationText = locationElement.textContent;
                const city = extractCity(locationText);
                
                const matchesSearch = name.includes(searchTerm);
                const matchesCity = cityFilter === '' || city.includes(cityFilter);
                
                // Para depuración
                console.log('Restaurante:', name, '| Ciudad extraída:', city, '| Filtro ciudad:', cityFilter, '| Coincide:', matchesCity);
                
                return matchesSearch && matchesCity;
            });

            // Ordenar restaurantes
            filteredCards.sort((a, b) => {
                const nameA = a.querySelector('.card-title').textContent.toLowerCase();
                const nameB = b.querySelector('.card-title').textContent.toLowerCase();
                
                // Obtener fechas de creación
                const dateA = formatDate(a.getAttribute('data-created-at'));
                const dateB = formatDate(b.getAttribute('data-created-at'));

                switch(sortValue) {
                    case 'name_asc':
                        return nameA.localeCompare(nameB);
                    case 'name_desc':
                        return nameB.localeCompare(nameA);
                    case 'recent':
                        // Ordenar por fecha más reciente primero
                        return dateB - dateA;
                    default:
                        return 0;
                }
            });

            // Actualizar la vista
            restaurantsContainer.innerHTML = '';
            filteredCards.forEach(card => {
                restaurantsContainer.appendChild(card);
            });
        }

        // Event listeners para los filtros
        searchInput.addEventListener('input', filterAndSortRestaurants);
        filterCity.addEventListener('change', filterAndSortRestaurants);
        sortBy.addEventListener('change', filterAndSortRestaurants);

        // Llamar a la función al cargar
        filterAndSortRestaurants();
    });
</script>
@endpush
@endsection
