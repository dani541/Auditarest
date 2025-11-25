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
    <input type="text" 
           class="form-control @error('auditor') is-invalid @enderror" 
           id="auditor" 
           name="auditor" 
           value="{{ old('auditor', auth()->check() ? auth()->user()->name : '') }}" 
           required>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="general_notes">Notas Generales</label>
                            <textarea class="form-control @error('general_notes') is-invalid @enderror" 
                                      id="general_notes" name="general_notes" 
                                      rows="1">{{ old('general_notes') }}</textarea>
                            @error('general_notes')
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
                                    'floor' => 'Suelo en buen estado',
                                    'walls' => 'Paredes en buen estado',
                                    'windows' => 'Ventanas en buen estado',
                                    'doors' => 'Puertas en buen estado',
                                    'ceiling' => 'Techos en buen estado',
                                    'lighting' => 'Lámparas y luminarias en buen estado',
                                    'countertops' => 'Encimeras sin grietas ni descorchones',
                                    'work_tables' => 'Mesas de trabajo sin grietas ni descorchones'
                                ];
                            @endphp

                            @foreach($infrastructureItems as $field => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" 
                                                   name="infrastructure[{{ $field }}_condition]" 
                                                   value="1" 
                                                   {{ old("infrastructure.{$field}_condition") === '1' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" 
                                                   name="infrastructure[{{ $field }}_condition]" 
                                                   value="0" 
                                                   {{ old("infrastructure.{$field}_condition") === '0' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" 
                                               name="infrastructure[{{ $field }}_notes]" 
                                               value="{{ old("infrastructure.{$field}_notes") }}">
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
                                    'stove' => 'Fogones en buen estado',
                                    'oven' => 'Horno en buen estado',
                                    'fryer' => 'Freidoras en buen estado',
                                    'induction_cooktop' => 'Placa de inducción en buen estado',
                                    'hood' => 'Campana en buen estado',
                                    'gas_outlets' => 'Tomas de gas sin fugas ni roturas',
                                    'microwave' => 'Microondas en buen estado',
                                    'dishwasher' => 'Lavavajillas en buen estado y sin fugas',
                                    'mixer' => 'Batidora en buen estado',
                                    'faucets' => 'Grifería en buen estado',
                                    'electrical_system' => 'Sistema eléctrico en buen estado',
                                    'refrigerators' => 'Refrigeradores en buen estado y manteniendo el frío',
                                    'freezers' => 'Arcónes en buen estado y manteniendo el frío',
                                    'cutting_boards' => 'Tablas de corte en buen estado'
                                ];
                            @endphp

                            @foreach($machineryItems as $field => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" 
                                                   name="machinery[{{ $field }}_condition]" 
                                                   value="1" 
                                                   {{ old("machinery.{$field}_condition") === '1' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" 
                                                   name="machinery[{{ $field }}_condition]" 
                                                   value="0" 
                                                   {{ old("machinery.{$field}_condition") === '0' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" 
                                               name="machinery[{{ $field }}_notes]" 
                                               value="{{ old("machinery.{$field}_notes") }}">
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
                                    'personal_hygiene' => 'Higiene personal adecuada',
                                    'uniform_cleanliness' => 'Limpieza del uniforme',
                                    'hair_protection' => 'Protección del cabello',
                                    'hand_washing' => 'Lavado de manos adecuado',
                                    'food_handling' => 'Manejo adecuado de alimentos',
                                    'waste_management' => 'Manejo de residuos',
                                    'pest_control' => 'Control de plagas',
                                    'cleaning_procedures' => 'Procedimientos de limpieza',
                                    'chemical_storage' => 'Almacenamiento de químicos'
                                ];
                            @endphp

                            @foreach($hygieneItems as $field => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" 
                                                   name="hygiene[{{ $field }}_condition]" 
                                                   value="1" 
                                                   {{ old("hygiene.{$field}_condition") === '1' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" 
                                                   name="hygiene[{{ $field }}_condition]" 
                                                   value="0" 
                                                   {{ old("hygiene.{$field}_condition") === '0' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" 
                                               name="hygiene[{{ $field }}_notes]" 
                                               value="{{ old("hygiene.{$field}_notes") }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sección de Notas Adicionales -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 font-weight-bold">IV. 📝 Notas Adicionales</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="general_notes">Observaciones Generales</label>
                    <textarea class="form-control" id="general_notes" name="general_notes" rows="3">{{ old('general_notes') }}</textarea>
                </div>
            </div>
        </div>

      
        <div class="row mb-4">
            <div class="col-12 text-right">
                <a href="{{ route('audits.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Auditoría
                </button>
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

       
        document.querySelectorAll('input[type="radio"][value="0"]').forEach(radio => {
            toggleIncidenceField(radio, incidenceInput);
            
            
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

