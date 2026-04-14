<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nro_desp = $_POST['nro_desplazamiento'];
    $origen_id = $_POST['origen_id'];
    $destino_id = $_POST['destino_id'];
    $motivo = $_POST['motivo'];
    $bienes = $_POST['bienes']; // Array de IDs de los bienes seleccionados

    try {
        $pdo->beginTransaction();

        // 1. Insertar la cabecera del desplazamiento
        $stmt = $pdo->prepare("INSERT INTO desplazamientos (numero_desplazamiento, persona_origen_id, persona_destino_id, motivo, fecha) VALUES (?, ?, ?, ?, CURDATE())");
        $stmt->execute([$nro_desp, $origen_id, $destino_id, $motivo]);
        $desplazamiento_id = $pdo->lastInsertId();

        foreach ($bienes as $bien_id) {
            // 2. Registrar detalle
            $stmtDetalle = $pdo->prepare("INSERT INTO detalle_desplazamiento (desplazamiento_id, bien_id) VALUES (?, ?)");
            $stmtDetalle->execute([$desplazamiento_id, $bien_id]);

            // 3. Registrar historial de auditoría
            $stmtHist = $pdo->prepare("INSERT INTO historial (bien_id, persona_anterior_id, persona_nueva_id, accion) VALUES (?, ?, ?, 'DESPLAZAMIENTO')");
            $stmtHist->execute([$bien_id, $origen_id, $destino_id]);

            // 4. Actualizar el dueño actual en la tabla bienes
            $stmtUpdate = $pdo->prepare("UPDATE bienes SET persona_id = ? WHERE id = ?");
            $stmtUpdate->execute([$destino_id, $bien_id]);
        }

        $pdo->commit();
        echo "Desplazamiento registrado con éxito.";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error en el proceso: " . $e->getMessage();
    }
}
?>