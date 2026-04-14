<?php
// backend/controllers/UsuarioController.php
require_once __DIR__ . '/../config/conexion.php';

class UsuarioController {
    private $pdo;

    public function __construct($conexion) {
        $this->pdo = $conexion;
    }

    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND estado = 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        // Nota: En producción usa password_verify, aquí validamos texto plano por tu SQL inicial
        if ($usuario && $password === $usuario['password']) {
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['rol'] = $usuario['rol'];
            
            return ["status" => "success", "message" => "Acceso concedido"];
        }

        return ["status" => "error", "message" => "Credenciales incorrectas"];
    }
}