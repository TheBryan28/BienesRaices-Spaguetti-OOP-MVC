<?php

function conectarDB():mysqli{
    $db = mysqli_connect('localhost','root','root','bienes_raices');
    $db->set_charset("utf8");//para no tener problemas con la ñ

    
    if (!$db) {
        echo "Error: No se pudo conectar a MySQL.";
        echo "errno de depuración: " . mysqli_connect_errno();
        echo "error de depuración: " . mysqli_connect_error();
        exit;
    } else{
        //echo 'conexion exitosa';
    }
    return $db;
}





