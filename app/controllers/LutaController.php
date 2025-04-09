<?php
require_once '../app/models/Luta.php';

class LutaController {
    public function salvar() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo "Acesso restrito.";
            exit;
        }
    
        $eventoId = $_POST['evento_id'];
        $dataHoraLuta = $_POST['data_hora'];
    
        require_once __DIR__ . '/../models/Evento.php';
        $eventoModel = new Evento();
        $eventos = $eventoModel->listarEventos();

        $eventoSelecionado = null;
        foreach ($eventos as $evento) {
            if ($evento['id'] == $eventoId) {
                $eventoSelecionado = $evento;
                break;
            }
        }
    
        if (!$eventoSelecionado) {
            echo "<script>
                alert('Erro: Evento não encontrado.');
                window.location.href = '/apostas_mvc_completo/public/index.php?action=dashboard';
            </script>";
            exit;
        }
    
        if ($dataHoraLuta < $eventoSelecionado['data_evento']) {
            echo "<script>
                alert('Erro: A luta não pode ocorrer antes da data do evento.');
                window.location.href = '/apostas_mvc_completo/public/index.php?action=dashboard';
            </script>";
            exit;
        }
    
        $data = [
            'evento_id' => $eventoId,
            'data_hora' => $dataHoraLuta,
            'tipo_luta' => $_POST['tipo_luta'],
            'lutador1_nome' => $_POST['lutador1_nome'],
            'lutador1_peso' => $_POST['lutador1_peso'],
            'lutador2_nome' => $_POST['lutador2_nome'],
            'lutador2_peso' => $_POST['lutador2_peso']
        ];
    
        $lutaModel = new Luta();
        $lutaModel->criarLuta($data);
    
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
    public function novo(){
        require_once __DIR__ . '/../models/Evento.php';
        $eventoModel = new Evento();
        $eventos = $eventoModel->listarEventos();
    
        require_once __DIR__ . '/../views/cadastrar_luta.php';
    }
}
?>