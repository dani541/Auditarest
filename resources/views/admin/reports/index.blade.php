@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Estadísticas de Auditorías</h2>
    
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Auditorías por Mes</h5>
                    <canvas id="auditsChart" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Resumen</h5>
                    <div class="text-center py-4">
                        <h1 class="display-4 text-primary" id="totalAudits">{{ number_format($totalAudits) }}</h1>
                        <p class="text-muted">Auditorías totales</p>
                    </div>
                    <div class="mt-3">
                        <h6>Últimas Auditorías</h6>
                        <div id="recentAudits" class="list-group list-group-flush">
                            @foreach($recentAudits as $audit)
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $audit['name'] }}</h6>
                                    <small class="text-muted">{{ $audit['date'] }}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ number_format($audit['score'], 1) }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Distribución de Puntuaciones por Categoría</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart-container" style="position: relative; height: 350px; width: 100%;">
                                @if(isset($isDemoData) && $isDemoData)
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Se están mostrando datos de ejemplo. Agrega auditorías reales para ver los datos reales.
                                    </div>
                                @endif
                                <canvas id="donutChart"></canvas>
                                <div id="noDataMessage" class="text-center d-none" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                    <i class="fas fa-chart-pie fa-3x text-muted mb-2"></i>
                                    <p class="text-muted">No hay datos disponibles para mostrar</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mt-4">
                                <h6>Detalles de Puntuación</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Categoría</th>
                                                <th class="text-end">Puntuación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($avgScores as $category => $data)
                                            <tr>
                                                <td>
                                                    <span class="score-indicator" style="background-color: {{ $data['color'] }}"></span>
                                                    {{ ucfirst($category) }}
                                                </td>
                                                <td class="text-end">
                                                    <span class="fw-bold">{{ number_format($data['score'], 1) }}%</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
</div>

@push('styles')
<style>
    .score-indicator {
        width: 15px;
        height: 15px;
        display: inline-block;
        margin-right: 5px;
        border-radius: 3px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de barras - Auditorías por mes
    const ctx = document.getElementById('auditsChart').getContext('2d');
    const barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($monthNames),
            datasets: [{
                label: 'Número de Auditorías',
                data: @json($monthlyData),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Gráfico de anillo - Distribución por categoría
    const donutCtx = document.getElementById('donutChart');
    const noDataMessage = document.getElementById('noDataMessage');
    
    // Asegurarse de que los datos sean números
    const donutData = @json($radarData).map(Number);
    const donutLabels = @json($radarLabels);
    
    // Verificar que hay datos para mostrar
    console.log('Datos del gráfico:', donutData);
    console.log('Etiquetas:', donutLabels);
    console.log('Colores:', @json($radarBackgroundColors));
    
    if (donutCtx && donutData.length > 0 && donutData.some(value => value > 0)) {
        // Ocultar mensaje de no datos
        if (noDataMessage) noDataMessage.classList.add('d-none');
        
        // Crear el gráfico
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: donutLabels,
                datasets: [{
                    data: donutData,
                    backgroundColor: @json($radarBackgroundColors),
                    borderColor: @json($radarBorderColors),
                    borderWidth: 1,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 15,
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label}: ${value}%`;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    } else {
        // Mostrar mensaje si no hay datos
        donutCtx.font = '16px Arial';
        donutCtx.textAlign = 'center';
        donutCtx.fillText('No hay datos disponibles', 150, 150);
        console.warn('No hay datos suficientes para mostrar el gráfico');
    }
});
</script>
@endpush

@endsection