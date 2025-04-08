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
            INSERT INTO lutas (
                data_hora, tipo_luta, lutador1_nome, lutador1_peso,
                lutador2_nome, lutador2_peso, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $status = 'pendente';
        $stmt->bind_param(
            "sssssss",
            $data['data_hora'],
            $data['tipo_luta'],
            $data['lutador1_nome'],
            $data['lutador1_peso'],
            $data['lutador2_nome'],
            $data['lutador2_peso'],
            $status
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
        $stmt = $this->conn->prepare("
            UPDATE lutas 
            SET vencedor = ?, status = 'concluido' 
            WHERE id = ?
        ");
        $stmt->bind_param("si", $vencedor, $id);
        return $stmt->execute();
    }
}
?>
