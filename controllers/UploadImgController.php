<?php

namespace Usuario\Apipropiedades;

class UploadImgController
{
    public function uploadFile()
    {
        // Determinar la URL base de la aplicación
        $baseUrl = $this->getBaseUrl();

        // Check if propertyName is set
        if (!isset($_POST['propertyName'])) {
            echo json_encode(['message' => 'Property name is required']);
            exit();
        }
        $propertyName = $_POST['propertyName']; // Assuming you're passing propertyName in the form data
        $targetDir = "imagenes/$propertyName/";

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $response = [];

        if (isset($_FILES['coverImage'])) {
            $coverImage = $_FILES['coverImage'];
            $coverImagePath = $targetDir . uniqid() . '-' .  basename($coverImage["name"]);
            move_uploaded_file($coverImage["tmp_name"], $coverImagePath);
            $response['coverImage'] = $baseUrl . $coverImagePath;
        }
        if (isset($_FILES['imageBusiness'])) {
            $imageBusiness = $_FILES['imageBusiness'];
            $businessImagePath = $targetDir . uniqid() . '-' .  basename($imageBusiness["name"]);
            move_uploaded_file($imageBusiness["tmp_name"], $businessImagePath);
            $response['url'] = $baseUrl . $businessImagePath;
        }
        if (isset($_FILES['imageWebPortada'])) {
            $imageWebPortada = $_FILES['imageWebPortada'];
            $portadaWebImagePath = $targetDir . uniqid() . '-' .  basename($imageWebPortada["name"]);
            move_uploaded_file($imageWebPortada["tmp_name"], $portadaWebImagePath);
            $response['url'] = $baseUrl . $portadaWebImagePath;
        }
        if (isset($_FILES['galleryImages'])) {
            $galleryImages = $_FILES['galleryImages'];
            foreach ($galleryImages['tmp_name'] as $index => $tmpName) {
                $galleryImagePath = $targetDir . uniqid() . '-' .  basename($galleryImages["name"][$index]);
                move_uploaded_file($tmpName, $galleryImagePath);
                $response['galleryImages'][] = $baseUrl . $galleryImagePath;
            }
        }
        if (isset($_FILES['modelosImages'])) {
            $modelosImages = $_FILES['modelosImages'];
            foreach ($modelosImages['tmp_name'] as $index => $tmpName) {
                $modeloImagePath = $targetDir . uniqid() . '-' . basename($modelosImages["name"][$index]);
                move_uploaded_file($tmpName, $modeloImagePath);
                $response['modelosImages'][] = $baseUrl . $modeloImagePath;
            }
        }

        return json_encode($response);
    }
    public function deleteFile($data)
    {
        $response = [];
        if (!isset($data["image_url"])) {
            http_response_code(400);
            echo json_encode(['message' => 'Falta la URL de la imagen']);
            exit();
        }

        $imageUrl = $data["image_url"];

        // Aquí puedes agregar la lógica para eliminar la imagen de tu servidor
        // Por ejemplo, podrías eliminar un archivo local o desde un servicio de almacenamiento

        // Ejemplo para eliminar un archivo local:
        $imagePath = parse_url($imageUrl, PHP_URL_PATH);
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $imagePath)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $imagePath);
            http_response_code(200);
            $response = ['message' => 'remove'];
        } else {
            http_response_code(404);
            $response = ['message' => 'no-remove'];
        }


        return json_encode($response);
    }
    public function deleteFiles($data)
    {
        $response = [];
        // $response = ['deleted' => [], 'not_found' => []];

        if (!isset($data["imagenesDelete"]) || !is_array($data["imagenesDelete"])) {
            http_response_code(400);
            echo json_encode(['message' => 'Falta el array de URLs de imágenes']);
            exit();
        }

        $imagenesDelete = $data["imagenesDelete"];

        foreach ($imagenesDelete as $imageUrl) {
            $imagePath = parse_url($imageUrl, PHP_URL_PATH);
            $fullImagePath = $_SERVER['DOCUMENT_ROOT'] . $imagePath;

            if (file_exists($fullImagePath)) {
                unlink($fullImagePath);
                // $response['deleted'][] = $imageUrl;
            }
            //  else {
            //     $response['not_found'][] = $imageUrl;
            // }
        }
        $response = ['message' => 'remove'];

        http_response_code(200);
        return json_encode($response);
    }


    private function getBaseUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        return $protocol . $host . $scriptName . '/';
    }
}
