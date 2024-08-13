<?php

namespace Usuario\Apipropiedades;

use Usuario\Apipropiedades\Database;
use Usuario\Apipropiedades\Geo;

class GeoController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getDepartamentos()
    {
        $geo = new Geo($this->db);
        $stmt = $geo->listDepartamentos();
        $regiones = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($regiones);
    }
    public function getDepartamentoId($regionId)
    {
        $geo = new Geo($this->db);
        $geo->regionId = $regionId;
        $stmt = $geo->listDepartamentoId();
        $regiones = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($regiones);
    }
    public function getProvincias()
    {
        $geo = new Geo($this->db);
        $stmt = $geo->listProvincias();
        $provincias = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($provincias);
    }
    public function getProvinciasId($provinciaId)
    {
        $geo = new Geo($this->db);
        $geo->provinciaId = $provinciaId;
        $stmt = $geo->listProvinciaId();
        $provincias = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($provincias);
    }
    public function getProvinciasByDepartamento($regionId)
    {
        $geo = new Geo($this->db);
        $geo->regionId = $regionId;
        $stmt = $geo->listProvinciasByDepartamento();
        $provincias = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($provincias);
    }
    public function getDistritos()
    {
        $geo = new Geo($this->db);
        $stmt = $geo->listDistritos();
        $provincias = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($provincias);
    }
    public function getDistritosId($distritoId)
    {
        $geo = new Geo($this->db);
        $geo->distritoId = $distritoId;
        $stmt = $geo->listDistritoId();
        $provincias = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($provincias);
    }
    public function getDistritosByProvincia($provinciaId)
    {
        $geo = new Geo($this->db);
        $geo->provinciaId = $provinciaId;
        $stmt = $geo->listDistritoByProvinciaId();
        $provincias = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($provincias);
    }
}
