<?php
require_once '../app/models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        echo $user->existsByEmail($email) ? 'existe' : 'disponivel';
    }

    if (isset($_POST['cpf'])) {
        $cpf = $_POST['cpf'];
        echo $user->existsByCPF($cpf) ? 'existe' : 'disponivel';
    }
}
?>