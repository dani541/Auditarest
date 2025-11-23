@extends('layouts.app')

@section('title', 'Nueva Auditoría')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nueva Auditoría</h1>
    </div>

    <form action="{{ route('audits.store') }}" method="POST" id="auditForm">
        @csrf

        <!-- Datos de la Auditoría -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Datos de la Auditoría</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="restaurant_id">Restaurante</label>
                            <select class="form-control @error('restaurant_id') is-invalid @enderror" 
                                    id="restaurant_id" name="restaurant_id" required>
                                <option value="">Seleccione un restaurante</option>
                                @foreach($restaurants as $restaurant)
                                    <option value="{{ $restaurant->id }}" {{ old('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                                        {{ $restaurant->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('restaurant_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="auditor">Auditor</label>
                            <input type="text" class="form-control @error('auditor') is-invalid @enderror" 
                                   id="auditor" name="auditor" 
                                   value="{{ old('auditor', auth()->user()->name) }}" required>
                            @error('auditor')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date">Fecha de Auditoría</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                   id="date" name="date" 
                                   value="{{ old('date', now()->format('Y-m-d')) }}" required>
                            @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="supervisor">Supervisor</label>
                            <input type="text" class="form-control @error('supervisor') is-invalid @enderror" 
                                   id="supervisor" name="supervisor" 
                                   value="{{ old('supervisor') }}" required>
                            @error('supervisor')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección 1: Infraestructura -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 font-weight-bold">I. 🏗️ Infraestructura (Infrastructure)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50%">Elemento de Verificación</th>
                                <th class="text-center" style="width: 15%">Conforme (C)</th>
                                <th class="text-center" style="width: 15%">No Conforme (IC)</th>
                                <th style="width: 20%">Incidencia / Medida Correctora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $infrastructureItems = [
                                    'Suelo en buen estado',
                                    'Paredes en buen estado',
                                    'Ventanas en buen estado',
                                    'Puertas en buen estado',
                                    'Techos en buen estado',
                                    'Lámparas y luminarias en buen estado',
                                    'Encimeras sin grietas ni descorchones',
                                    'Mesas de trabajo sin grietas ni descorchones'
                                ];
                            @endphp

                            @foreach($infrastructureItems as $index => $item)
                                <tr>
                                    <td>{{ $item }}</td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" 
                                                   name="verification[infraestructura][{{ $index }}][complies]" 
                                                   value="1" 
                                                   {{ old("verification.infraestructura.{$index}.complies") === '1' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" 
                                                   name="verification[infraestructura][{{ $index }}][complies]" 
                                                   value="0" 
                                                   {{ old("verification.infraestructura.{$index}.complies") === '0' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" 
                                               name="verification[infraestructura][{{ $index }}][notes]" 
                                               value="{{ old("verification.infraestructura.{$index}.notes") }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sección 2: Maquinaria y Equipos -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 font-weight-bold">II. 🍳 Maquinaria y Equipos (Machinery and Equipment)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50%">Elemento de Verificación</th>
                                <th class="text-center" style="width: 15%">C</th>
                                <th class="text-center" style="width: 15%">IC</th>
                                <th style="width: 20%">Incidencia / Medida Correctora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $machineryItems = [
                                    'Fogones en buen estado',
                                    'Horno en buen estado',
                                    'Freidoras en buen estado',
                                    'Placa de inducción en buen estado',
                                    'Campana en buen estado',
                                    'Tomas de gas sin fugas ni roturas',
                                    'Microondas en buen estado',
                                    'Lavavajillas en buen estado y sin fugas',
                                    'Batidora en buen estado',
                                    'Grifería en buen estado',
                                    'Sistema eléctrico en buen estado',
                                    'Refrigeradores en buen estado y manteniendo el frío',
                                    'Arcónes en buen estado y manteniendo el frío',
                                    'Tablas de corte en buen estado'
                                ];
                            @endphp

                            @foreach($machineryItems as $index => $item)
                                <tr>
                                    <td>{{ $item }}</td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" 
                                                   name="verification[maquinaria][{{ $index }}][complies]" 
                                                   value="1" 
                                                   {{ old("verification.maquinaria.{$index}.complies") === '1' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" 
                                                   name="verification[maquinaria][{{ $index }}][complies]" 
                                                   value="0" 
                                                   {{ old("verification.maquinaria.{$index}.complies") === '0' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" 
                                               name="verification[maquinaria][{{ $index }}][notes]" 
                                               value="{{ old("verification.maquinaria.{$index}.notes") }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sección 3: Buenas Prácticas y Condiciones Higiénicas -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 font-weight-bold">III. ✅ Buenas Prácticas y Condiciones Higiénicas</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50%">Elemento de Verificación</th>
                                <th class="text-center" style="width: 15%">C</th>
                                <th class="text-center" style="width: 15%">IC</th>
                                <th style="width: 20%">Incidencia / Medida Correctora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $hygieneItems = [
                                    'Espacios ordenados',
                                    'Separación de productos (Elaborado / Materia Prima)',
                                    'Descongelación en condiciones higiénicas',
                                    'Desinfección correcta de los productos',
                                    'Manipuladores libres de joyas o postizos',
                                    'Instrucciones de desinfección seguidas',
                                    'Productos de limpieza aislados de alimentos',
                                    'Neveras a la temperatura correcta (4°C)',
                                    'Alimentos en neveras a 4°C',
                                    'Congeladores a la temperatura correcta (-18°C)',
                                    'Alimentos en congelador a -18°C'
                                ];
                            @endphp

                            @foreach($hygieneItems as $index => $item)
                                <tr>
                                    <td>{{ $item }}</td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" 
                                                   name="verification[higiene][{{ $index }}][complies]" 
                                                   value="1" 
                                                   {{ old("verification.higiene.{$index}.complies") === '1' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" 
                                                   name="verification[higiene][{{ $index }}][complies]" 
                                                   value="0" 
                                                   {{ old("verification.higiene.{$index}.complies") === '0' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" 
                                               name="verification[higiene][{{ $index }}][notes]" 
                                               value="{{ old("verification.higiene.{$index}.notes") }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sección 4: Registro de Auditoría -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 font-weight-bold">IV. ✍️ Registro de Auditoría</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="incidencias_comentarios">Incidencias / Comentarios</label>
                    <textarea class="form-control @error('incidencias_comentarios') is-invalid @enderror" 
                              id="incidencias_comentarios" name="incidencias_comentarios" 
                              rows="3">{{ old('incidencias_comentarios') }}</textarea>
                    @error('incidencias_comentarios')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="responsable">Responsable</label>
                            <input type="text" class="form-control @error('responsable') is-invalid @enderror" 
                                   id="responsable" name="responsable" 
                                   value="{{ old('responsable', auth()->user()->name) }}" required>
                            @error('responsable')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supervisor">Supervisor</label>
                            <input type="text" class="form-control @error('supervisor') is-invalid @enderror" 
                                   id="supervisor" name="supervisor" 
                                   value="{{ old('supervisor') }}" required>
                            @error('supervisor')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Auditoría
                    </button>
                    <a href="{{ route('audits.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Auto-select C (Conforme) for all radio buttons on load
    document.addEventListener('DOMContentLoaded', function() {
        // Set default values for radio buttons
        const radioGroups = document.querySelectorAll('input[type="radio"]');
        radioGroups.forEach(radio => {
            if (!radio.checked && radio.value === '1') {
                radio.checked = true;
            }
        });

        // Show/hide notes field when IC is selected
        document.querySelectorAll('input[type="radio"][value="0"]').forEach(radio => {
            toggleIncidenceField(radio, incidenceInput);
            
            // Mostrar/ocultar al cambiar la selección
            radio.addEventListener('change', function() {
                toggleIncidenceField(radio, incidenceInput);
            });
        });

        function toggleIncidenceField(radio, incidenceInput) {
            if (radio.checked && radio.value === '0') {
                incidenceInput.style.display = 'block';
                incidenceInput.required = true;
            } else if (radio.checked && radio.value === '1') {
                incidenceInput.style.display = 'none';
                incidenceInput.required = false;
                incidenceInput.value = '';
            }
        }

        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            let isValid = true;
            const requiredRadios = document.querySelectorAll('.answer-radio[required]');
            const radioGroups = {};

            // Agrupar radios por nombre
            requiredRadios.forEach(radio => {
                const name = radio.name;
                if (!radioGroups[name]) {
                    radioGroups[name] = [];
                }
                radioGroups[name].push(radio);
            });

            // Verificar que al menos un radio de cada grupo esté seleccionado
            for (const [name, radios] of Object.entries(radioGroups)) {
                const isAnyChecked = Array.from(radios).some(radio => radio.checked);
                if (!isAnyChecked) {
                    isValid = false;
                    // Resaltar fila
                    const row = radios[0].closest('tr');
                    row.style.backgroundColor = '#fff3cd';
                }
            }

            if (!isValid) {
                e.preventDefault();
                alert('Por favor, responde todas las preguntas marcadas como obligatorias.');
            }
        });
    });
</script>
@endpush