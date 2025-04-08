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
        $luta_id = $_POST['luta_id'];
        $escolha = $_POST['escolha'];
        $valor = floatval($_POST['valor']);

        $apostaModel = new Aposta();
        $apostaModel->registrarAposta($usuario_id, $luta_id, $valor, $escolha);

        // Atualizar os totais de apostas da luta
        $lutaModel = new Luta();
        $lutaModel->atualizarTotaisAposta($luta_id, $valor, $escolha);

        header("Location: /apostas_mvc_completo/public/index.php?action=principal");
        exit;
    }
}
