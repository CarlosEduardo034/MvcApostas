<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: /apostas_mvc_completo/public/index.php?action=login");
    exit;
}

require_once '../app/models/Luta.php';

$lutaModel = new Luta();
$lutas = $lutaModel->listarLutas();
?>
<a href="/apostas_mvc_completo/public/index.php?action=logout">Sair</a>
<h2>Bem-vindo, <?= htmlspecialchars($_SESSION['user']['nome']) ?>!</h2>

<h3>Lutas Disponíveis para Apostar</h3>

<?php foreach ($lutas as $luta): ?>
    <?php if ($luta['status'] === 'pendente'): ?>
        <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 10px;">
            <strong><?= htmlspecialchars($luta['tipo_luta']) ?></strong><br>
            <strong><?= date('d/m/Y H:i', strtotime($luta['data_hora'])) ?></strong><br>
            <em>
                Evento: <?= htmlspecialchars($luta['evento_nome']) ?> -
                <?= date('d/m/Y H:i', strtotime($luta['evento_data'])) ?>
            </em><br>
            <?= htmlspecialchars($luta['lutador1_nome']) ?> (<?= $luta['lutador1_peso'] ?> kg) x 
            <?= htmlspecialchars($luta['lutador2_nome']) ?> (<?= $luta['lutador2_peso'] ?> kg)<br><br>

            <form action="/apostas_mvc_completo/public/index.php?action=apostar" method="post">
                <input type="hidden" name="luta_id" value="<?= $luta['id'] ?>">
                <label>Escolha seu lutador:</label><br>
                <input type="radio" name="escolha" value="lutador1" required> <?= $luta['lutador1_nome'] ?><br>
                <input type="radio" name="escolha" value="lutador2" required> <?= $luta['lutador2_nome'] ?><br><br>

                <label>Valor da Aposta (R$):</label><br>
                <input type="number" name="valor" min="1" step="0.01" required><br><br>
                <button type="submit">Fazer Aposta</button>
            </form>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

