<?php
require 'vendor/autoload.php';

use Usuario\Apipropiedades\Database;

$database = new Database();
$db = $database->getConnection();

$cliente_id = 1;
$email = "Juampi0070";
$password = "20091722"; // Cambia esto por la contraseña deseada

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Prepare the SQL query
$query = "INSERT INTO usuario (cliente_id, email, password) VALUES (:cliente_id, :email, :password)";
$stmt = $db->prepare($query);

// Bind parameters
$stmt->bindParam(':cliente_id', $cliente_id);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $hashed_password);

// Execute the query
if ($stmt->execute()) {
    echo "User created successfully.";
} else {
    echo "User creation failed.";
}
