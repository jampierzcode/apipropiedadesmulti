<?php

namespace Usuario\Apipropiedades;

class User
{
    private $conn;
    private $table_name = "usuario";

    public $id;
    public $email;
    public $rol;
    public $cliente_id;
    public $password;
    public $nombres;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login()
    {
        $query = "SELECT * FROM " . $this->table_name . " u inner join cliente c on u.cliente_id=c.id WHERE u.email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row && password_verify($this->password, $row['password'])) {
            $this->id = $row['id'];
            $this->cliente_id = $row['cliente_id'];
            $this->nombres = $row['nombres'];
            $this->email = $row['email'];
            $this->rol = $row['rol'];
            return true;
        }

        return false;
    }
    public function create()
    {
        $query = "SELECT * FROM " . $this->table_name . " u inner join cliente c on u.cliente_id=c.id WHERE u.email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row && password_verify($this->password, $row['password'])) {
            $this->id = $row['id'];
            $this->cliente_id = $row['cliente_id'];
            $this->nombres = $row['nombres'];
            $this->email = $row['email'];
            return true;
        }

        return false;
    }
}
