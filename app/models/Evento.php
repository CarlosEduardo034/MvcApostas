<?php
require_once __DIR__ . '/../../config/database.php';

class Evento {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function criarEvento($data) {
        $stmt = $this->conn->prepare("INSERT INTO eventos (nome, data_evento, local) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $data['nome'], $data['data_evento'], $data['local']);
        return $stmt->execute();
    }

    public function listarEventos() {
        $stmt = $this->conn->prepare("SELECT * FROM eventos ORDER BY data_evento ASC");
        $stmt->execute();
        $result = $stmt->get_result();
    
        $eventos = [];
        while ($row = $result->fetch_assoc()) {
            $eventos[] = $row;
        }
    
        return $eventos;
    }

    public function excluirEvento($id) {
        $stmt = $this->conn->prepare("DELETE FROM eventos WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
