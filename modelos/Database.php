<?php

namespace Usuario\Apipropiedades;

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $charset;
    public $conn;

    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->db_name = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->charset = "utf8mb4";
        $this->password = $_ENV['DB_PASS'];
    }

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new \PDO("mysql:dbname={$this->db_name};host={$this->host};charset={$this->charset}", $this->username, $this->password);
        } catch (\PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
