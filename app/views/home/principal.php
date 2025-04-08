<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: /apostas_mvc_completo/public/index.php?action=login");
    exit;
}

echo "<h2>Bem-vindo ao site de apostas, " . $_SESSION['user']['nome'] . "!</h2>";
?>
<a href="/apostas_mvc_completo/public/index.php?action=logout">Sair</a>