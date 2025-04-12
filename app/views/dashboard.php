<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: /apostas_mvc_completo/public/index.php?action=login");
    exit;
}
if (trim(strtolower($_SESSION['user']['role'])) !== 'admin') {
    echo "Acesso restrito. Esta página é exclusiva para administradores.";
    exit;
}

require_once '../app/models/Luta.php';
require_once '../app/models/Evento.php'; 
$lutaModel = new Luta();
$lutas = $lutaModel->listarLutas();

$eventoModel = new Evento();
$eventos = $eventoModel->listarEventos();

$eventoSelecionado = $_GET['evento_id'] ?? null;
$statusSelecionado = $_GET['status'] ?? null;
$lutas = $lutaModel->listarLutas($eventoSelecionado, $statusSelecionado);

$tiposLuta = $lutaModel->listarTiposLuta();
$tipoSelecionado = $_GET['tipo_luta'] ?? '';

$dataUnica = $_GET['data_unica'] ?? '';

$lutas = array_filter($lutas, function($luta) use (
    $eventoSelecionado, $statusSelecionado, $tipoSelecionado, $dataUnica
) {
    if (!empty($eventoSelecionado) && $luta['evento_id'] != $eventoSelecionado) return false;
    if (!empty($statusSelecionado) && $luta['status'] != $statusSelecionado) return false;
    if (!empty($tipoSelecionado) && $luta['tipo_luta'] != $tipoSelecionado) return false;
    if (!empty($dataUnica)) {
        $dataLuta = date('Y-m-d', strtotime($luta['data_hora']));
        if ($dataLuta !== $dataUnica) return false;
    }

    return true;
});
echo "<h2>Bem-vindo, administrador " . htmlspecialchars($_SESSION['user']['nome']) . "!</h2>";
?>

<a href="/apostas_mvc_completo/public/index.php?action=logout">Sair</a>
<h2>Cadastrar Evento</h2>
<form method="POST" action="index.php?action=salvar_evento">
    <input type="text" name="nome" placeholder="Nome do Evento" required>
    <input type="datetime-local" name="data_evento" required min="<?= date('Y-m-d\TH:i') ?>">
    <input type="text" name="local" placeholder="Local do Evento" required>
    <button type="submit">Cadastrar</button>
</form>

<h3>Eventos Cadastrados</h3>
<?php
require_once '../app/models/Evento.php';
$eventoModel = new Evento();
$eventosListados = $eventoModel->listarEventos();
?>

<?php if (count($eventosListados) > 0): ?>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Nome do Evento</th>
                <th>Data e Hora</th>
                <th>Local</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eventosListados as $evento): ?>
                <tr>
                    <td><?= htmlspecialchars($evento['nome']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($evento['data_evento'])) ?></td>
                    <td><?= htmlspecialchars($evento['local']) ?></td>
                    <td>
                        <a href="/apostas_mvc_completo/public/index.php?action=editar_evento&id=<?= $evento['id'] ?>">Editar</a> |
                        <a href="/apostas_mvc_completo/public/index.php?action=excluir_evento&id=<?= $evento['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este evento?')">Excluir</a>
                    </td>   
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nenhum evento cadastrado ainda.</p>
<?php endif; ?>

<hr>

<h3>Cadastrar Nova Luta</h3>
<form action="/apostas_mvc_completo/public/index.php?action=salvar_luta" method="post">
<label for="evento_id">Evento (Campeonato):</label>
<select name="evento_id" id="evento_id" required>
    <option value="">Selecione um evento</option>
    <?php foreach ($eventos as $evento): ?>
        <option value="<?= $evento['id'] ?>" data-data-evento="<?= date('Y-m-d\TH:i', strtotime($evento['data_evento'])) ?>">
            <?= htmlspecialchars($evento['nome']) ?> - <?= date('d/m/Y H:i', strtotime($evento['data_evento'])) ?>
        </option>
    <?php endforeach; ?>
</select> <br> <br>

    <label>Tipo de Luta:</label><br>
    <select name="tipo_luta" required>
        <option value="">Selecione</option>
        <option value="Boxe">Boxe</option>
        <option value="MMA">MMA</option>
        <option value="Jiu-Jitsu">Jiu-Jitsu</option>
        <option value="Muay Thai">Muay Thai</option>
        <option value="Karatê">Karatê</option>
    </select><br><br>

    <label>Data e Hora:</label><br>
    <input type="datetime-local" name="data_hora" id="data_hora" required>
    <br><br>

    <label>Lutador 1 - Nome:</label><br>
    <input type="text" name="lutador1_nome" required><br>
    <label>Lutador 1 - Peso (kg):</label><br>
    <input type="number" name="lutador1_peso" step="0.1" required><br><br>

    <label>Lutador 2 - Nome:</label><br>
    <input type="text" name="lutador2_nome" required><br>
    <label>Lutador 2 - Peso (kg):</label><br>
    <input type="number" name="lutador2_peso" step="0.1" required><br><br>

    <button type="submit">Salvar Luta</button>
</form>
<script>
document.getElementById('evento_id').addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];
    const dataEvento = selectedOption.getAttribute('data-data-evento');

    if (dataEvento) {
        document.getElementById('data_hora').setAttribute('min', dataEvento);
    } else {
        document.getElementById('data_hora').removeAttribute('min');
    }
});
</script>

