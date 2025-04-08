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

$lutaModel = new Luta();
$lutas = $lutaModel->listarLutas();

echo "<h2>Bem-vindo, administrador " . htmlspecialchars($_SESSION['user']['nome']) . "!</h2>";
?>

<a href="/apostas_mvc_completo/public/index.php?action=logout">Sair</a>

<h3>Cadastrar Nova Luta</h3>
<form action="/apostas_mvc_completo/public/index.php?action=salvar_luta" method="post">
    <label>Data e Hora:</label><br>
    <input type="datetime-local" name="data_hora" required min="<?= date('Y-m-d\TH:i') ?>"><br><br>

    <label>Lutador 1 - Nome:</label><br>
    <input type="text" name="lutador1_nome" required><br>
    <label>Lutador 1 - Descrição:</label><br>
    <textarea name="lutador1_desc"></textarea><br><br>

    <label>Lutador 2 - Nome:</label><br>
    <input type="text" name="lutador2_nome" required><br>
    <label>Lutador 2 - Descrição:</label><br>
    <textarea name="lutador2_desc"></textarea><br><br>

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
                <th>Ações</th>
                <th>Vencedor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lutas as $luta): ?>
                <tr>
                    <td><?= htmlspecialchars($luta['data_hora']) ?></td>
                    <td><?= htmlspecialchars($luta['lutador1_nome']) ?></td>
                    <td><?= nl2br(htmlspecialchars($luta['lutador1_desc'])) ?></td>
                    <td><?= htmlspecialchars($luta['lutador2_nome']) ?></td>
                    <td><?= nl2br(htmlspecialchars($luta['lutador2_desc'])) ?></td>
                    <td>
                        <a href="/apostas_mvc_completo/public/index.php?action=excluir_luta&id=<?= $luta['id'] ?>" 
                        onclick="return confirm('Tem certeza que deseja excluir esta luta?');"
                        style="color: red; text-decoration: none;">Excluir</a>
                    </td>
                    <td>
                        <?= $luta['vencedor'] ? htmlspecialchars($luta[$luta['vencedor'].'_nome']) : 'Vencedor' ?>
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
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nenhuma luta cadastrada ainda.</p>
<?php endif; ?>