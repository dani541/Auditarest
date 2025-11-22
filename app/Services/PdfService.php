<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    /**
     * Genera un PDF a partir de una vista y datos
     *
     * @param string $view
     * @param array $data
     * @param string $filename
     * @param bool $download
     * @return \Barryvdh\DomPDF\PDF|\Illuminate\Http\Response
     */
    public function generatePdf($view, $data, $filename = 'document.pdf', $download = true)
    {
        // Configurar opciones de PDF
        $pdf = Pdf::loadView($view, $data);
        
        // Configuración adicional del PDF
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial',
            'dpi' => 150,
        ]);

        // Si se solicita descargar directamente
        if ($download) {
            return $pdf->download($filename);
        }

        // Si se quiere guardar en el servidor
        $path = 'pdfs/' . uniqid() . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());
        
        return $path;
    }

    /**
     * Genera el PDF de una auditoría
     *
     * @param \App\Models\Audit $audit
     * @param bool $download
     * @return \Barryvdh\DomPDF\PDF|\Illuminate\Http\Response|string
     */
    public function generateAuditPdf($audit, $download = true)
    {
        // Cargar relaciones necesarias
        $audit->load([
            'verificationItems',
            'auditor',
            'restaurant',
            'evidences'
        ]);

        $groupedItems = $audit->verificationItems->groupBy('category');

        // Calcular estadísticas
        $items = $audit->verificationItems;
        $total = $items->count();
        $cumplen = $items->where('pivot.status', 'C')->count();
        $noCumplen = $items->where('pivot.status', 'IC')->count();
        $noAplica = $items->where('pivot.status', 'NA')->count();
        $porcentaje = $total > 0 ? round(($cumplen / $total) * 100, 2) : 0;

        $data = [
            'audit' => $audit,
            'groupedItems' => $groupedItems,
            'stats' => [
                'total' => $total,
                'cumplen' => $cumplen,
                'noCumplen' => $noCumplen,
                'noAplica' => $noAplica,
                'porcentaje' => $porcentaje
            ],
            'logo' => $this->getLogoBase64(),
            'fecha' => now()->format('d/m/Y H:i:s')
        ];

        $filename = 'auditoria-' . $audit->id . '-' . now()->format('YmdHis') . '.pdf';
        
        return $this->generatePdf('pdf.audit', $data, $filename, $download);
    }

    /**
     * Obtiene el logo en base64 para incrustarlo en el PDF
     *
     * @return string
     */
    protected function getLogoBase64()
    {
        $logoPath = public_path('images/logo.png');
        
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        
        return null;
    }
}
