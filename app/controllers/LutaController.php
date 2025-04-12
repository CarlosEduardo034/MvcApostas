<?php
class LutaController {
    public function salvar() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo "Acesso restrito.";
            exit;
        }

        $eventoId = filter_input(INPUT_POST, 'evento_id', FILTER_VALIDATE_INT);
        $dataHoraLuta = filter_input(INPUT_POST, 'data_hora', FILTER_SANITIZE_STRING);

        if (!$eventoId || !$dataHoraLuta) {
            echo "Dados inválidos.";
            exit;
        }

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
            'tipo_luta' => filter_input(INPUT_POST, 'tipo_luta', FILTER_SANITIZE_STRING),
            'lutador1_nome' => filter_input(INPUT_POST, 'lutador1_nome', FILTER_SANITIZE_SPECIAL_CHARS),
            'lutador1_peso' => filter_input(INPUT_POST, 'lutador1_peso', FILTER_VALIDATE_FLOAT),
            'lutador2_nome' => filter_input(INPUT_POST, 'lutador2_nome', FILTER_SANITIZE_SPECIAL_CHARS),
            'lutador2_peso' => filter_input(INPUT_POST, 'lutador2_peso', FILTER_VALIDATE_FLOAT),
        ];

        if (!$data['tipo_luta'] || !$data['lutador1_nome'] || !$data['lutador1_peso'] ||
            !$data['lutador2_nome'] || !$data['lutador2_peso']) {
            echo "Todos os campos devem ser preenchidos corretamente...";
            exit;
        }

        $lutaModel = new Luta();
        $lutaModel->criarLuta($data);

        header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
        exit;
    }

    public function excluir() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

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
        } else {
            echo "ID inválido.";
        }
    }

    public function declararVencedor() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo "Acesso restrito.";
            exit;
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $vencedor = filter_input(INPUT_POST, 'vencedor', FILTER_SANITIZE_STRING);

        if (!$id || ($vencedor !== 'lutador1' && $vencedor !== 'lutador2')) {
            echo "Dados inválidos.";
            exit;
        }

        $model = new Luta();
        $model->atualizarVencedor($id, $vencedor);

        header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
        exit;
    }

    public function novo() {
        require_once __DIR__ . '/../models/Evento.php';
        $eventoModel = new Evento();
        $eventos = $eventoModel->listarEventos();

        require_once __DIR__ . '/../views/cadastrar_luta.php';
    }
}
?>
