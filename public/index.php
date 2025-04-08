<?php
$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'login':
        require_once '../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->showLogin();
        break;

    case 'register':
        require_once '../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->showRegister();
        break;

    case 'do_register':
        require_once '../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->register();
        break;

    case 'do_login':
        require_once '../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;

    case 'dashboard':
        require_once '../app/views/dashboard.php';
        break;

    case 'principal':
        require_once '../app/views/home/principal.php';
        break;

    case 'logout':
        require_once '../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    default:
        echo "Ação inválida.";
}
