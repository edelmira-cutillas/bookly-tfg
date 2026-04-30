<?php

require_once '../utils/conexion.php';
require_once 'Usuario.php';
require_once 'Libro.php';

class Resenya {
    private PDO $db;

    public int     $id;
    public Usuario $usuario;
    public Libro   $libro;
    public int     $calificacion;   // 1-5
    public ?string $comentario = null;

    public function __construct(PDO $conexion) {
        $this->db = $conexion;
    }

    // CREATE

    public function crear(): bool {
        $this->validarCalificacion();
        $sql  = "INSERT INTO resenyas (usuario_id, libro_id, calificacion, comentario)
                 VALUES (:usuario_id, :libro_id, :calificacion, :comentario)";
        $stmt = $this->db->prepare($sql);
        $ok   = $stmt->execute([
            ':usuario_id'  => $this->usuario->id,
            ':libro_id'    => $this->libro->id,
            ':calificacion' => $this->calificacion,
            ':comentario'  => $this->comentario,
        ]);
        if ($ok) {
            $this->id = (int) $this->db->lastInsertId();
        }
        return $ok;
    }

    // READ

    public function obtenerPorId(int $id): ?Resenya {
        $stmt = $this->db->prepare($this->sqlBase() . " WHERE r.id = :id");
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch();
        if (!$fila) return null;
        return $this->hidratar($fila);
    }

    public function obtenerPorLibro(int $libroId): array {
        $stmt = $this->db->prepare(
            $this->sqlBase() . " WHERE r.libro_id = :lid ORDER BY r.id DESC"
        );
        $stmt->execute([':lid' => $libroId]);
        return array_map(fn($f) => $this->hidratar($f), $stmt->fetchAll());
    }

    public function obtenerPorUsuario(int $usuarioId): array {
        $stmt = $this->db->prepare(
            $this->sqlBase() . " WHERE r.usuario_id = :uid ORDER BY r.id DESC"
        );
        $stmt->execute([':uid' => $usuarioId]);
        return array_map(fn($f) => $this->hidratar($f), $stmt->fetchAll());
    }

    public function promedioCalificacion(int $libroId): float {
        $stmt = $this->db->prepare(
            "SELECT AVG(calificacion) as promedio FROM resenyas WHERE libro_id = :lid"
        );
        $stmt->execute([':lid' => $libroId]);
        return (float) ($stmt->fetchColumn() ?? 0.0);
    }

    // UPDATE

    public function actualizar(): bool {
        $this->validarCalificacion();
        $sql  = "UPDATE resenyas
                 SET calificacion = :calificacion, comentario = :comentario
                 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':calificacion' => $this->calificacion,
            ':comentario'  => $this->comentario,
            ':id'          => $this->id,
        ]);
    }

    // DELETE

    public function eliminar(): bool {
        $stmt = $this->db->prepare("DELETE FROM resenyas WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    // OTROS

    private function validarCalificacion(): void {
        if ($this->calificacion < 1 || $this->calificacion > 5) {
            throw new InvalidArgumentException("La calificación debe estar entre 1 y 5.");
        }
    }

    private function sqlBase(): string {
        return "SELECT r.*,
                       u.nombre AS u_nombre, u.email AS u_email,
                       u.password AS u_password, u.rol AS u_rol,
                       u.fecha_registro AS u_fecha_registro,
                       l.titulo AS l_titulo, l.descripcion AS l_descripcion,
                       l.portada AS l_portada, l.paginas AS l_paginas,
                       l.autor_id, a.nombre AS autor_nombre, a.bio AS autor_bio,
                       l.genero_id, g.nombre AS genero_nombre
                FROM resenyas r
                JOIN usuarios u ON r.usuario_id = u.id
                JOIN libros   l ON r.libro_id   = l.id
                JOIN autores  a ON l.autor_id   = a.id
                JOIN generos  g ON l.genero_id  = g.id";
    }

    private function hidratar(array $f): Resenya {
        $usuario                 = new Usuario($this->db);
        $usuario->id             = (int) $f['usuario_id'];
        $usuario->nombre         = $f['u_nombre'];
        $usuario->email          = $f['u_email'];
        $usuario->password       = $f['u_password'];
        $usuario->rol            = $f['u_rol'];
        $usuario->fecha_registro = $f['u_fecha_registro'];

        $autor         = new Autor($this->db);
        $autor->id     = (int) $f['autor_id'];
        $autor->nombre = $f['autor_nombre'];
        $autor->bio    = $f['autor_bio'];

        $genero         = new Genero($this->db);
        $genero->id     = (int) $f['genero_id'];
        $genero->nombre = $f['genero_nombre'];

        $libro              = new Libro($this->db);
        $libro->id          = (int) $f['libro_id'];
        $libro->titulo      = $f['l_titulo'];
        $libro->descripcion = $f['l_descripcion'];
        $libro->portada     = $f['l_portada'];
        $libro->paginas     = $f['l_paginas'] !== null ? (int) $f['l_paginas'] : null;
        $libro->autor       = $autor;
        $libro->genero      = $genero;

        $obj               = new Resenya($this->db);
        $obj->id           = (int) $f['id'];
        $obj->usuario      = $usuario;
        $obj->libro        = $libro;
        $obj->calificacion = (int) $f['calificacion'];
        $obj->comentario   = $f['comentario'];
        return $obj;
    }
}