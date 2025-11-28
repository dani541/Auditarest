@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-users me-2"></i>Gestión de Usuarios
        </h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Crear Usuario
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-users me-2"></i> Lista de Usuarios
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Restaurante Asignado</th>
                      <!--  <th>Restaurantes Auditados</th>-->
                       <!-- <th>Estado</th>-->
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->role->name === 'admin' ? 'bg-primary' : ($user->role->name === 'editor' ? 'bg-info' : 'bg-secondary') }}">
                                    {{ $user->role->name }}
                                </span>
                            </td>
                            <td>
                                @if($user->restaurant)
                                    {{ $user->restaurant->name }}
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </td>
                          <!--  <td>
                                
                                @if($user->role->name === 'editor')
                                    <span class="badge bg-success">
                                        {{ $user->audited_restaurants_count }} restaurantes
                                    </span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>-->
                           <!-- <td>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>-->
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <!-- Botón Editar -->
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Botón Eliminar con confirmación -->
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-users-slash fa-2x mb-3 text-muted"></i>
                                <p class="mb-0">No hay usuarios registrados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@include('admin.users._delete_modal')

@endsection

@push('scripts')
<script>
    // Initialize tooltips and delete modal
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Handle delete modal
        var deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var userId = button.getAttribute('data-user-id');
                var userName = button.getAttribute('data-user-name');
                
                var modalTitle = deleteModal.querySelector('.modal-title');
                var deleteForm = deleteModal.querySelector('form');
                
                modalTitle.textContent = 'Eliminar Usuario: ' + userName;
                deleteForm.action = '{{ url("admin/users") }}/' + userId;
            });
        }
    });
</script>
@endpush
