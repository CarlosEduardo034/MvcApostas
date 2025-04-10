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

    case 'apostar':
        require_once '../app/controllers/ApostaController.php';
        $controller = new ApostaController();
        $controller->apostar();
        break;

    case 'salvar_evento':
        require_once '../app/controllers/EventoController.php';
        $controller = new EventoController();
        $controller->salvar();
        break;
        
    case 'excluir_evento':
        require_once '../app/controllers/EventoController.php';
        $controller = new EventoController();
        $controller->excluir();
        break;

    case 'editar_evento':
        require_once '../app/controllers/EventoController.php';
        $controller = new EventoController();
        $controller->editar();
        break;
        
    case 'atualizar_evento':
        require_once '../app/controllers/EventoController.php';
        $controller = new EventoController();
        $controller->atualizar();
        break;
    default:
        echo "Ação inválida.";
}
