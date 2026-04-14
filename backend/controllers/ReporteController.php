<?php
require_once __DIR__ . '/utils/libs/fpdf/fpdf.php';


class ReporteController {
    private $pdo;

    public function __construct($conexion) {
        $this->pdo = $conexion;
    }

    // REPORTE 1: Bienes actuales por persona (Acta)
    public function generarAsignacionPersona($personaId) {
        $stmt = $this->pdo->prepare("SELECT * FROM personas WHERE id = ?");
        $stmt->execute([$personaId]);
        $persona = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->pdo->prepare("SELECT codigo_patrimonial, nombre, estado FROM bienes WHERE persona_id = ?");
        $stmt->execute([$personaId]);
        $bienes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdf = new FPDF();
        $pdf->AddPage();
        $this->cabecera($pdf, 'ACTA DE ASIGNACION DE BIENES');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 10, 'DATOS DEL RESPONSABLE:', 0, 1);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, 'Nombre: ' . utf8_decode($persona['nombre']), 0, 1);
        $pdf->Cell(0, 7, 'Area: ' . utf8_decode($persona['area']), 0, 1);
        $pdf->Ln(5);

        $this->tablaBienes($pdf, $bienes);
        $pdf->Output('I', 'Acta_Asignacion.pdf');
    }

    // REPORTE 2: Historial de desplazamientos
    public function generarReporteGeneralDesplazamientos() {
        $sql = "SELECT d.fecha, b.nombre as bien, b.codigo_patrimonial, 
                       p_origen.nombre as origen, p_destino.nombre as destino, d.motivo
                FROM desplazamientos d
                JOIN bienes b ON d.bien_id = b.id
                JOIN personas p_origen ON d.origen_persona_id = p_origen.id
                JOIN personas p_destino ON d.destino_persona_id = p_destino.id
                ORDER BY d.fecha DESC";
        
        $desplazamientos = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $pdf = new FPDF('L', 'mm', 'A4'); // Horizontal para ver más datos
        $pdf->AddPage();
        $this->cabecera($pdf, 'HISTORIAL GENERAL DE DESPLAZAMIENTOS');

        // Encabezados de tabla
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(200, 220, 255);
        $pdf->Cell(25, 8, 'Fecha', 1, 0, 'C', true);
        $pdf->Cell(60, 8, 'Bien / Codigo', 1, 0, 'C', true);
        $pdf->Cell(50, 8, 'Origen', 1, 0, 'C', true);
        $pdf->Cell(50, 8, 'Destino', 1, 0, 'C', true);
        $pdf->Cell(90, 8, 'Motivo', 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 8);
        foreach ($desplazamientos as $d) {
            $pdf->Cell(25, 7, $d['fecha'], 1);
            $pdf->Cell(60, 7, utf8_decode($d['bien'] . " (" . $d['codigo_patrimonial'] . ")"), 1);
            $pdf->Cell(50, 7, utf8_decode($d['origen']), 1);
            $pdf->Cell(50, 7, utf8_decode($d['destino']), 1);
            $pdf->Cell(90, 7, utf8_decode($d['motivo']), 1, 1);
        }

        $pdf->Output('I', 'Historial_Desplazamientos.pdf');
    }

    private function cabecera($pdf, $titulo) {
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, utf8_decode($titulo), 0, 1, 'C');
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 10, 'Generado el: ' . date('d/m/Y H:i'), 0, 1, 'R');
        $pdf->Ln(5);
    }

    private function tablaBienes($pdf, $bienes) {
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 8, 'Codigo', 1, 0, 'C');
        $pdf->Cell(90, 8, 'Descripcion del Bien', 1, 0, 'C');
        $pdf->Cell(30, 8, 'Estado', 1, 0, 'C');
        $pdf->Cell(30, 8, 'Firma', 1, 1, 'C');
        
        $pdf->SetFont('Arial', '', 10);
        foreach ($bienes as $b) {
            $pdf->Cell(40, 8, $b['codigo_patrimonial'], 1);
            $pdf->Cell(90, 8, utf8_decode($b['nombre']), 1);
            $pdf->Cell(30, 8, $b['estado'], 1, 0, 'C');
            $pdf->Cell(30, 8, '', 1, 1);
        }
    }
}