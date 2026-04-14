<?php
// backend/controllers/BienController.php

class BienController {
    private $pdo;

    public function __construct($conexion) {
        $this->pdo = $conexion;
    }

    // Nuevo método para validar duplicados
    public function existeCodigo($codigo) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM bienes WHERE codigo_patrimonial = ?");
        $stmt->execute([$codigo]);
        return $stmt->fetchColumn() > 0;
    }

    public function registrar($data) {
        // Validar si el código ya existe antes de insertar
        if ($this->existeCodigo($data['codigo'])) {
            return ["status" => "error", "message" => "El código patrimonial ya se encuentra registrado"];
        }

        try {
            $sql = "INSERT INTO bienes (codigo_patrimonial, nombre, descripcion, estado) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['codigo'], 
                $data['nombre'], 
                $data['descripcion'], 
                $data['estado']
            ]);
            return ["status" => "success", "message" => "Bien registrado correctamente"];
        } catch (Exception $e) {
            return ["status" => "error", "message" => "Error en la base de datos: " . $e->getMessage()];
        }
    }
    
    // ... método listar ...
    public function obtenerEstadisticas() {
        // Total de bienes
        $total = $this->pdo->query("SELECT COUNT(*) FROM bienes")->fetchColumn();
        
        // Bienes asignados
        $asignados = $this->pdo->query("SELECT COUNT(*) FROM bienes WHERE persona_id IS NOT NULL")->fetchColumn();
        
        // Bienes disponibles
        $disponibles = $this->pdo->query("SELECT COUNT(*) FROM bienes WHERE persona_id IS NULL")->fetchColumn();

        return [
            "total" => $total,
            "asignados" => $asignados,
            "disponibles" => $disponibles
        ];
    }
    public function listar() {
            try {
                $sql = "SELECT b.id, b.codigo_patrimonial, b.nombre, b.estado, p.nombre as asignado 
                        FROM bienes b 
                        LEFT JOIN personas p ON b.persona_id = p.id 
                        ORDER BY b.id DESC";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return [];
            }
        }
}