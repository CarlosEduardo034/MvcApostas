<?php
require_once __DIR__ . '/../../config/database.php';

class Luta {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function criarLuta($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO lutas (data_hora, lutador1_nome, lutador1_desc, lutador2_nome, lutador2_desc)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "sssss",
            $data['data_hora'],
            $data['lutador1_nome'],
            $data['lutador1_desc'],
            $data['lutador2_nome'],
            $data['lutador2_desc']
        );

        return $stmt->execute();
    }
    public function listarLutas() {
        $sql = "SELECT * FROM lutas ORDER BY data_hora DESC";
        $result = $this->conn->query($sql);
    
        $lutas = [];
        while ($row = $result->fetch_assoc()) {
            $lutas[] = $row;
        }
    
        return $lutas;
    }
    public function excluirLuta($id) {
        $stmt = $this->conn->prepare("DELETE FROM lutas WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public function atualizarVencedor($id, $vencedor) {
        $stmt = $this->conn->prepare("UPDATE lutas SET vencedor = ? WHERE id = ?");
        $stmt->bind_param("si", $vencedor, $id);
        return $stmt->execute();
    }
}
?>
