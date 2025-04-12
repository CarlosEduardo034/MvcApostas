<?php
class ApostaController {
    public function apostar() {
        session_start();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
            $_SESSION['mensagem'] = "Acesso negado.";
            header("Location: /apostas_mvc_completo/public/index.php?action=login");
            exit;
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!$token || $token !== ($_SESSION['csrf_token'] ?? '')) {
            $_SESSION['mensagem'] = "Token CSRF inválido.";
            header("Location: /apostas_mvc_completo/public/index.php?action=principal");
            exit;
        }

        $usuario_id = $_SESSION['user']['idUsuarios'];
        $luta_id = filter_input(INPUT_POST, 'luta_id', FILTER_VALIDATE_INT);
        $escolha = filter_input(INPUT_POST, 'escolha', FILTER_SANITIZE_STRING);
        $valor = filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT);

        if (!$luta_id || !$valor || !in_array($escolha, ['lutador1', 'lutador2']) || $valor <= 0) {
            $_SESSION['mensagem'] = "Dados inválidos para aposta.";
            header("Location: /apostas_mvc_completo/public/index.php?action=principal");
            exit;
        }

        $apostaModel = new Aposta();
        $lutaModel = new Luta();

        $sucesso = $apostaModel->registrarAposta($usuario_id, $luta_id, $valor, $escolha);

        if ($sucesso) {
            $lutaModel->atualizarTotaisAposta($luta_id, $valor, $escolha);
            $_SESSION['mensagem'] = "Aposta registrada com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Erro ao registrar aposta.";
        }

        header("Location: /apostas_mvc_completo/public/index.php?action=principal");
        exit;
    }
}
