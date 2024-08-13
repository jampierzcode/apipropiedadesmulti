<?php

namespace Usuario\Apipropiedades;

class Configuracion
{
    private $conn;
    private $table_name = "business";

    public $id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT * FROM business";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function readById($id)
    {
        $query = "SELECT * FROM business WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    public function readByUser($id)
    {
        $query = "SELECT * FROM business WHERE user_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    public function readWebDataByBusiness($id)
    {
        $query = "SELECT * FROM config_website WHERE business_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }

    public function create($data)
    {
        try {
            //code...
            $query = "INSERT INTO " . $this->table_name . "(user_id, nombre_razon, website, direccion,  phone_contact, email, logo) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                $data['user_id'],
                $data['nombre_razon'],
                $data['website'],
                $data['direccion'],
                $data['phone_contact'],
                $data['email'],
                $data['logo']
            ]);
            $propiedad_id = $this->conn->lastInsertId();
            $success = json_encode(['message' => 'add', "id" => $propiedad_id]);
            return $success;
        } catch (\Throwable $error) {
            //throw $th;
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }
    public function createWeb($data)
    {
        try {
            //code...
            $query = "INSERT INTO config_website(business_id, color_primary, color_secondary, color_fondo_portada,  is_capa_fondo_portada, color_capa_fondo_portada, portada) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                $data['business_id'],
                $data['color_primary'],
                $data['color_secondary'],
                $data['color_fondo_portada'],
                $data['is_capa_fondo_portada'],
                $data['color_capa_fondo_portada'],
                $data['portada']
            ]);
            $propiedad_id = $this->conn->lastInsertId();
            $success = json_encode(['message' => 'add', "id" => $propiedad_id]);
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
            $query = "UPDATE " . $this->table_name . " SET nombre_razon=?, website=?, direccion=?,  phone_contact=?, email=?, logo=? WHERE user_id=?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['nombre_razon'],
                $data['website'],
                $data['direccion'],
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
    public function updateWebByBusiness($data)
    {
        try {
            //code...
            $query = "UPDATE config_website SET color_primary=?, color_secondary=?,  color_fondo_portada=?, is_capa_fondo_portada=?, color_capa_fondo_portada=?, portada=? WHERE business_id=?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['color_primary'],
                $data['color_secondary'],
                $data['color_fondo_portada'],
                $data['is_capa_fondo_portada'],
                $data['color_capa_fondo_portada'],
                $data['portada'],
                $data['business_id']
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
