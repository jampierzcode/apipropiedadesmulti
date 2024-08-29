<?php
require 'controllers/PropiedadController.php';
require 'controllers/UserController.php';
require 'controllers/GeoController.php';
require 'controllers/ConfiguracionController.php';
require 'controllers/UploadImgController.php';
require 'controllers/ClientesController.php';

use \Firebase\JWT\JWT;

use Firebase\JWT\Key;
// Obtener el dominio del archivo .env
$dominio = $_ENV['DOMINIO'];
// CORS headers
header("Access-Control-Allow-Origin: $dominio");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json; charset=UTF-8");


$propertyController = new Usuario\Apipropiedades\PropiedadController();
$clienteController = new Usuario\Apipropiedades\ClientesController();
$configController = new Usuario\Apipropiedades\ConfiguracionController();
$geoController = new Usuario\Apipropiedades\GeoController();
$uploadImgController = new Usuario\Apipropiedades\UploadImgController();
$controller = new Usuario\Apipropiedades\UserController();
$request_method = $_SERVER['REQUEST_METHOD'];
$base_path = '/apipropiedades2'; // Cambiar según la configuración local o de producción
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remover el base_path si existe en el URI
if (strpos($uri, $base_path) === 0) {
    $uri = substr($uri, strlen($base_path));
}
$uri = trim($uri, '/');
$uri = explode('/', $uri);

// Imprimir el arreglo $uri para depuración
// echo print_r($uri);

