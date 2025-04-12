<?php
require_once '../config/autoload.php';

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) ?? 'login';

switch ($action) {
    case 'login':
        $controller = new AuthController();
        $controller->showLogin();
        break;

    case 'register':
        $controller = new AuthController();
        $controller->showRegister();
        break;

    case 'do_register':
        $controller = new AuthController();
        $controller->register();
        break;

    case 'dashboard':
        require_once '../app/views/dashboard.php';
        break;

    case 'principal':
        require_once '../app/views/home/principal.php';
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'salvar_luta':
        $controller = new LutaController();
        $controller->salvar();
        break;

    case 'excluir_luta':
        $controller = new LutaController();
        $controller->excluir();
        break;

    case 'declarar_vencedor':
        $controller = new LutaController();
        $controller->declararVencedor();
        break;

    case 'apostar':
        $controller = new ApostaController();
        $controller->apostar();
        break;

    case 'salvar_evento':
        $controller = new EventoController();
        $controller->salvar();
        break;

    case 'excluir_evento':
        $controller = new EventoController();
        $controller->excluir();
        break;

    case 'editar_evento':
        $controller = new EventoController();
        $controller->editar();
        break;

    case 'atualizar_evento':
        $controller = new EventoController();
        $controller->atualizar();
        break;
        
    case 'do_login':
        $controller = new AuthController();
        $controller->login();
        break;
    default:
        echo "Ação inválida.";
}
