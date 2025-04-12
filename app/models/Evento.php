<?php
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

    public function excluirEvento() {
        $id = $_GET['id'] ?? null;
    
        if ($id) {
            $db = new Database();
            $conn = $db->connect();
    
            $stmt = $conn->prepare("SELECT COUNT(*) as total FROM lutas WHERE evento_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
    
            if ($data['total'] > 0) {
                echo "<script>
                    alert('Não é possível excluir este evento, pois ele possui lutas cadastradas.');
                    window.location.href = 'index.php?action=dashboard';
                </script>";
                return;
            }
    
            $stmt = $conn->prepare("DELETE FROM eventos WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
    
            echo "<script>
                alert('Evento excluído com sucesso.');
                window.location.href = 'index.php?action=dashboard';
            </script>";
        }
    }
    public function buscarEventoPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM eventos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function atualizarEvento($id, $nome, $data_evento, $local) {
        $stmt = $this->conn->prepare("UPDATE eventos SET nome = ?, data_evento = ?, local = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nome, $data_evento, $local, $id);
        return $stmt->execute();
    }
    
    
}
?>
