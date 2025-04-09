<?php
require_once '../app/models/Evento.php';

class EventoController {
    public function salvar() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo "Acesso restrito.";
            exit;
        }
    
        $dataEvento = date('Y-m-d H:i:s', strtotime($_POST['data_evento']));
        $agora = date('Y-m-d H:i:s');
    
        if ($dataEvento < $agora) {
            echo "<script>
                alert('Erro: O evento não pode ser cadastrado com data/hora anterior à atual.');
                window.location.href = '/apostas_mvc_completo/public/index.php?action=dashboard';
            </script>";
            exit;
        }
    
        require_once '../app/models/Evento.php';
        $eventoModel = new Evento();
        $eventoModel->criarEvento([
            'nome' => $_POST['nome'],
            'data_evento' => $dataEvento,
            'local' => $_POST['local']
        ]);
    
        echo "<script>
            alert('Evento cadastrado com sucesso!');
            window.location.href = '/apostas_mvc_completo/public/index.php?action=dashboard';
        </script>";
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
    public function editar() {
        $id = $_GET['id'] ?? null;
    
        if ($id) {
            $model = new Evento();
            $evento = $model->buscarEventoPorId($id);
    
            if ($evento) {
                require_once __DIR__ . '/../views/editar_evento.php';
            } else {
                echo "<script>
                    alert('Evento não encontrado.');
                    window.location.href = 'index.php?action=dashboard';
                </script>";
            }
        }
    }
    
    public function atualizar() {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $data_evento = $_POST['data_evento'];
        $local = $_POST['local'];
    
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
