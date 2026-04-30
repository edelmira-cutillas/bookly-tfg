<?php
session_start();
require_once '../utils/conexion.php';
require_once '../clases/Estanteria.php';
require_once '../clases/Libro.php';
require_once '../clases/Usuario.php';

require_once __DIR__ . '/../utils/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $libro_id = (int)$_POST['libro_id'];
    $nuevo_estado = $_POST['estado'];

    $estanteriaModel = new Estanteria($conexion);
    $registro = $estanteriaModel->obtenerPorUsuarioYLibro($_SESSION['user_id'], $libro_id);

    if ($registro) {
        // Actualizar existente
        $registro->estado = $nuevo_estado;
        if ($nuevo_estado === 'Ninguno') {
            $registro->eliminar();
        } else {
            $registro->actualizar();
        }
    } else if ($nuevo_estado !== 'Ninguno') {
        // Crear nuevo
        $nuevaEntrada = new Estanteria($conexion);
        $nuevaEntrada->usuario = (new Usuario($conexion))->obtenerPorId($_SESSION['user_id']);
        $nuevaEntrada->libro = (new Libro($conexion))->obtenerPorId($libro_id);
        $nuevaEntrada->estado = $nuevo_estado;
        $nuevaEntrada->crear();
    }

    header("Location: detalle_libro.php?id=" . $libro_id . "&msj=ok");
    exit;
}