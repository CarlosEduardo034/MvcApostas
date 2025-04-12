<?php
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
        $success = $stmt->execute();

        if ($success) {
            $lutaModel = new Luta();
            $lutaModel->atualizarTotaisAposta($luta_id, $valor, $escolha);
        }

        return $success;
    }

    public function atualizarTotaisAposta($luta_id, $valor, $escolha) {
        $campo = $escolha === 'lutador1' ? 'apostas_lutador1' : 'apostas_lutador2';
    
        $stmt = $this->conn->prepare("
            UPDATE lutas SET $campo = $campo + ? WHERE id = ?
        ");
        $stmt->bind_param("di", $valor, $luta_id);
        return $stmt->execute();
    }
    
}
