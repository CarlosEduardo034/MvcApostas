<?php
class EventoController {
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
    
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $data_evento = filter_input(INPUT_POST, 'data_evento', FILTER_SANITIZE_STRING);
        $local = filter_input(INPUT_POST, 'local', FILTER_SANITIZE_STRING);
    
        if (!$nome || !$data_evento || !$local) {
            $_SESSION['mensagem'] = "Preencha todos os campos corretamente.";
            header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
            exit;
        }
    
        $dataEventoFormatada = date('Y-m-d H:i:s', strtotime($data_evento));
        $agora = date('Y-m-d H:i:s');
    
        if ($dataEventoFormatada < $agora) {
            $_SESSION['mensagem'] = "Erro: A data do evento deve ser futura.";
            header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
            exit;
        }
    
        $eventoModel = new Evento();
        $eventoModel->criarEvento([
            'nome' => $nome,
            'data_evento' => $dataEventoFormatada,
            'local' => $local
        ]);
    
        $_SESSION['mensagem'] = "Evento cadastrado com sucesso!";
        header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
        exit;
    }
    

    public function excluir() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            $eventoModel = new Evento();
            $eventoModel->excluirEvento($id);

            echo "<script>
                alert('Evento excluído com sucesso.');
                window.location.href = 'index.php?action=dashboard';
            </script>";
        } else {
            echo "ID do evento inválido.";
        }
    }

    public function editar() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            $model = new Evento();
            $evento = $model->buscarEventoPorId($id);

            if ($evento) {
                require_once __DIR__ . '/../views/editar_evento.php';
            } else {
                echo "<script>
                    alert('Evento não encontrado...');
                    window.location.href = 'index.php?action=dashboard';
                </script>";
            }
        } else {
            echo "ID inválido.";
        }
    }

    public function atualizar() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $data_evento = filter_input(INPUT_POST, 'data_evento', FILTER_SANITIZE_STRING);
        $local = filter_input(INPUT_POST, 'local', FILTER_SANITIZE_STRING);

        if (!$id || !$nome || !$data_evento || !$local) {
            echo "<script>
                alert('Todos os campos devem ser preenchidos corretamente.');
                window.location.href = 'index.php?action=editar_evento&id=$id';
            </script>";
            return;
        }

        if (strtotime($data_evento) < time()) {
            echo "<script>
                alert('A data do evento não pode ser anterior à data atual.');
                window.location.href = 'index.php?action=editar_evento&id=$id';
            </script>";
            return;
        }

        $model = new Evento();
        $model->atualizarEvento($id, $nome, $data_evento, $local);

        echo "<script>
            alert('Evento atualizado com sucesso.');
            window.location.href = 'index.php?action=dashboard';
        </script>";
    }
}
?>
