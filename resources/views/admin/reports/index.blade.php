@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Estadísticas de Auditorías</h2>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Auditorías por Mes</h5>
                    <canvas id="auditsChart" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm">
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
                                <span class="badge bg-primary rounded-pill">{{ $audit['score'] }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos para el gráfico
    const ctx = document.getElementById('auditsChart').getContext('2d');
    
    // Configuración del gráfico
    const myChart = new Chart(ctx, {
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
});
</script>
@endpush

@endsection