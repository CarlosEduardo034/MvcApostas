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

echo "<h2>Bem-vindo, administrador " . htmlspecialchars($_SESSION['user']['nome']) . "!</h2>";
?>
<a href="/apostas_mvc_completo/public/index.php?action=logout" style="display: inline-block; padding: 10px 20px; background-color: #c0392b; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px;">
    Sair
</a>