function authenticate()
{
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        echo json_encode(['message' => 'Access denied']);
        exit();
    }

    $jwt = str_replace('Bearer ', '', $headers['Authorization']);
    try {
        $decoded = JWT::decode($jwt, new Key($_ENV['JWT_SECRET'], 'HS256'));
        return (array) $decoded;
    } catch (\Exception $e) {
        echo json_encode(['message' => 'Access denied']);
        exit();
    }
}
if ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'login') {
    $data = json_decode(file_get_contents("php://input"), true);
    echo $controller->login($data);
    // DISEÑO WEB
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'usuario') {
    if (isset($uri[2])) {

        echo $controller->getUsuarioById($uri[2]);
    } else {
        echo json_encode(['message' => "Error en la consulta, falta un parametro"]);
    }
} elseif ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'usuario') {
    $data = json_decode(file_get_contents("php://input"), true);
    echo $controller->crearUsuario($data);
    // DISEÑO WEB
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'configwebbybusiness') {
    if (isset($uri[2])) {

        echo $configController->getWebDataByBusiness($uri[2]);
    } else {
        echo json_encode(['message' => "Error en la consulta, falta un parametro"]);
    }
} elseif ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'configweb') {
    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);

    echo $configController->createConfigWeb($data);
} elseif ($request_method == 'PUT' && $uri[0] == 'api' && $uri[1] == 'configwebbybusiness') {
    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);

    echo $configController->updateConfigWebByBusiness($data);
    // }
    // BSINESS
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'businessbyuser') {
    if (isset($uri[2])) {

        echo $configController->getBusinessByUser($uri[2]);
    } else {
        echo json_encode(['message' => "Error en la consulta, falta un parametro"]);
    }
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'business') {
    if (isset($uri[2])) {

        echo $configController->getBusinessById($uri[2]);
    } else {
        echo $configController->getBusiness();
    }
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'businessbyslug') {
    if (isset($uri[2])) {

        echo $configController->getBusinessBySlug($uri[2]);
    } else {
        echo json_encode(['message' => "Error en la consulta, falta un parametro"]);
    }
} elseif ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'business') {

    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);

    echo $configController->createBusiness($data);
    // }
} elseif ($request_method == 'PUT' && $uri[0] == 'api' && $uri[1] == 'busineesbyuser') {

    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);

    echo $configController->updateBusinessById($data);
    // }
} elseif ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'businessimg') {
    $user_data = authenticate();
    echo $uploadImgController->uploadFile();
} elseif ($request_method == 'DELETE' && $uri[0] == 'api' && $uri[1] == 'businessimg') {
    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);

    echo $uploadImgController->deleteFile($data);
} elseif ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'imagenesdelete') {
    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);

    echo $uploadImgController->deleteFiles($data);

    // PROPIEDADES
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'propiedades') {

    // $user_data = authenticate();
    if (isset($uri[2])) {
        echo $propertyController->getProperty($uri[2]);
    } else {
        echo $propertyController->getProperties();
    }
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'propiedadesbyuser') {

    // $user_data = authenticate();
    if (isset($uri[2])) {
        echo $propertyController->getPropertiesByUserId($uri[2]);
    } else {
        echo json_encode(['message' => "Error en la consulta, falta un parametro"]);
    }
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'propiedadesbybusiness') {

    // $user_data = authenticate();
    if (isset($uri[2])) {
        echo $propertyController->getPropertiesByBusinessId($uri[2]);
    } else {
        echo json_encode(['message' => "Error en la consulta, falta un parametro"]);
    }
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'amenidadesbypropiedad') {

    if (isset($uri[2])) {

        echo $propertyController->getAmenidadesProperty($uri[2]);
    } else {
        echo json_encode(['message' => "Error en la consulta, falta un parametro"]);
    }
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'multimediabypropiedad') {

    if (isset($uri[2])) {

        echo $propertyController->getMultimediaProperty($uri[2]);
    } else {
        echo json_encode(['message' => "Error en la consulta, falta un parametro"]);
    }
} elseif ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'multimediapropiedades') {
    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);
    // echo print_r($data);
    echo $propertyController->createMultimediaProperty($data);
} elseif ($request_method == 'PUT' && $uri[0] == 'api' && $uri[1] == 'multimediapropiedades') {
    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);
    // echo print_r($data);
    echo $propertyController->updateMultimediaProperty($data);
} elseif ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'deletemultimediapropiedades') {

    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);
    // echo print_r($data);
    echo $propertyController->deleteMultimediaProperty($data);
} elseif ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'amenidadespropiedades') {
    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);
    // echo print_r($data);
    echo $propertyController->createAmenidadProperty($data);
} elseif ($request_method == 'PUT' && $uri[0] == 'api' && $uri[1] == 'amenidadespropiedades') {
    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);
    // echo print_r($data);
    echo $propertyController->updateAmenidadProperty($data);
} elseif ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'deleteamenidadespropiedades') {

    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);
    // echo print_r($data);
    echo $propertyController->deleteAmenidadProperty($data);
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'modelosbypropiedad') {

    if (isset($uri[2])) {
        echo $propertyController->getModelosByProperty($uri[2]);
    } else {
        echo json_encode(['message' => 'Te falta parametros del ID del departamento']);
    }
} elseif ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'modelospropiedades') {
    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);
    // echo print_r($data);
    echo $propertyController->createModelosProperty($data);
} elseif ($request_method == 'PUT' && $uri[0] == 'api' && $uri[1] == 'updatemodelo' && isset($uri[2])) {
    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);
    echo $propertyController->updateModelo($uri[2], $data);
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'unidadesbymodelo') {

    if (isset($uri[2])) {
        echo $propertyController->getUnidadesModelo($uri[2]);
    } else {
        echo json_encode(['message' => 'Te falta parametros del ID del departamento']);
    }
} elseif ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'unidadesModelos') {
    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);
    // echo print_r($data);
    echo $propertyController->createUnidadesModelos($data);
} elseif ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'propiedades') {
    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);
    // echo print_r($data);
    echo $propertyController->createProperty($data);
} elseif ($request_method == 'PUT' && $uri[0] == 'api' && $uri[1] == 'propiedades' && isset($uri[2])) {
    $user_data = authenticate();
    $data = json_decode(file_get_contents("php://input"), true);
    echo $propertyController->updateProperty($uri[2], $data);
} elseif ($request_method == 'DELETE' && $uri[0] == 'api' && $uri[1] == 'propiedades' && isset($uri[2])) {
    $user_data = authenticate();
    echo $propertyController->deleteProperty($uri[2]);
    // GEO LOCALIZACION
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'departamentos') {

    $user_data = authenticate();
    if (isset($uri[2])) {
        echo $geoController->getDepartamentoId($uri[2]);
    } else {
        echo $geoController->getDepartamentos();
    }
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'provincias') {

    $user_data = authenticate();
    if (isset($uri[2])) {
        echo $geoController->getProvinciasId($uri[2]);
    } else {
        echo $geoController->getProvincias();
    }
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'provinciasbydepartamento') {

    $user_data = authenticate();
    if (isset($uri[2])) {
        echo $geoController->getProvinciasByDepartamento($uri[2]);
    } else {
        echo json_encode(['message' => 'Te falta parametros del ID del departamento']);
    }
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'distritos') {

    $user_data = authenticate();
    if (isset($uri[2])) {
        echo $geoController->getDistritosId($uri[2]);
    } else {
        echo $geoController->getDistritos();
    }
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'distritosbyprovincia') {

    $user_data = authenticate();
    if (isset($uri[2])) {
        echo $geoController->getDistritosByProvincia($uri[2]);
    } else {
        echo json_encode(['message' => 'Te falta parametros del ID de la provincia']);
    }
} elseif ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'uploadimg') {
    $user_data = authenticate();
    echo $uploadImgController->uploadFile();
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'clientesbyuser') {

    if (isset($uri[2])) {
        echo $clienteController->getClientesByUserId($uri[2]);
    } else {
        echo json_encode(['message' => 'Te falta parametros del ID del usuario']);
    }
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'clientesbyasigned') {

    if (isset($uri[2])) {
        echo $clienteController->getClientesByAsignedId($uri[2]);
    } else {
        echo json_encode(['message' => 'Te falta parametros del ID del usuario']);
    }
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'clientesbybusiness') {

    if (isset($uri[2])) {
        echo $clienteController->getClientesByBusinessId($uri[2]);
    } else {
        echo json_encode(['message' => 'Te falta parametros del ID del usuario']);
    }
} elseif ($request_method == 'GET' && $uri[0] == 'api' && $uri[1] == 'usuariosbyadmin') {

    if (isset($uri[2])) {
        echo $controller->getUsuariosByAdminId($uri[2]);
    } else {
        echo json_encode(['message' => 'Te falta parametros del ID del admin']);
    }
} elseif ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'clientes') {
    $data = json_decode(file_get_contents("php://input"), true);
    // echo print_r($data);
    echo $clienteController->createCliente($data);
} elseif ($request_method == 'POST' && $uri[0] == 'api' && $uri[1] == 'clientesasigned') {
    $data = json_decode(file_get_contents("php://input"), true);
    // echo print_r($data);
    echo $clienteController->createClienteAsigned($data);
}
