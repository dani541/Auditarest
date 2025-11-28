<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Informe de Auditoría #{{ $audit->id }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px;
            border-bottom: 2px solid #4e73df;
            padding-bottom: 15px;
        }
        .section { 
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section-title { 
            background-color: #4e73df; 
            color: white;
            padding: 8px 12px; 
            font-weight: bold;
            margin: 15px 0 10px 0;
            border-radius: 4px;
        }
        .subsection-title {
            font-weight: bold;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin: 15px 0 8px 0;
        }
        .info-row { 
            margin-bottom: 5px;
            display: flex;
        }
        .info-label {
            font-weight: bold;
            min-width: 200px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.9em;
            font-weight: bold;
            color: white;
        }
        .status-completed { background-color: #1cc88a; }
        .status-pending { background-color: #f6c23e; }
        .status-in-progress { background-color: #36b9cc; }
        .score {
            font-weight: bold;
            color: #2e59d9;
        }
        .signature-box {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        .signature-line {
            width: 60%;
            border-top: 1px solid #000;
            margin: 30px 0 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fc;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <h2>INFORME DE AUDITORÍA INTERNA</h2>
        <h3>Restaurante: {{ $restaurant->name }}</h3>
        <p>Fecha: {{ $audit->date ? $audit->date->format('d/m/Y') : 'No especificada' }} | 
           Código de auditoría: {{ $audit->id }}</p>
    </div>

    <!-- Información General -->
    <div class="section">
        <div class="section-title">1. INFORMACIÓN GENERAL</div>
        
        <div class="info-row">
            <div class="info-label">Auditor:</div>
            <div>{{ $audit->auditor->name ?? 'No asignado' }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Supervisor:</div>
            <div>{{ $audit->supervisorUser->name ?? 'No asignado' }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Estado:</div>
            <div>
                @php
                    $statusClass = [
                        'completada' => 'status-completed',
                        'en_progreso' => 'status-in-progress',
                        'pendiente' => 'status-pending'
                    ][$audit->status ?? 'pendiente'] ?? 'status-pending';
                @endphp
                <span class="status-badge {{ $statusClass }}">
                    {{ ucfirst($audit->status ?? 'pendiente') }}
                </span>
            </div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Puntuación Total:</div>
            <div class="score">{{ number_format($audit->total_score ?? 0, 2) }} / 100</div>
        </div>
    </div>

    <!-- Resumen de Puntuaciones -->
    <div class="section">
        <div class="section-title">2. RESUMEN DE RESULTADOS</div>
        
        <table>
            <thead>
                <tr>
                    <th>Área</th>
                    <th>Puntuación</th>
                    <th>Porcentaje</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $sections = [
                        'Infraestructura' => $audit->infrastructure,
                        'Maquinaria' => $audit->machinery,
                        'Higiene' => $audit->hygiene
                    ];
                @endphp
                
                @foreach($sections as $name => $section)
                    @if($section)
                        @php
                            $score = $section->total_score ?? 0;
                            $statusClass = $score >= 80 ? 'status-completed' : 
                                         ($score >= 50 ? 'status-in-progress' : 'status-pending');
                            $statusText = $score >= 80 ? 'Aprobado' : 
                                        ($score >= 50 ? 'En progreso' : 'Pendiente');
                        @endphp
                        <tr>
                            <td>{{ $name }}</td>
                            <td>{{ number_format($score, 2) }}</td>
                            <td>{{ number_format(($score / 100) * 100, 0) }}%</td>
                            <td><span class="status-badge {{ $statusClass }}">{{ $statusText }}</span></td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td><strong>Total</strong></td>
                    <td colspan="3" class="score">{{ number_format($audit->total_score ?? 0, 2) }} / 100</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Detalles por Área -->
    <div class="section">
        <div class="section-title">3. DETALLES POR ÁREA</div>
        
        @if($audit->infrastructure)
        <div class="subsection">
            <div class="subsection-title">3.1 INFRAESTRUCTURA</div>
            <table>
                <tbody>
                    @foreach($audit->infrastructure->toArray() as $key => $value)
                        @if(!in_array($key, ['id', 'audit_id', 'created_at', 'updated_at', 'total_score']) && !is_null($value))
                        <tr>
                            <td style="width: 70%;">{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                            <td style="width: 30%;">
                                @if(is_bool($value))
                                    {{ $value ? 'Sí' : 'No' }}
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($audit->machinery)
        <div class="subsection">
            <div class="subsection-title">3.2 MAQUINARIA</div>
            <table>
                <tbody>
                    @foreach($audit->machinery->toArray() as $key => $value)
                        @if(!in_array($key, ['id', 'audit_id', 'created_at', 'updated_at', 'total_score']) && !is_null($value))
                        <tr>
                            <td style="width: 70%;">{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                            <td style="width: 30%;">
                                @if(is_bool($value))
                                    {{ $value ? 'Sí' : 'No' }}
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($audit->hygiene)
        <div class="subsection">
            <div class="subsection-title">3.3 HIGIENE</div>
            <table>
                <tbody>
                    @foreach($audit->hygiene->toArray() as $key => $value)
                        @if(!in_array($key, ['id', 'audit_id', 'created_at', 'updated_at', 'total_score']) && !is_null($value))
                        <tr>
                            <td style="width: 70%;">{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                            <td style="width: 30%;">
                                @if(is_bool($value))
                                    {{ $value ? 'Sí' : 'No' }}
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Observaciones y Firmas -->
    <div class="section">
        <div class="section-title">4. OBSERVACIONES Y CONCLUSIONES</div>
        <div style="min-height: 80px; border: 1px solid #ddd; padding: 10px; margin: 10px 0;">
            {{ $audit->general_notes ?? 'Sin observaciones adicionales.' }}
        </div>
    </div>

    <!-- Firmas -->
    <div style="margin-top: 50px;">
        <div style="width: 45%; float: left;">
            <div class="signature-line"></div>
            <div style="text-align: center;">Firma del Auditor</div>
            <div style="text-align: center; margin-top: 10px;">{{ $audit->auditor->name ?? '__________________________' }}</div>
        </div>
        <div style="width: 45%; float: right;">
            <div class="signature-line"></div>
            <div style="text-align: center;">Firma del Responsable</div>
            <div style="text-align: center; margin-top: 10px;">__________________________</div>
        </div>
        <div style="clear: both;"></div>
    </div>

    <!-- Pie de página -->
    <div style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #666; border-top: 1px solid #eee; padding-top: 5px;">
        Generado el {{ now()->format('d/m/Y H:i') }} | {{ config('app.name') }}
    </div>
</body>
</html>