<?php

namespace Usuario\Apipropiedades;

use Exception;

class User
{
    private $conn;
    private $table_name = "usuario";

    public $id;
    public $uuid;
    public $email;
    public $rol;
    public $cliente_id;
    public $password;
    public $nombres;
    public $celular;
    public $documento;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login()
    {
        $query = "SELECT * FROM " . $this->table_name . " u WHERE u.email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row && password_verify($this->password, $row['password'])) {
            $this->id = $row['id'];
            $this->uuid = $row['uuid'];
            $this->nombres = $row['nombres'];
            $this->email = $row['email'];
            $this->celular = $row['celular'];
            $this->documento = $row['documento'];
            $this->rol = $row['rol'];
            return true;
        }

        return false;
    }
    public function create($data)
    {
        try {
            $hashed_password = password_hash($data["password"], PASSWORD_BCRYPT);

            // Iniciar la transacción
            $this->conn->beginTransaction();

            // Primera inserción: tabla `usuario`
            $query = "INSERT INTO usuario(uuid, nombres, celular, documento, email, password, created_by, fecha_created, rol, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['uuid'],
                $data['nombres'],
                $data['celular'],
                $data['documento'],
                $data['email'],
                $hashed_password,
                $data['created_by'],
                $data['fecha_created'],
                $data['rol'],
                $data['status']
            ]);

            // Obtener el ID del usuario insertado
            $user_id = $this->conn->lastInsertId();

            // Segunda inserción: tabla `user_business`
            $asigned = json_decode($this->asignedUserToBusiness($user_id, $data["empresa_id"], $data["fecha_created"], $data['created_by']));

            if ($asigned && $asigned->message === "add") {
                // Confirmar la transacción
                $this->conn->commit();
                return json_encode(['message' => 'add', "user_id" => $user_id, "user_business_id" => $asigned->id]);
            } else {
                throw new Exception("Error en la asignación del usuario a la empresa.");
            }
        } catch (\Throwable $error) {
            // Revertir la transacción en caso de error
            $this->conn->rollBack();
            return json_encode(['message' => 'error', "error" => $error->getMessage()]);
        }
    }

    public function asignedUserToBusiness($user_id, $business_id, $fecha_asigned, $created_by)
    {
        try {
            $query = "INSERT INTO user_business(user_id, business_id, fecha_asigned, created_by) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$user_id, $business_id, $fecha_asigned, $created_by]);

            $user_business_id = $this->conn->lastInsertId();
            return json_encode(['message' => 'add', "id" => $user_business_id]);
        } catch (\Throwable $error) {
            // En caso de error, devolver un mensaje adecuado
            return json_encode(['message' => 'error', "error" => $error->getMessage()]);
        }
    }

    public function readUsuariosByAdmin($user_id)
    {
        $query = "SELECT u.*, e.id AS empresa_id
FROM usuario u
INNER JOIN user_business ub ON u.id = ub.user_id
INNER JOIN business e ON ub.business_id = e.id
WHERE e.id IN (
    SELECT ub.business_id
    FROM user_business ub
    WHERE ub.user_id = ?
) AND u.rol!=2;
";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);

        return $stmt;
    }
    public function readUsuario($id)
    {
        $query = "SELECT * FROM usuario WHERE id=? ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);

        return $stmt;
    }
}
