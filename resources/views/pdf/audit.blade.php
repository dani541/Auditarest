<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Auditoría #{{ $audit->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 30px; }
        .section-title { 
            background-color: #f8f9fa; 
            padding: 10px; 
            font-weight: bold;
            border-left: 4px solid #4e73df;
        }
        .info-row { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Auditoría #{{ $audit->id }}</h1>
        <p>Restaurante: {{ $restaurant->name }}</p>
        <p>Fecha: {{ $audit->date ? $audit->date->format('d/m/Y') : 'No especificada' }}</p>
    </div>

    <div class="section">
        <div class="section-title">Información General</div>
        <div class="info-row"><strong>Auditor:</strong> {{ $audit->auditor ?? 'No asignado' }}</div>
        <div class="info-row">
            <strong>Estado:</strong> 
            <span style="background-color: {{ $audit->status === 'completada' ? '#1cc88a' : '#f6c23e' }}; 
                        color: white; 
                        padding: 5px 10px;
                        border-radius: 3px;">
                {{ ucfirst($audit->status ?? 'pendiente') }}
            </span>
        </div>
    </div>

    @if($audit->infrastructure)
    <div class="section">
        <div class="section-title">Infraestructura</div>
        <!-- Agrega aquí los campos de infraestructura -->
        <p><strong>Estado del piso:</strong> {{ $audit->infrastructure->floor_condition ?? 'No especificado' }}</p>
        <!-- Agrega más campos según sea necesario -->
    </div>
    @endif

    @if($audit->machinery)
    <div class="section">
        <div class="section-title">Maquinaria</div>
        <!-- Agrega aquí los campos de maquinaria -->
    </div>
    @endif

    @if($audit->hygiene)
    <div class="section">
        <div class="section-title">Higiene</div>
        <!-- Agrega aquí los campos de higiene -->
    </div>
    @endif
</body>
</html>