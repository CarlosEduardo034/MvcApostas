<h2>Editar Evento</h2>
<form method="POST" action="/apostas_mvc_completo/public/index.php?action=atualizar_evento">
    <input type="hidden" name="id" value="<?= $evento['id'] ?>">

    <label>Nome:</label><br>
    <input type="text" name="nome" value="<?= htmlspecialchars($evento['nome']) ?>" required><br><br>

    <label>Data e Hora:</label><br>
    <input type="datetime-local" name="data_evento" value="<?= date('Y-m-d\TH:i', strtotime($evento['data_evento'])) ?>" required><br><br>

    <label>Local:</label><br>
    <input type="text" name="local" value="<?= htmlspecialchars($evento['local']) ?>" required><br><br>

    <button type="submit">Atualizar Evento</button>
</form>