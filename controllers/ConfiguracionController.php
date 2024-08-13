<?php

namespace Usuario\Apipropiedades;

use Usuario\Apipropiedades\Database;
use Usuario\Apipropiedades\Configuracion;

class ConfiguracionController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getBusiness()
    {
        $conf = new Configuracion($this->db);
        $stmt = $conf->read();
        $business = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($business);
    }
    public function getBusinessById($id)
    {
        $conf = new Configuracion($this->db);
        $stmt = $conf->readById($id);
        $business = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($business);
    }
    public function getBusinessByUser($id)
    {
        $conf = new Configuracion($this->db);
        $stmt = $conf->readByUser($id);
        $business = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($business);
    }
    public function getWebDataByBusiness($id)
    {
        $conf = new Configuracion($this->db);
        $stmt = $conf->readWebDataByBusiness($id);
        $business = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($business);
    }
    public function createConfigWeb($data)
    {
        $conf = new Configuracion($this->db);
        return $conf->createWeb($data);
    }
    public function createBusiness($data)
    {
        $conf = new Configuracion($this->db);
        return $conf->create($data);
    }
    public function updateConfigWebByBusiness($data)
    {
        $conf = new Configuracion($this->db);
        return $conf->updateWebByBusiness($data);
    }
    public function updateBusinessByUser($data)
    {
        $conf = new Configuracion($this->db);
        return $conf->updateByUser($data);
    }
}
