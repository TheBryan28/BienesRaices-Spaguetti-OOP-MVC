<?php
//importar la conexion
require 'includes/config/database.php';
$db= conectarDB();
// crear un email y password
$email='correo@correo.com';
$password='123456';
$passwordHash=password_hash($password,PASSWORD_DEFAULT);
//var_dump($passwordHash);
//query para crear el usuario
$query="INSERT INTO usuarios (email, password) VALUES ('$email','$passwordHash')";
 //echo $query;
//agregarlo a la base de datos

// exit;
mysqli_query($db,$query);
