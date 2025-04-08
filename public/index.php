<?php
require_once '../app/controllers/AuthController.php';

$action = $_GET['action'] ?? 'login';
$auth = new AuthController();

switch ($action) {
    case 'login':
        $auth->showLogin();
        break;
    case 'register':
        $auth->showRegister();
        break;
    case 'do_register':
        $auth->register();
        break;
    case 'do_login':
        $auth->login();
        break;
    case 'logout':
        $auth->logout();
        break;
    case 'dashboard':
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: /apostas_mvc_completo/public/index.php?action=login");
            exit;
        } else {
            include '../app/views/dashboard.php';
        }
        break;
    default:
        echo "Página não encontrada.";
}
