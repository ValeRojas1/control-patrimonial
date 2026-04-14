<?php
class AsignacionController {
    private $pdo;
    public function __construct($db) { $this->pdo = $db; }

    public function asignar($data) {
        try {
            $bId = $data['bien_id'] ?? null;
            $pId = $data['persona_id'] ?? null;

            if (!$bId || !$pId) return ["status" => "error", "message" => "Faltan IDs"];

            $stmt = $this->pdo->prepare("UPDATE bienes SET persona_id = ? WHERE id = ?");
            $res = $stmt->execute([$pId, $bId]);

            return ["status" => "success", "message" => "Asignado"];
        } catch (Exception $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }
}