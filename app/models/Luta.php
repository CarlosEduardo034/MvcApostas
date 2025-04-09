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
        $query = "SELECT * FROM lutas ORDER BY data_hora DESC";
        $result = $this->conn->query($query);
    
        $lutas = [];
        while ($row = $result->fetch_assoc()) {
            $lutas[] = $row;
        }
    
        return $lutas;
    }
    public function excluirLuta($id) {
        $stmt = $this->conn->prepare("DELETE FROM lutas WHERE id = ?");
        if (!$stmt) {
            die("Erro ao preparar: " . $this->conn->error);
        }
    
        $stmt->bind_param("i", $id);
    
        if (!$stmt->execute()) {
            die("Erro ao executar: " . $stmt->error);
        }
    
        $stmt->close();
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
    public function atualizarTotaisAposta($luta_id, $valor, $escolha) {
        $campo = $escolha === 'lutador1' ? 'apostas_lutador1' : 'apostas_lutador2';
    
        $stmt = $this->conn->prepare("
            UPDATE lutas SET $campo = $campo + ? WHERE id = ?
        ");
        $stmt->bind_param("di", $valor, $luta_id);
        return $stmt->execute();
    }
    
}
?>
