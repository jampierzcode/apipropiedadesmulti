<?php

namespace Usuario\Apipropiedades;

class Clientes
{
    private $conn;
    private $table_name = "clientes";

    public $id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT * FROM clientes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function readById($id)
    {
        $query = "SELECT * FROM clientes WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    public function readByUserId($id)
    {
        $query = "SELECT c.*, p.nombre as nombre_propiedad FROM clientes c inner join propiedades p on c.propiedad_id=p.id WHERE p.created_by=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    public function readByAsignedId($id)
    {
        $query = "SELECT c.*, p.nombre as nombre_propiedad FROM clientes c inner join propiedades p on c.propiedad_id=p.id inner join user_cliente uc on c.id=uc.cliente_id WHERE uc.user_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    public function readByBusinessId($id)
    {
        $query = "SELECT c.*, p.nombre as nombre_propiedad FROM clientes c inner join propiedades p on c.propiedad_id=p.id WHERE c.empresa_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    public function create($data)
    {
        try {
            //code...
            $query = "INSERT INTO " . $this->table_name . "(nombres, apellidos, email, celular, mensaje, propiedad_id, empresa_id, fecha_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                $data['nombres'],
                $data['apellidos'],
                $data['email'],
                $data['celular'],
                $data['mensaje'],
                $data['propiedad_id'],
                $data['empresa_id'],
                $data['fecha_created']
            ]);
            $cliente_id = $this->conn->lastInsertId();
            $success = json_encode(['message' => 'add', "id" => $cliente_id]);
            return $success;
        } catch (\Throwable $error) {
            //throw $th;
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }
    public function createAsigned($data)
    {
        try {
            //code...
            $query = "INSERT INTO user_cliente (cliente_id, user_id, fecha_asigned) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                $data['cliente_id'],
                $data['user_id'],
                $data['fecha_asigned']
            ]);
            $asigned_id = $this->conn->lastInsertId();
            $success = json_encode(['message' => 'add', "id" => $asigned_id]);
            return $success;
        } catch (\Throwable $error) {
            //throw $th;
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }

    public function updateByUser($data)
    {
        try {
            //code...
            $query = "UPDATE " . $this->table_name . " SET nombre_razon=?, website=?, phone_contact=?, email=?, logo=? WHERE user_id=?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['nombre_razon'],
                $data['website'],
                $data['phone_contact'],
                $data['email'],
                $data['logo'],
                $data['user_id']
            ]);
            $success = json_encode(['message' => 'update']);
            return $success;
        } catch (\Throwable $error) {
            //throw $th;
            //throw $th;
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }
}
