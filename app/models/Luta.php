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
            INSERT INTO lutas 
            (evento_id, tipo_luta, data_hora, lutador1_nome, lutador1_peso, lutador2_nome, lutador2_peso) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
    
        $stmt->bind_param(
            "isssdsd", 
            $data['evento_id'],
            $data['tipo_luta'],
            $data['data_hora'],
            $data['lutador1_nome'],
            $data['lutador1_peso'],
            $data['lutador2_nome'],
            $data['lutador2_peso']
        );
    
        return $stmt->execute();
    }
    
    public function listarLutas() {
        $sql = "
        SELECT l.*, 
           IFNULL(SUM(CASE WHEN a.escolha = 'lutador1' THEN a.valor ELSE 0 END), 0) AS apostas_lutador1,
           IFNULL(SUM(CASE WHEN a.escolha = 'lutador2' THEN a.valor ELSE 0 END), 0) AS apostas_lutador2,
           e.nome AS evento_nome,
           e.data_evento AS evento_data
    FROM lutas l
    LEFT JOIN apostas a ON l.id = a.luta_id
    LEFT JOIN eventos e ON l.evento_id = e.id
    GROUP BY l.id
    ORDER BY l.data_hora ASC
";

        $result = $this->conn->query($sql);
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
