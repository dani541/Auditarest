@extends('layouts.app') 

@section('title', 'Admin Dashboard - AuditaRest')

@section('content')
<div class="container mx-auto p-4 md:p-8">
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 border-b-2 pb-2">Panel de Administración de AuditaRest</h1>
        <p class="text-gray-600 mt-2">Gestión centralizada de recursos y monitoreo del sistema.</p>
    </header>

    <h2>Estadísticas</h2>
    {{-- Bloque de Estadísticas (Requiere datos pasados por el controlador) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        {{-- Card 1: Restaurantes --}}
        <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-green-500 hover:shadow-xl transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Restaurantes Registrados</p>
                    {{-- Aquí se mostraría la variable $restaurantCount --}}
                    <p class="text-3xl font-bold text-gray-900">120</p> 
                </div>
                <i class="fas fa-utensils text-green-500 text-3xl"></i>
            </div>
            <a href="{{ route('admin.restaurants.index') }}" class="text-sm text-green-600 hover:text-green-700 mt-3 block">Ver Gestión</a>
        </div>

        <h3>Auditores Activos</h3>
        {{-- Card 2: Auditores Activos --}}
        <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-blue-500 hover:shadow-xl transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Auditores Activos</p>
                    {{-- Aquí se mostraría la variable $auditorCount --}}
                    <p class="text-3xl font-bold text-gray-900">15</p>
                </div>
                <i class="fas fa-user-tie text-blue-500 text-3xl"></i>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-700 mt-3 block">Ver Usuarios</a>
        </div>


        <h3>Auditorias pendientes</h3>
        {{-- Card 3: Auditorías Pendientes --}}
        <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-yellow-500 hover:shadow-xl transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Auditorías Pendientes</p>
                    {{-- Aquí se mostraría la variable $pendingAuditCount --}}
                    <p class="text-3xl font-bold text-gray-900">24</p>
                </div>
                <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl"></i>
            </div>
            <a href="{{ route('admin.audits.index') }}" class="text-sm text-yellow-600 hover:text-yellow-700 mt-3 block">Ver Auditorías</a>
        </div>

        {{-- Card 4: Informes Generados (Ejemplo) --}}
        <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-purple-500 hover:shadow-xl transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Informes Publicados</p>
                    {{-- Aquí se mostraría la variable $reportCount --}}
                    <p class="text-3xl font-bold text-gray-900">450</p>
                </div>
                <i class="fas fa-file-pdf text-purple-500 text-3xl"></i>
            </div>
            <a href="{{ route('admin.audits.index') }}" class="text-sm text-purple-600 hover:text-purple-700 mt-3 block">Ver Informes</a>
        </div>
    </div>
    
    {{-- Bloque de Navegación Rápida a Recursos Base --}}
    <section class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Gestión de Recursos Base</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Tarjeta 1: Usuarios --}}
            <a href="{{ route('admin.users.index') }}" class="block bg-white p-6 rounded-lg shadow-md hover:bg-gray-50 transition duration-300">
                <h3 class="text-xl font-bold text-gray-800 mb-2 flex items-center"><i class="fas fa-users mr-2 text-blue-500"></i> Gestión de Usuarios y Roles</h3>
                <p class="text-gray-600">Crear y asignar roles (Auditor, Admin, Restaurante).</p>
            </a>

            {{-- Tarjeta 2: Categorías --}}
            <a href="{{ route('admin.categories.index') }}" class="block bg-white p-6 rounded-lg shadow-md hover:bg-gray-50 transition duration-300">
                <h3 class="text-xl font-bold text-gray-800 mb-2 flex items-center"><i class="fas fa-list-alt mr-2 text-red-500"></i> Categorías de Auditoría</h3>
                <p class="text-gray-600">Definir áreas de evaluación (Higiene, Seguridad, Calidad).</p>
            </a>

            {{-- Tarjeta 3: Formularios --}}
            <a href="{{ route('admin.forms.index') }}" class="block bg-white p-6 rounded-lg shadow-md hover:bg-gray-50 transition duration-300">
                <h3 class="text-xl font-bold text-gray-800 mb-2 flex items-center"><i class="fas fa-file-alt mr-2 text-purple-500"></i> Plantillas de Preguntas</h3>
                <p class="text-gray-600">Crear los formularios dinámicos para cada Categoría.</p>
            </a>
            
        </div>
    </section>


    <h3>Actividad Reciente </h3>
    {{-- Bloque de Actividad Reciente (Requiere lógica del controlador) --}}
    <section>
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Última Actividad del Sistema</h2>
        <div class="bg-white p-6 rounded-lg shadow-md">
            {{-- Esto sería un bucle @foreach en una implementación real --}}
            <ul class="space-y-3">
                <li class="border-b pb-2 text-gray-700">Auditoría #120 finalizada para "El Buen Sabor".</li>
                <li class="border-b pb-2 text-gray-700">Nuevo restaurante "La Cocina Express" registrado.</li>
                <li class="border-b pb-2 text-gray-700">El Auditor Juan Pérez ha iniciado sesión.</li>
                <li class="text-gray-700">Categoría "Cumplimiento Normativo" modificada.</li>
            </ul>
        </div>
    </section>

</div>

{{-- Script para cargar Font Awesome Icons (Requerido para los iconos) --}}
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

@endsection
