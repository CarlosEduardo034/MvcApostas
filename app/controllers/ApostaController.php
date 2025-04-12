<?php
require_once '../app/models/Aposta.php';
require_once '../app/models/Luta.php';

class ApostaController {
    public function apostar() {
        session_start();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
            echo "Acesso negado.";
            exit;
        }

        $usuario_id = $_SESSION['user']['idUsuarios'];
        $luta_id = isset($_POST['luta_id']) ? intval($_POST['luta_id']) : null;
        $escolha = $_POST['escolha'] ?? null;
        $valor = isset($_POST['valor']) ? floatval($_POST['valor']) : 0;

        if (!$luta_id || !in_array($escolha, ['lutador1', 'lutador2']) || $valor <= 0) {
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
