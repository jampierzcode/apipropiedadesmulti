<?php

namespace Usuario\Apipropiedades;

use Usuario\Apipropiedades\Database;
use Usuario\Apipropiedades\Clientes;

class ClientesController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getClientes()
    {
        $conf = new Clientes($this->db);
        $stmt = $conf->read();
        $business = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($business);
    }
    public function getClientesById($id)
    {
        $conf = new Clientes($this->db);
        $stmt = $conf->readById($id);
        $business = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($business);
    }
    public function getClientesByUserId($id)
    {
        $conf = new Clientes($this->db);
        $stmt = $conf->readByUserId($id);
        $business = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($business);
    }
    public function createCliente($data)
    {
        $conf = new Clientes($this->db);
        return $conf->create($data);
    }
    public function updateBusinessByUser($data)
    {
        $conf = new Clientes($this->db);
        return $conf->updateByUser($data);
    }
}
