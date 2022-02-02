<?php 

function conectarDB() : mysqli {
    $db = new mysqli('localhost', 'root', 'root', 'bienes_raices');
    $db->set_charset("utf8");//para no tener problemas con la ñ

    if(!$db) {
        echo "Error no se pudo conectar";
        exit;
    } 

    return $db;
    
}