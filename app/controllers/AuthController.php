<?php
class AuthController {
    public function showLogin() {
        include '../app/views/auth/login.php';
    }

    public function showRegister() {
        include '../app/views/auth/register.php';
    }

    public function register() {
        session_start();

        $nome  = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $cpf   = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_NUMBER_INT);
        $senha = filter_input(INPUT_POST, 'senha', FILTER_UNSAFE_RAW);
        $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_NUMBER_INT);
        $dataNascimento = filter_input(INPUT_POST, 'data_nascimento', FILTER_UNSAFE_RAW);
    
        if (!$nome || !$email || !$cpf || !$senha || !$telefone || !$dataNascimento) {
            $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
            header("Location: /apostas_mvc_completo/public/index.php?action=register");
            exit;
        }
    
        $user = new User();
    
        if ($user->existsByEmail($email)) {
            $_SESSION['mensagem'] = "Esse email já está sendo utilizado.";
            header("Location: /apostas_mvc_completo/public/index.php?action=register");
            exit;
        }
    
        if ($user->existsByCPF($cpf)) {
            $_SESSION['mensagem'] = "Esse CPF já está sendo utilizado.";
            header("Location: /apostas_mvc_completo/public/index.php?action=register");
            exit;
        }
    
        $data = [
            'nome' => $nome,
            'email' => $email,
            'cpf' => $cpf,
            'senha' => $senha,
            'telefone' => $telefone,
            'data_nascimento' => $dataNascimento
        ];
    
        if ($user->create($data)) {
            $_SESSION['mensagem'] = "Cadastro realizado com sucesso.";
            header("Location: /apostas_mvc_completo/public/index.php?action=login");
            exit;
        } else {
            $_SESSION['mensagem'] = "Erro ao cadastrar.";
            header("Location: /apostas_mvc_completo/public/index.php?action=register");
            exit;
        }
    }
    

    public function login() {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);

        if (!$email || !$senha) {
            echo "Email e senha são obrigatórios.";
            return;
        }

        $user = new User();
        $dados = $user->login($email, $senha);

        if ($dados) {
            session_start();
            $_SESSION['email'] = $dados['email'];
            $_SESSION['user'] = $dados;

            if ($dados['role'] === 'admin') {
                header("Location: /apostas_mvc_completo/public/index.php?action=dashboard");
            } else {
                header("Location: /apostas_mvc_completo/public/index.php?action=principal");
            }
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
