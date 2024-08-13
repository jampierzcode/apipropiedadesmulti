<?php

namespace Usuario\Apipropiedades;

use Usuario\Apipropiedades\Propiedades;
use Usuario\Apipropiedades\Database; // Asegúrate de tener una clase Database definida en modelos/Database.php

class PropiedadController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getProperties()
    {
        $property = new Propiedades($this->db);
        $stmt = $property->read();
        $properties = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($properties);
    }

    public function getProperty($id)
    {
        $property = new Propiedades($this->db);
        $property->id = $id;
        $stmt = $property->readOne();
        $property = $stmt->fetch(\PDO::FETCH_ASSOC);
        return json_encode($property);
    }

    public function createProperty($data)
    {
        $property = new Propiedades($this->db);
        return $property->create($data);
    }
    public function getMultimediaProperty($id)
    {
        $property = new Propiedades($this->db);
        $stmt = $property->readMultimediabyPropiedad($id);
        $property = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($property);
    }
    public function getAmenidadesProperty($id)
    {
        $property = new Propiedades($this->db);
        $stmt = $property->readAmenidadesbyPropiedad($id);
        $property = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($property);
    }
    public function createMultimediaProperty($data)
    {
        $property = new Propiedades($this->db);
        return $property->createMultimedia($data);
    }
    public function updateMultimediaProperty($data)
    {
        $property = new Propiedades($this->db);
        return $property->updateMultimedia($data);
    }
    public function deleteMultimediaProperty($data)
    {
        $property = new Propiedades($this->db);
        return $property->deleteMultimedia($data);
    }
    public function createAmenidadProperty($data)
    {
        $property = new Propiedades($this->db);
        return $property->createAmenidades($data);
    }
    public function updateAmenidadProperty($data)
    {
        $property = new Propiedades($this->db);
        return $property->updateAmenidades($data);
    }
    public function deleteAmenidadProperty($data)
    {
        $property = new Propiedades($this->db);
        return $property->deleteAmenidades($data);
    }
    public function updateModelo($id, $data)
    {
        $property = new Propiedades($this->db);

        return $property->updateModel($id, $data);
    }
    public function getModelosByProperty($id)
    {
        $property = new Propiedades($this->db);
        $stmt = $property->readModelosByPropiedad($id);
        $property = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($property);
    }
    public function createModelosProperty($data)
    {
        $property = new Propiedades($this->db);
        return $property->createModelos($data);
    }
    public function createUnidadesModelos($data)
    {
        $property = new Propiedades($this->db);
        return $property->createUnidadesModelos($data);
    }
    public function getUnidadesModelo($id)
    {
        $property = new Propiedades($this->db);
        $stmt = $property->readUnidadesModelo($id);
        $property = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($property);
    }

    public function updateProperty($id, $data)
    {
        $property = new Propiedades($this->db);
        $property->id = $id;
        return $property->update($data);
    }


    public function deleteProperty($id)
    {
        $property = new Propiedades($this->db);
        $property->id = $id;
        if ($property->delete()) {
            return json_encode(['message' => 'delete']);
        } else {
            return json_encode(['message' => 'no-delete']);
        }
    }
}
