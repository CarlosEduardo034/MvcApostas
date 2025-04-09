<?php
require_once '../app/models/Evento.php';

class EventoController {
    public function salvar() {
        $eventoModel = new Evento();
        $eventoModel->criarEvento($_POST);
        header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
        exit;
    }

    public function excluir() {
        if (isset($_GET['id'])) {
            $eventoModel = new Evento();
            $eventoModel->excluirEvento($_GET['id']);

            echo "<script>
                alert('Evento excluído com sucesso.');
                window.location.href = 'index.php?action=dashboard';
            </script>";
        } else {
            echo "ID do evento não fornecido.";
        }
    }
}
