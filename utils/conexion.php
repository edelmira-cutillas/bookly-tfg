<?php
require_once 'configuracion.php';

function conectarBD() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;    
        
        $conexion = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
        ]);
        
        return $conexion;
    } catch (PDOException $e) {
        die('Error de conexión con la base de datos: ' . $e->getMessage());
    }
}

$conexion = conectarBD();
?>