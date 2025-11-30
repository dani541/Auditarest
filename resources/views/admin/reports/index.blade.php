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
                        <h1 class="display-4 text-primary" id="totalAudits">0</h1>
                        <p class="text-muted">Auditorías totales</p>
                    </div>
                    <div class="mt-4">
                        <h6>Últimas Auditorías</h6>
                        <div id="recentAudits" class="list-group list-group-flush">
                            <!-- Se llenará con JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos de ejemplo
    const ctx = document.getElementById('auditsChart').getContext('2d');
    
    // Configuración del gráfico
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Número de Auditorías',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Datos de ejemplo para el resumen
    document.getElementById('totalAudits').textContent = '44';
    
    // Ejemplo de auditorías recientes
    const recentAudits = [
        { name: 'Restaurante Central', date: '25/11/2025', score: 92 },
        { name: 'Restaurante Norte', date: '24/11/2025', score: 85 },
        { name: 'Restaurante Sur', date: '23/11/2025', score: 78 }
    ];
    
    const recentAuditsList = document.getElementById('recentAudits');
    recentAudits.forEach(audit => {
        const item = document.createElement('a');
        item.href = '#';
        item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
        item.innerHTML = `
            <div>
                <h6 class="mb-1">${audit.name}</h6>
                <small class="text-muted">${audit.date}</small>
            </div>
            <span class="badge bg-primary rounded-pill">${audit.score}</span>
        `;
        recentAuditsList.appendChild(item);
    });
});
</script>
@endpush