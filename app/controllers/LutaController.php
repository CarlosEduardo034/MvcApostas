<?php
class LutaController {
    public function salvar() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo "Acesso restrito.";
            exit;
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!$token || $token !== $_SESSION['csrf_token']) {
            $_SESSION['mensagem'] = "Token CSRF inválido.";
            header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
            exit;
        }
    
        $eventoId = filter_input(INPUT_POST, 'evento_id', FILTER_VALIDATE_INT);
        $dataHoraLuta = filter_input(INPUT_POST, 'data_hora', FILTER_SANITIZE_STRING);
    
        if (!$eventoId || !$dataHoraLuta) {
            $_SESSION['mensagem'] = "Dados inválidos.";
            header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
            exit;
        }
    
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
            $_SESSION['mensagem'] = "Erro: Evento não encontrado.";
            header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
            exit;
        }
    
        if ($dataHoraLuta < $eventoSelecionado['data_evento']) {
            $_SESSION['mensagem'] = "A luta não pode ocorrer antes da data do evento.";
            header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
            exit;
        }
    
        $data = [
            'evento_id' => $eventoId,
            'data_hora' => $dataHoraLuta,
            'tipo_luta' => filter_input(INPUT_POST, 'tipo_luta', FILTER_SANITIZE_STRING),
            'lutador1_nome' => filter_input(INPUT_POST, 'lutador1_nome', FILTER_SANITIZE_SPECIAL_CHARS),
            'lutador1_peso' => filter_input(INPUT_POST, 'lutador1_peso', FILTER_VALIDATE_FLOAT),
            'lutador2_nome' => filter_input(INPUT_POST, 'lutador2_nome', FILTER_SANITIZE_SPECIAL_CHARS),
            'lutador2_peso' => filter_input(INPUT_POST, 'lutador2_peso', FILTER_VALIDATE_FLOAT),
        ];
    
        if (!$data['tipo_luta'] || !$data['lutador1_nome'] || !$data['lutador1_peso'] ||
            !$data['lutador2_nome'] || !$data['lutador2_peso']) {
            $_SESSION['mensagem'] = "Todos os campos devem ser preenchidos corretamente.";
            header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
            exit;
        }
    
        $lutaModel = new Luta();
        $lutaModel->criarLuta($data);
    
        $_SESSION['mensagem'] = "Luta cadastrada com sucesso.";
        header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
        exit;
    }    

    public function excluir() {
        session_start();
    
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['mensagem'] = "Acesso restrito.";
            header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
            exit;
        }
    
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
        if (!$id) {
            $_SESSION['mensagem'] = "ID inválido.";
            header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
            exit;
        }
    
        $db = new Database();
        $conn = $db->connect();
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM apostas WHERE luta_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
    
        if ($data['total'] > 0) {
            $_SESSION['mensagem'] = "Não é possível apagar uma luta que possui apostas.";
            header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
            exit;
        }
    
        $luta = new Luta();
        $luta->excluirLuta($id);
    
        $_SESSION['mensagem'] = "Luta excluída com sucesso.";
        header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
        exit;
    }
    

    public function declararVencedor() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo "Acesso restrito.";
            exit;
        }
    
        $token = $_POST['csrf_token'] ?? '';
        if (!$token || $token !== $_SESSION['csrf_token']) {
            $_SESSION['mensagem'] = "Token CSRF inválido.";
            header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
            exit;
        }
    
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $vencedor = filter_input(INPUT_POST, 'vencedor', FILTER_SANITIZE_STRING);
    
        if (!$id || ($vencedor !== 'lutador1' && $vencedor !== 'lutador2')) {
            $_SESSION['mensagem'] = "Dados inválidos para declarar vencedor.";
            header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
            exit;
        }
    
        $model = new Luta();
        $model->atualizarVencedor($id, $vencedor);
    
        $_SESSION['mensagem'] = "Vencedor declarado com sucesso.";
        header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
        exit;
    }

    public function novo() {
        session_start();
    
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['mensagem'] = "Acesso restrito.";
            header("Location: /apostas_mvc_completo/public/index.php?action=login");
            exit;
        }
    
        require_once __DIR__ . '/../models/Evento.php';
        $eventoModel = new Evento();
        $eventos = $eventoModel->listarEventos();
    
        require_once __DIR__ . '/../views/cadastrar_luta.php';
    }
    
}
?>