<h3>Lutas Cadastradas</h3>

<form method="get" action="/apostas_mvc_completo/public/index.php" style="margin-bottom: 20px;">
    <input type="hidden" name="action" value="dashboard">

    <label for="evento_id">Filtrar por Evento:</label>
    <select name="evento_id" id="evento_id">
        <option value="">Todos</option>
        <?php foreach ($eventos as $evento): ?>
            <option value="<?= $evento['id'] ?>" <?= (isset($_GET['evento_id']) && $_GET['evento_id'] == $evento['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($evento['nome']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="status">Status:</label>
    <select name="status" id="status">
        <option value="">Todos</option>
        <option value="pendente" <?= (isset($_GET['status']) && $_GET['status'] === 'pendente') ? 'selected' : '' ?>>Pendente</option>
        <option value="concluido" <?= (isset($_GET['status']) && $_GET['status'] === 'concluido') ? 'selected' : '' ?>>Concluído</option>
    </select>

    <label for="tipo_luta">Tipo de Luta:</label>
        <select name="tipo_luta" id="tipo_luta">
            <option value="">Todas</option>
            <?php foreach ($tiposLuta as $tipo): ?>
                <option value="<?= htmlspecialchars($tipo) ?>" <?= $tipoSelecionado === $tipo ? 'selected' : '' ?>>
                    <?= htmlspecialchars($tipo) ?>
                </option>
            <?php endforeach; ?>
        </select>

    <label for="data_unica">Data:</label>
    <input type="date" name="data_unica" id="data_unica" value="<?= $_GET['data_unica'] ?? '' ?>">
  
    <button type="submit">Filtrar</button>
</form>


<?php if (count($lutas) > 0): ?>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Evento</th>
                <th>Marcada para</th>
                <th>Lutador 1</th>
                <th>Peso</th>
                <th>Lutador 2</th>
                <th>Peso</th>
                <th>Modalidade</th>
                <th>Vencedor</th>
                <th>Status</th>
                <th>Ações</th>
                <th>Apostas no Lutador 1</th>
                <th>Apostas no Lutador 2</th>
                <th>Total Apostado</th>
                <th>Odds</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lutas as $luta): ?>
                <tr>
                    <td>
                        <?php if (!empty($luta['evento_nome'])): ?>
                            <?= htmlspecialchars($luta['evento_nome']) ?> - <?= date('d/m/Y H:i', strtotime($luta['evento_data'])) ?>
                        <?php else: ?>
                            <em>Evento não vinculado</em>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($luta['data_hora']) ?></td>
                    <td><?= htmlspecialchars($luta['lutador1_nome']) ?></td>
                    <td><?= htmlspecialchars($luta['lutador1_peso']) ?> kg</td>
                    <td><?= htmlspecialchars($luta['lutador2_nome']) ?></td>
                    <td><?= htmlspecialchars($luta['lutador2_peso']) ?> kg</td>
                    <td><?= htmlspecialchars($luta['tipo_luta']) ?></td>
                    <td>
                        <?= $luta['vencedor'] ? htmlspecialchars($luta[$luta['vencedor'].'_nome']) : 'Selecionar:' ?>
                        <form action="/apostas_mvc_completo/public/index.php?action=declarar_vencedor" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $luta['id'] ?>">
                                <select name="vencedor" required>
                                    <option value="">--Selecionar--</option>
                                    <option value="lutador1" <?= $luta['vencedor'] === 'lutador1' ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($luta['lutador1_nome']) ?>
                                    </option>
                                    <option value="lutador2" <?= $luta['vencedor'] === 'lutador2' ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($luta['lutador2_nome']) ?>
                                    </option>
                                </select>
                            <button type="submit">Salvar</button>
                        </form>
                    </td>
                    <td>
                        <?= $luta['status'] === 'concluido' ? 'Evento Concluído' : 'Evento Pendente' ?>
                    </td>
                    <td>
                        <a href="/apostas_mvc_completo/public/index.php?action=excluir_luta&id=<?= $luta['id'] ?>" 
                        style="color: red; text-decoration: none;">Excluir</a>
                    </td>
                    <td>R$ <?= number_format($luta['apostas_lutador1'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($luta['apostas_lutador2'], 2, ',', '.') ?></td>
                    <td>
                        R$ <?= number_format($luta['apostas_lutador1'] + $luta['apostas_lutador2'], 2, ',', '.') ?>
                    </td>
                    <td>
                        <p>
                            <?php echo $luta['lutador1_nome']; ?>: <?php echo $luta['odds_lutador1'];?> 
                            <br>
                            <?php echo $luta['lutador2_nome']; ?>: <?php echo $luta['odds_lutador2'];?>
                        </p>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>
        <?php
            if (!empty($statusSelecionado)) {
                echo "Nenhuma luta com status <strong>" . htmlspecialchars($statusSelecionado) . "</strong>.";
            } elseif (!empty($eventoSelecionado)) {
                echo "Nenhuma luta vinculada ao evento selecionado.";
            } else {
                echo "Nenhuma luta cadastrada ainda.";
            }
        ?>
    </p>
<?php endif; ?>
<?php if (empty($lutas)): ?>
    <p style="color: red;">Nenhuma luta encontrada com os filtros aplicados.</p>
<?php endif; ?>