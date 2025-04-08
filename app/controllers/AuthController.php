<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    public function showLogin() {
        include '../app/views/auth/login.php';
    }

    public function showRegister() {
        include '../app/views/auth/register.php';
    }

    public function register() {
        $user = new User();
        $data = $_POST;
    
        if ($user->existsByEmail($data['email'])) {
            echo "Esse email já está sendo utilizado";
            return;
        }
    
        if ($user->existsByCPF($data['cpf'])) {
            echo "Esse CPF já está sendo utilizado";
            return;
        }
    
        if ($user->create($data)) {
            header("Location: /apostas_mvc_completo/public/index.php?action=login");
            exit;
        } else {
            echo "Erro ao cadastrar.";
        }
    }

    public function login() {
        $user = new User();
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $dados = $user->login($email, $senha);

        if ($dados) {
            session_start();
            $_SESSION['email'] = $dados['email'];
            $_SESSION['user'] = $dados; // agora é possível validar no dashboard
            header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
            exit;
        } else {
            echo "Email ou senha incorretos.";
            exit;
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();
        header("Location: /apostas_mvc_completo/public/index.php?action=login");
        exit;
    }
}
