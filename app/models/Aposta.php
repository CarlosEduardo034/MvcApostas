<?php
require_once __DIR__ . '/../../config/database.php';

class Aposta {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function registrarAposta($usuario_id, $luta_id, $valor, $escolha) {
        $stmt = $this->conn->prepare("
            INSERT INTO apostas (usuario_id, luta_id, valor, escolha)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("iids", $usuario_id, $luta_id, $valor, $escolha);
        return $stmt->execute();
    }
}
