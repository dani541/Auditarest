@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Informes</h1>
        </div>
    </div>

    <!-- Resumen -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Auditorías por Mes</h5>
                    <canvas id="auditsChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Resumen</h5>
                    <div class="d-flex justify-content-center align-items-center mb-4">
                        <div class="text-center">
                            <h1 class="display-4" id="totalAudits">0</h1>
                            <p class="text-muted mb-0">Auditorías totales</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6>Últimas Auditorías</h6>
                        <div class="list-group list-group-flush" id="recentAudits">
                            <!-- Las auditorías recientes se cargarán aquí -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Heatmap 
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Actividad de Auditorías por Día y Hora</h5>
                    <p class="text-muted small mb-3">Muestra la distribución de auditorías por día de la semana y hora del día</p>
                    <div class="heatmap-container" style="position: relative; height: 400px;">
                        <canvas id="heatmapChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-chart-heatmap/dist/index.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Verificar datos en consola
    console.log('Audits by month:', @json($auditsByMonth));
    console.log('Total audits:', @json($totalAudits));
    console.log('Recent audits:', @json($recentAudits));
    console.log('Heatmap data:', @json($heatmapData));

    // 1. Gráfico de barras - Auditorías por mes
    const ctx = document.getElementById('auditsChart').getContext('2d');
    const auditsByMonth = @json($auditsByMonth);
    
    if (Object.keys(auditsByMonth).length > 0) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(auditsByMonth),
                datasets: [{
                    label: 'Número de Auditorías',
                    data: Object.values(auditsByMonth),
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
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });
    } else {
        console.warn('No hay datos de auditorías por mes para mostrar');
    }

    // 2. Actualizar total de auditorías
    document.getElementById('totalAudits').textContent = '{{ $totalAudits }}';
    
    // 3. Lista de auditorías recientes
    const recentAudits = @json($recentAudits);
    const recentAuditsList = document.getElementById('recentAudits');
    
    if (recentAudits.length > 0) {
        recentAudits.forEach(audit => {
            const item = document.createElement('a');
            item.href = '{{ route("audits.index") }}';
            item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
            item.innerHTML = `
                <div>
                    <h6 class="mb-1">${audit.name}</h6>
                    <small class="text-muted">${audit.date}</small>
                </div>
                <span class="badge bg-${audit.score >= 70 ? 'success' : audit.score >= 50 ? 'warning' : 'danger'} rounded-pill">
                    ${audit.score}%
                </span>
            `;
            recentAuditsList.appendChild(item);
        });
    } else {
        recentAuditsList.innerHTML = '<div class="text-center py-3 text-muted">No hay auditorías recientes</div>';
    }

    // 4. Configuración del heatmap
    const heatmapCtx = document.getElementById('heatmapChart').getContext('2d');
    const heatmapData = @json($heatmapData);
    
    if (Object.keys(heatmapData).length > 0) {
        const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        
        const data = {
            datasets: days.map((day, dayIndex) => {
                const dayNumber = dayIndex + 1;
                const dayData = heatmapData[dayNumber] || {};
                
                return {
                    label: day,
                    data: Array.from({length: 24}, (_, hour) => {
                        const hourData = dayData[hour] || { count: 0 };
                        return {x: hour, y: hourData.count, day: day};
                    }),
                    backgroundColor: function(context) {
                        const value = context.dataset.data[context.dataIndex].y;
                        const alpha = value > 0 ? Math.min(0.9, 0.2 + (value / 10)) : 0;
                        return `rgba(54, 162, 235, ${alpha})`;
                    },
                    borderColor: 'rgba(200, 200, 200, 0.2)',
                    borderWidth: 1,
                    borderRadius: 4
                };
            })
        };

        new Chart(heatmapCtx, {
            type: 'matrix',
            data: {
                datasets: data.datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                const data = context[0].dataset.data[context[0].dataIndex];
                                return `${data.day}, ${String(data.x).padStart(2, '0')}:00 - ${String(parseInt(data.x) + 1).padStart(2, '0')}:00`;
                            },
                            label: function(context) {
                                const value = context.dataset.data[context.dataIndex].y;
                                return `Auditorías: ${value}`;
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        type: 'linear',
                        min: 0,
                        max: 23,
                        offset: true,
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Hora del día'
                        },
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return value + ':00';
                            }
                        }
                    },
                    y: {
                        type: 'category',
                        labels: days,
                        offset: true,
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Día de la semana'
                        }
                    }
                }
            }
        });
    } else {
        console.warn('No hay datos para mostrar en el heatmap');
        document.querySelector('.heatmap-container').innerHTML = 
            '<div class="alert alert-info">No hay datos suficientes para mostrar el mapa de calor</div>';
    }
});
</script>
@endpush