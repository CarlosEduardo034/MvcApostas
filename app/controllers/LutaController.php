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
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo "Acesso restrito.";
            exit;
        }
    
        if (!isset($_GET['id'])) {
            echo "ID da luta não fornecido.";
            exit;
        }
    
        $lutaModel = new Luta();
        $lutaModel->excluirLuta($_GET['id']);
    
        header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
        exit;
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