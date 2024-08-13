<?php

namespace Usuario\Apipropiedades;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler
{
    private $key;
    private $iss = "http://example.org";
    private $aud = "http://example.com";
    private $iat;
    private $nbf;
    private $exp;

    public function __construct()
    {
        $this->key = $_ENV['JWT_SECRET'];
        $this->iat = time();
        $this->nbf = $this->iat;
        $this->exp = $this->iat + (60 * 60); // Token válido por 1 hora
    }

    public function generateToken($userId, $nombres, $email, $cliente_id)
    {
        $payload = array(
            "iss" => $this->iss,
            "aud" => $this->aud,
            "iat" => $this->iat,
            "nbf" => $this->nbf,
            "exp" => $this->exp,
            "data" => array(
                "id" => $userId,
                "email" => $email,
                "nombres" => $nombres,
                "cliente_id" => $cliente_id,
            )
        );

        return JWT::encode($payload, $this->key, 'HS256');
    }

    public function validateToken($jwt)
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
            return $decoded->data->id;
        } catch (\Exception $e) {
            return false;
        }
    }
}
