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

    case 'salvar_luta':
        require_once '../app/controllers/LutaController.php';
        $controller = new LutaController();
        $controller->salvar();
        break;

    case 'excluir_luta':
        require_once '../app/controllers/LutaController.php';
        $controller = new LutaController();
        $controller->excluir();
        break;

    case 'declarar_vencedor':
        require_once '../app/controllers/LutaController.php';
        $controller = new LutaController();
        $controller->declararVencedor();
        break;
    default:
        echo "Ação inválida.";
}
