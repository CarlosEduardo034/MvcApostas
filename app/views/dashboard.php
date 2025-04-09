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

echo "<h2>Bem-vindo, administrador " . htmlspecialchars($_SESSION['user']['nome']) . "!</h2>";
?>

<a href="/apostas_mvc_completo/public/index.php?action=logout">Sair</a>
<h2>Cadastrar Evento</h2>
<form method="POST" action="index.php?action=salvar_evento">
    <input type="text" name="nome" placeholder="Nome do Evento" required>
    <input type="datetime-local" name="data_evento" required>
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
                        <a href="index.php?action=excluir_evento&id=<?= $evento['id'] ?>" 
                           style="color: red; text-decoration: none;">
                            Excluir
                        </a>
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
<select name="evento_id" required>
    <option value="">Selecione um evento</option>
    <?php foreach ($eventos as $evento): ?>
        <option value="<?= $evento['id'] ?>">
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
    <input type="datetime-local" name="data_hora" required min="<?= date('Y-m-d\TH:i') ?>"><br><br>

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

<h3>Lutas Cadastradas</h3>
<?php if (count($lutas) > 0): ?>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Data e Hora</th>
                <th>Lutador 1</th>
                <th>Descrição 1</th>
                <th>Lutador 2</th>
                <th>Descrição 2</th>
                <th>Modalidade</th>
                <th>Ações</th>
                <th>Vencedor</th>
                <th>Status</th>
                <th>Apostas no Lutador 1</th>
                <th>Apostas no Lutador 2</th>
                <th>Total Apostado</th>
                <th>Evento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lutas as $luta): ?>
                <tr>
                    <td><?= htmlspecialchars($luta['data_hora']) ?></td>
                    <td><?= htmlspecialchars($luta['lutador1_nome']) ?></td>
                    <td><?= htmlspecialchars($luta['lutador1_peso']) ?> kg</td>
                    <td><?= htmlspecialchars($luta['lutador2_nome']) ?></td>
                    <td><?= htmlspecialchars($luta['lutador2_peso']) ?> kg</td>
                    <td><?= htmlspecialchars($luta['tipo_luta']) ?></td>
                    <td>
                        <a href="/apostas_mvc_completo/public/index.php?action=excluir_luta&id=<?= $luta['id'] ?>" 
                        style="color: red; text-decoration: none;">Excluir</a>
                    </td>
                    <td>
                        <?= $luta['vencedor'] ? htmlspecialchars($luta[$luta['vencedor'].'_nome']) : 'Selecionar:' ?>
                        <?php if (!$luta['vencedor']): ?>
                            <form action="/apostas_mvc_completo/public/index.php?action=declarar_vencedor" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $luta['id'] ?>">
                                <select name="vencedor" required>
                                    <option value="">--Selecionar--</option>
                                    <option value="lutador1"><?= htmlspecialchars($luta['lutador1_nome']) ?></option>
                                    <option value="lutador2"><?= htmlspecialchars($luta['lutador2_nome']) ?></option>
                                </select>
                                <button type="submit">Salvar</button>
                            </form>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= $luta['status'] === 'concluido' ? 'Evento Concluído' : 'Evento Pendente' ?>
                    </td>
                    <td>R$ <?= number_format($luta['apostas_lutador1'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($luta['apostas_lutador2'], 2, ',', '.') ?></td>
                    <td>
                        R$ <?= number_format($luta['apostas_lutador1'] + $luta['apostas_lutador2'], 2, ',', '.') ?>
                    </td>
                    <td>
                        <?php if (!empty($luta['evento_nome'])): ?>
                            <?= htmlspecialchars($luta['evento_nome']) ?> - <?= date('d/m/Y H:i', strtotime($luta['evento_data'])) ?>
                        <?php else: ?>
                            <em>Evento não vinculado</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nenhuma luta cadastrada ainda.</p>
<?php endif; ?>