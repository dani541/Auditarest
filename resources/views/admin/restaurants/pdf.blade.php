<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Informe del Restaurante</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h2 {
            color: #3498db;
            font-size: 18px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Informe del Restaurante</h1>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        <h2>Información General</h2>
        <div class="info-row">
            <div class="info-label">Nombre:</div>
            <div>{{ $restaurant->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Dirección:</div>
            <div>{{ $restaurant->address }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Ciudad:</div>
            <div>{{ $restaurant->city }}</div>
        </div>
        @if($restaurant->description)
        <div class="info-row">
            <div class="info-label">Descripción:</div>
            <div>{{ $restaurant->description }}</div>
        </div>
        @endif
    </div>

    <div class="info-section">
        <h2>Datos de Contacto</h2>
        <div class="info-row">
            <div class="info-label">Persona de Contacto:</div>
            <div>{{ $restaurant->contact_name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Teléfono:</div>
            <div>{{ $restaurant->contact_phone }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div>{{ $restaurant->contact_email }}</div>
        </div>
    </div>

    <div class="footer">
        <p>Sistema de Gestión de Restaurantes - {{ config('app.name') }}</p>
    </div>
</body>
</html>
