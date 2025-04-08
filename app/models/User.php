<?php
require_once __DIR__ . '/../../config/database.php';

class User {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function create($data) {
        $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        $role = isset($data['role']) ? $data['role'] : 'user';

        $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, telefone, data_nasc, cpf, senha, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $data['nome'], $data['email'], $data['telefone'], $data['data_nascimento'], $data['cpf'], $data['senha'], $role);
        return $stmt->execute();
    }

    public function existsByEmail($email) {
        $stmt = $this->conn->prepare("SELECT idUsuarios FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function existsByCPF($cpf) {
        $stmt = $this->conn->prepare("SELECT idUsuarios FROM usuarios WHERE cpf = ?");
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function login($email, $senha) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();
            if (password_verify($senha, $usuario['senha'])) {
                return $usuario;
            }
        }
        return false;
    }
}
