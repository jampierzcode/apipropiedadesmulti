<?php

namespace Usuario\Apipropiedades;

class Geo
{
    private $conn;

    public $regionId;
    public $provinciaId;
    public $distritoId;
    // private $table_name = "ubigeo_peru_";


    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function listDepartamentos()
    {
        $query = "SELECT * FROM ubigeo_peru_departments";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function listDepartamentoId()
    {
        $query = "SELECT * FROM ubigeo_peru_departments WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->regionId);
        $stmt->execute();
        return $stmt;
    }
    public function listProvincias()
    {
        $query = "SELECT * FROM ubigeo_peru_provinces";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function listProvinciasByDepartamento()
    {
        $query = "SELECT * FROM ubigeo_peru_provinces WHERE department_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->regionId);
        $stmt->execute();
        return $stmt;
    }
    public function listProvinciaId()
    {
        $query = "SELECT * FROM ubigeo_peru_provinces WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->provinciaId);
        $stmt->execute();
        return $stmt;
    }
    public function listDistritos()
    {
        $query = "SELECT * FROM ubigeo_peru_districts";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function listDistritoId()
    {
        $query = "SELECT * FROM ubigeo_peru_districts WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->distritoId);
        $stmt->execute();
        return $stmt;
    }
    public function listDistritoByProvinciaId()
    {
        $query = "SELECT * FROM ubigeo_peru_districts WHERE province_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->provinciaId);
        $stmt->execute();
        return $stmt;
    }
}
