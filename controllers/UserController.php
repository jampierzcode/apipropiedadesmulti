<?php

namespace Usuario\Apipropiedades;


use Usuario\Apipropiedades\Database;
use Usuario\Apipropiedades\User;
use Firebase\JWT\JWT;

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
class UserController
{
    private $db;
    private $user;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function register($data)
    {
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];

        if ($this->user->create()) {
            return json_encode(['message' => 'User registered successfully']);
        } else {
            return json_encode(['message' => 'User registration failed']);
        }
    }

    public function login($data)
    {
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];

        $user_id = $this->user->login();

        if ($user_id) {
            $payload = array(
                'iat' => time(),
                'exp' => time() + (60 * 60),
                "data" => array(
                    "id" => $this->user->id,
                    "email" => $this->user->email,
                    "nombres" => $this->user->nombres,
                    "cliente_id" => $this->user->cliente_id,
                )
            );
            $jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
            $data =  array(
                "id" => $this->user->id,
                "email" => $this->user->email,
                "nombres" => $this->user->nombres,
                "cliente_id" => $this->user->cliente_id,
                "rol" => $this->user->rol,
                "token" => $jwt,
            );

            return json_encode(array("message" => "login_success", "data" => $data));;
        } else {
            return json_encode(['message' => 'Login failed']);
        }
    }
}
