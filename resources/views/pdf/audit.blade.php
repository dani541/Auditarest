<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Auditoría #{{ $audit->id }} - {{ $audit->restaurant->name }}</title>
    <style>
        @page { margin: 20px 25px; }
        body { 
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #3490dc;
            padding-bottom: 10px;
        }
        .logo {
            max-width: 150px;
            max-height: 80px;
        }
        .title {
            color: #2d3748;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            color: #4a5568;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .section-title {
            background-color: #f7fafc;
            color: #2d3748;
            font-size: 12px;
            font-weight: bold;
            padding: 5px 10px;
            margin: 15px 0 10px 0;
            border-left: 4px solid #3490dc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9pt;
        }
        th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        td {
            border: 1px solid #dee2e6;
            padding: 8px;
            vertical-align: top;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9pt;
            font-weight: bold;
            text-align: center;
        }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .badge-secondary { background-color: #e2e3e5; color: #383d41; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mt-3 { margin-top: 15px; }
        .mb-3 { margin-bottom: 15px; }
        .py-2 { padding-top: 8px; padding-bottom: 8px; }
        .page-break { page-break-after: always; }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
            width: 300px;
            text-align: center;
            font-size: 9pt;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding: 5px 0;
        }
        .page-number:after {
            content: counter(page);
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <table>
            <tr>
                <td style="width: 20%; vertical-align: middle;">
                    @if($logo)
                        <img src="{{ $logo }}" class="logo" alt="Logo">
                    @endif
                </td>
                <td style="width: 60%; text-align: center; vertical-align: middle;">
                    <div class="title">INFORME DE AUDITORÍA</div>
                    <div class="subtitle">{{ $audit->restaurant->name }}</div>
                </td>
                <td style="width: 20%; text-align: right; vertical-align: middle;">
                    <div style="font-size: 9pt;">
                        <div><strong>Auditoría #</strong> {{ str_pad($audit->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div><strong>Fecha:</strong> {{ $audit->scheduled_date->format('d/m/Y') }}</div>
                        <div><strong>Hora:</strong> {{ $audit->created_at->format('H:i') }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Información General -->
    <div class="section-title">INFORMACIÓN GENERAL</div>
    <table>
        <tr>
            <td style="width: 50%;">
                <strong>Auditor:</strong> {{ $audit->auditor->name }}
            </td>
            <td style="width: 50%;">
                <strong>Restaurante:</strong> {{ $audit->restaurant->name }}
            </td>
        </tr>
        <tr>
            <td>
                <strong>Fecha de Auditoría:</strong> {{ $audit->scheduled_date->format('d/m/Y') }}
            </td>
            <td>
                <strong>Estado:</strong> 
                @if($audit->status === 'completada')
                    <span class="badge badge-success">COMPLETADA</span>
                @elseif($audit->status === 'pendiente')
                    <span class="badge badge-warning">PENDIENTE</span>
                @else
                    <span class="badge badge-secondary">{{ strtoupper($audit->status) }}</span>
                @endif
            </td>
        </tr>
    </table>

    <!-- Resumen de Resultados -->
    <div class="section-title">RESUMEN DE RESULTADOS</div>
    <table>
        <tr>
            <td class="text-center" style="width: 25%;">
                <div style="font-size: 18px; font-weight: bold;">{{ $stats['total'] }}</div>
                <div>Total Verificaciones</div>
            </td>
            <td class="text-center" style="width: 25%; background-color: #d4edda;">
                <div style="font-size: 18px; font-weight: bold; color: #155724;">{{ $stats['cumplen'] }}</div>
                <div>Cumplen</div>
            </td>
            <td class="text-center" style="width: 25%; background-color: #f8d7da;">
                <div style="font-size: 18px; font-weight: bold; color: #721c24;">{{ $stats['noCumplen'] }}</div>
                <div>No Cumplen</div>
            </td>
            <td class="text-center" style="width: 25%; background-color: #e2e3e5;">
                <div style="font-size: 18px; font-weight: bold; color: #383d41;">{{ $stats['noAplica'] }}</div>
                <div>No Aplica</div>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="padding: 0;">
                <div style="background-color: #f8f9fa; height: 25px; position: relative;">
                    <div style="background-color: #3490dc; height: 100%; width: {{ $stats['porcentaje'] }}%;">
                        <div style="position: absolute; left: 10px; top: 3px; color: #000; font-weight: bold;">
                            {{ $stats['porcentaje'] }}% de cumplimiento
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Detalle por Categoría -->
    @foreach($groupedItems as $category => $items)
        <div class="section-title">{{ strtoupper(str_replace('_', ' ', $category)) }}</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 55%;">Verificación</th>
                    <th style="width: 10%; text-align: center;">Estado</th>
                    <th style="width: 30%;">Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                    @php
                        $response = $item->pivot;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->description }}</td>
                        <td class="text-center">
                            @if($response->status === 'C')
                                <span class="badge badge-success">CUMPLE</span>
                            @elseif($response->status === 'IC')
                                <span class="badge badge-danger">NO CUMPLE</span>
                            @else
                                <span class="badge badge-secondary">NO APLICA</span>
                            @endif
                        </td>
                        <td>
                            {{ $response->corrective_measure }}
                            @if($response->temperature)
                                <div><strong>Temperatura:</strong> {{ $response->temperature }}°C</div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <!-- Observaciones Generales -->
    @if($audit->observations)
        <div class="section-title">OBSERVACIONES GENERALES</div>
        <div style="border: 1px solid #dee2e6; padding: 10px; margin-bottom: 15px; min-height: 100px;">
            {!! nl2br(e($audit->observations)) !!}
        </div>
    @endif

    <!-- Firma del Auditor -->
    <div style="margin-top: 50px;">
        <div class="signature-line">
            {{ $audit->auditor->name }}
        </div>
        <div style="text-align: center; font-size: 9pt; margin-top: 5px;">
            Firma del Auditor
        </div>
    </div>

    <!-- Pie de página -->
    <div class="footer">
        <div>Generado el {{ $fecha }} - Página <span class="page-number"></span></div>
        <div>{{ config('app.name') }} - {{ config('app.url') }}</div>
    </div>
</body>
</html>
