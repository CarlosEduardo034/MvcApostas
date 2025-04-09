<?php
require_once '../app/models/Luta.php';

class LutaController {
    public function salvar() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo "Acesso restrito.";
            exit;
        }
    
        $dataHora = $_POST['data_hora'];
        $agora = date('Y-m-d H:i:s');
    
        if ($dataHora < $agora) {
            echo "Erro: A luta não pode ser cadastrada com data/hora anterior à atual.";
            echo '<br><a href="/apostas_mvc_completo/public/index.php?action=dashboard">Voltar</a>';
            exit;
        }
    
        $lutaModel = new Luta();
        $lutaModel->criarLuta($_POST);
    
        header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
        exit;
    }
    public function excluir() {
        $id = $_GET['id'] ?? null;
    
        if ($id) {
            $db = new Database();
            $conn = $db->connect();
            $stmt = $conn->prepare("SELECT COUNT(*) as total FROM apostas WHERE luta_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
    
            if ($data['total'] > 0) {
                echo "<script>
                    alert('Não é possível apagar uma luta que possui apostas.');
                    window.location.href = 'index.php?action=dashboard';
                </script>";
                return;
            }
    
            // Exclusão segura
            $luta = new Luta();
            $luta->excluirLuta($id);
    
            echo "<script>
                alert('Luta excluída com sucesso.');
                window.location.href = 'index.php?action=dashboard';
            </script>";
        }
    }
    public function declararVencedor() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo "Acesso restrito.";
            exit;
        }
    
        $id = $_POST['id'];
        $vencedor = $_POST['vencedor'];
    
        if ($vencedor !== 'lutador1' && $vencedor !== 'lutador2') {
            echo "Vencedor inválido.";
            exit;
        }
    
        $model = new Luta();
        $model->atualizarVencedor($id, $vencedor);
    
        header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
        exit;
    }

}
?>