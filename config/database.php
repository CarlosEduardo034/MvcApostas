<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'Cadastros';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if($this->conn->connect_error){
            die("Erro de conexão: " . $this->conn->connect_error);
        }
        return $this->conn;
    }
}
