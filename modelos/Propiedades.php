<?php

namespace Usuario\Apipropiedades;

class Propiedades
{
    private $conn;
    private $table_name = "propiedades";

    public $id;
    public $nombre;
    public $tipo;
    public $purpose;
    public $descripcion;
    public $link_extra;
    public $region;
    public $provincia;
    public $distrito;
    public $exactAddress;
    public $postalcode;
    public $position_locate;
    public $area_from;
    public $area_const_from;
    public $precio_from;
    public $moneda;
    public $etapa;
    public $fecha_entrega;
    public $fecha_captacion;
    public $fecha_created;
    public $financiamiento;
    public $created_by;
    public $status;
    public $name_reference;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT p.*, pm.categoria, pm.url_file, pm.etiqueta, pr.name as region_name, pv.name as provincia_name, pd.name as distrito_name FROM " . $this->table_name . " p  left join propiedad_multimedia pm on p.id=pm.propiedad_id  AND pm.etiqueta = 'Portada' inner join ubigeo_peru_departments pr on p.region=pr.id inner join ubigeo_peru_provinces pv on p.provincia=pv.id inner join ubigeo_peru_districts pd on p.distrito=pd.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne()
    {
        $query = "SELECT p.*, pm.categoria, pm.url_file, pm.etiqueta, pr.name as region_name, pv.name as provincia_name, pd.name as distrito_name FROM " . $this->table_name . "  p  left join propiedad_multimedia pm on p.id=pm.propiedad_id inner join ubigeo_peru_departments pr on p.region=pr.id inner join ubigeo_peru_provinces pv on p.provincia=pv.id inner join ubigeo_peru_districts pd on p.distrito=pd.id WHERE p.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function create($data)
    {
        try {
            //code...
            $query = "INSERT INTO " . $this->table_name . "(logo, nombre, tipo, purpose, descripcion, video_descripcion, link_extra, region, provincia, distrito, exactAddress, postalcode, position_locate, area_from, area_const_from, precio_from, moneda, etapa, fecha_entrega, fecha_captacion, fecha_created, financiamiento, created_by, status, name_reference) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                $data['logo'],
                $data['nombre'],
                $data['tipo'],
                $data['purpose'],
                $data['descripcion'],
                $data['video_descripcion'],
                $data['link_extra'],
                $data['region'],
                $data['provincia'],
                $data['distrito'],
                $data['exactAddress'],
                $data['postalcode'],
                json_encode($data['position_locate']),
                $data['area_from'],
                $data['area_const_from'],
                $data['precio_from'],
                $data['moneda'],
                $data['etapa'],
                $data['fecha_entrega'],
                $data['fecha_captacion'],
                $data['fecha_created'],
                $data['financiamiento'],
                $data['created_by'],
                $data['status'],
                $data['name_reference'],
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
    public function readMultimediabyPropiedad($id)
    {
        try {
            //code...
            $query = "SELECT * FROM propiedad_multimedia WHERE propiedad_id=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            return $stmt;
        } catch (\Throwable $error) {
            //throw $th;
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }
    public function readAmenidadesbyPropiedad($id)
    {
        try {
            //code...
            $query = "SELECT * FROM propiedad_amenidad WHERE propiedad_id=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            return $stmt;
        } catch (\Throwable $error) {
            //throw $th;
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }
    public function createMultimedia($data)
    {
        try {
            //code...
            $ids = [];
            $query = "INSERT INTO propiedad_multimedia(categoria, url_file, propiedad_id, etiqueta, indice) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            foreach ($data as $multimedia) {
                # code...
                $stmt->execute([
                    $multimedia['categoria'],
                    $multimedia['url_file'],
                    $multimedia['propiedad_id'],
                    $multimedia['etiqueta'],
                    $multimedia['indice']
                ]);
                $multimedia_id = $this->conn->lastInsertId();
                $ids[] = $multimedia_id;
            }
            $success = json_encode(['message' => 'add', "ids" => $ids]);
            return $success;
        } catch (\Throwable $error) {
            //throw $th;
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }
    public function updateMultimedia($data)
    {
        try {
            //code...
            $query = "UPDATE propiedad_multimedia SET categoria = ?, url_file = ?, propiedad_id = ?, etiqueta = ?, indice = ? WHERE id=?";
            $stmt = $this->conn->prepare($query);
            foreach ($data as $multimedia) {
                # code...
                $stmt->execute([
                    $multimedia['categoria'],
                    $multimedia['url_file'],
                    $multimedia['propiedad_id'],
                    $multimedia['etiqueta'],
                    $multimedia['indice'],
                    $multimedia['id']
                ]);
            }
            $success = json_encode(['message' => 'update']);
            return $success;
        } catch (\Throwable $error) {
            //throw $th;
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }
    public function deleteMultimedia($data)
    {
        try {
            //code...
            $query = "DELETE FROM propiedad_multimedia WHERE id=?";
            $stmt = $this->conn->prepare($query);
            foreach ($data as $multimedia) {
                # code...
                $stmt->execute([
                    $multimedia['id']
                ]);
            }
            $success = json_encode(['message' => 'delete']);
            return $success;
        } catch (\Throwable $error) {
            //throw $th;
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }
    public function createAmenidades($data)
    {
        try {
            //code...
            $ids = [];
            $query = "INSERT INTO propiedad_amenidad(propiedad_id, amenidad) VALUES (?, ?)";
            $stmt = $this->conn->prepare($query);
            foreach ($data as $multimedia) {
                # code...
                $stmt->execute([
                    $multimedia['propiedad_id'],
                    $multimedia['amenidad']
                ]);
                $amenidad_id = $this->conn->lastInsertId();
                $ids[] = $amenidad_id;
            }
            $success = json_encode(['message' => 'add', "ids" => $ids]);
            return $success;
        } catch (\Throwable $error) {
            //throw $th;
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }
    public function updateAmenidades($data)
    {
        try {
            //code...
            $query = "UPDATE propiedad_amenidad set propiedad_id=?, amenidad=? WHERE id=?";
            $stmt = $this->conn->prepare($query);
            foreach ($data as $amenidad) {
                # code...
                $stmt->execute([
                    $amenidad['propiedad_id'],
                    $amenidad['amenidad'],
                    $amenidad['id']
                ]);
            }
            $success = json_encode(['message' => 'update']);
            return $success;
        } catch (\Throwable $error) {
            //throw $th;
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }
    public function deleteAmenidades($data)
    {
        try {
            //code...
            $query = "DELETE FROM propiedad_amenidad WHERE id=?";
            $stmt = $this->conn->prepare($query);
            foreach ($data as $amenidad) {
                # code...
                $stmt->execute([
                    $amenidad['id']
                ]);
            }
            $success = json_encode(['message' => 'delete']);
            return $success;
        } catch (\Throwable $error) {
            //throw $th;
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }
    public function readModelosByPropiedad($id)
    {
        $query = "SELECT pm.*, p.nombre as nombre_propiedad, p.purpose as proposito_propiedad,    COALESCE(COUNT(u.id), 0) AS cantidad_unidades_totales,
    COALESCE(SUM(CASE WHEN u.status = 'Disponible' THEN 1 ELSE 0 END), 0) AS cantidad_unidades_disponibles
 FROM propiedad_modelos pm inner join propiedades p on pm.propiedad_id=p.id LEFT JOIN 
    modelos_unidades u ON pm.id = u.modelo_id WHERE pm.propiedad_id=? GROUP BY 
    pm.id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    public function createModelos($data)
    {
        try {
            //code...
            $ids = [];
            $query = "INSERT INTO propiedad_modelos(propiedad_id, nombre, categoria, precio, area, imagenUrl, habs, garage, banios, moneda, etapa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            foreach ($data as $models) {
                # code...
                $stmt->execute([
                    $models['propiedad_id'],
                    $models['nombre'],
                    $models['categoria'],
                    $models['precio'],
                    $models['area'],
                    $models['imagenUrl'],
                    $models['habs'],
                    $models['garage'],
                    $models['banios'],
                    $models['moneda'],
                    $models['etapa']
                ]);
                $amenidad_id = $this->conn->lastInsertId();
                $ids[] = $amenidad_id;
            }
            $success = json_encode(['message' => 'add', "ids" => $ids]);
            return $success;
        } catch (\Throwable $error) {
            //throw $th;
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }
    public function updateModel($id, $data)
    {
        try {
            //code...
            $query = "UPDATE propiedad_modelos
        SET nombre = ?, categoria = ?, precio = ?, area = ?, imagenUrl = ?, habs = ?, garage = ?, banios = ?, moneda = ?, etapa = ?
        WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['nombre'],
                $data['categoria'],
                $data['precio'],
                $data['area'],
                $data['imagenUrl'],
                $data['habs'],
                $data['garage'],
                $data['banios'],
                $data['moneda'],
                $data['etapa'],
                $id,
            ]);
            $success = json_encode(['message' => 'update']);
            return $success;
        } catch (\Throwable $error) {
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }
    public function readUnidadesModelo($id)
    {
        $ids = [];
        $query = "SELECT * FROM modelos_unidades WHERE modelo_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    public function createUnidadesModelos($data)
    {
        try {
            //code...
            $ids = [];
            $query = "INSERT INTO modelos_unidades(modelo_id, nombre, status) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            foreach ($data as $models) {
                # code...
                $stmt->execute([
                    $models['modelo_id'],
                    $models['nombre'],
                    $models['status']
                ]);
                $unidad_id = $this->conn->lastInsertId();
                $ids[] = $unidad_id;
            }
            $success = json_encode(['message' => 'add', "ids" => $ids]);
            return $success;
        } catch (\Throwable $error) {
            //throw $th;
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }

    public function update($data)
    {
        try {
            //code...
            $query = "UPDATE " . $this->table_name . " SET 
            logo= ?, nombre = ?, tipo = ?, purpose = ?, descripcion = ?, video_descripcion = ?, link_extra = ?, region = ?, provincia = ?, distrito = ?, exactAddress = ?, postalcode = ?, position_locate = ?, area_from = ?, area_const_from = ?, precio_from = ?, moneda = ?, etapa = ?, fecha_entrega = ?, fecha_captacion = ?, fecha_created = ?, financiamiento = ?, created_by = ?, status = ?, name_reference = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['logo'],
                $data['nombre'],
                $data['tipo'],
                $data['purpose'],
                $data['descripcion'],
                $data['video_descripcion'],
                $data['link_extra'],
                $data['region'],
                $data['provincia'],
                $data['distrito'],
                $data['exactAddress'],
                $data['postalcode'],
                $data['position_locate'],
                $data['area_from'],
                $data['area_const_from'],
                $data['precio_from'],
                $data['moneda'],
                $data['etapa'],
                $data['fecha_entrega'],
                $data['fecha_captacion'],
                $data['fecha_created'],
                $data['financiamiento'],
                $data['created_by'],
                $data['status'],
                $data['name_reference'],
                $this->id,
            ]);

            // $stmt->bindParam(":name", $this->name);
            // $stmt->bindParam(":location", $this->location);
            // $stmt->bindParam(":price", $this->price);
            $stmt->bindParam(":id", $this->id);
            $success = json_encode(['message' => 'update']);
            return $success;
        } catch (\Throwable $error) {
            $success = json_encode(['message' => 'error', "error" => $error->getMessage()]);
            return $success;
        }
    }


    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
