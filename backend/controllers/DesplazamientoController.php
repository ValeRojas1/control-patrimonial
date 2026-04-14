<?php
// backend/controllers/DesplazamientoController.php
require_once __DIR__ . '/../config/conexion.php';

class DesplazamientoController {
    private $pdo;

    public function __construct($conexion) {
        $this->pdo = $conexion;
    }

    public function registrar($data) {
        try {
            $this->pdo->beginTransaction();

            // 1. Insertar cabecera del desplazamiento
            $sqlDesp = "INSERT INTO desplazamientos (numero_desplazamiento, persona_origen_id, persona_destino_id, motivo, fecha) 
                        VALUES (:nro, :origen, :destino, :motivo, CURDATE())";
            $stmt = $this->pdo->prepare($sqlDesp);
            $stmt->execute([
                ':nro'     => $data['numero_desplazamiento'],
                ':origen'  => $data['persona_origen_id'],
                ':destino' => $data['persona_destino_id'],
                ':motivo'  => $data['motivo']
            ]);
            $idDesplazamiento = $this->pdo->lastInsertId();

            // 2. Procesar cada bien seleccionado
            foreach ($data['bienes_ids'] as $bienId) {
                // Registrar en detalle_desplazamiento
                $sqlDetalle = "INSERT INTO detalle_desplazamiento (desplazamiento_id, bien_id) VALUES (?, ?)";
                $this->pdo->prepare($sqlDetalle)->execute([$idDesplazamiento, $bienId]);

                // Registrar en historial para trazabilidad
                $sqlHist = "INSERT INTO historial (bien_id, persona_anterior_id, persona_nueva_id, accion) 
                            VALUES (?, ?, ?, 'DESPLAZAMIENTO')";
                $this->pdo->prepare($sqlHist)->execute([$bienId, $data['persona_origen_id'], $data['persona_destino_id']]);

                // ACTUALIZACIÓN AUTOMÁTICA: Cambiar el poseedor en la tabla bienes
                $sqlUpdate = "UPDATE bienes SET persona_id = ? WHERE id = ?";
                $this->pdo->prepare($sqlUpdate)->execute([$data['persona_destino_id'], $bienId]);
            }

            $this->pdo->commit();
            return ["status" => "success", "message" => "Desplazamiento procesado correctamente"];

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }
}