<?php

require_once '../utils/conexion.php';
require_once 'Usuario.php';
require_once 'Libro.php';

class Estanteria {
    private PDO $db;

    public int     $id;
    public Usuario $usuario;
    public Libro   $libro;
    public string  $estado             = 'Ninguno';
    public int     $pagina_actual       = 0;
    public int     $progreso_porcentaje = 0;

    // Estados válidos según el ENUM de la BD
    public const ESTADOS = [
        'Actualmente leyendo',
        'Quiero leer',
        'Leídos',
        'Ninguno',
    ];

    public function __construct(PDO $conexion) {
        $this->db = $conexion;
    }

    // CREATE

    public function crear(): bool {
        $sql  = "INSERT INTO estanterias
                    (usuario_id, libro_id, estado, pagina_actual, progreso_porcentaje)
                 VALUES
                    (:usuario_id, :libro_id, :estado, :pagina_actual, :progreso_porcentaje)";
        $stmt = $this->db->prepare($sql);
        $ok   = $stmt->execute([
            ':usuario_id'          => $this->usuario->id,
            ':libro_id'            => $this->libro->id,
            ':estado'              => $this->estado,
            ':pagina_actual'       => $this->pagina_actual,
            ':progreso_porcentaje' => $this->progreso_porcentaje,
        ]);
        if ($ok) {
            $this->id = (int) $this->db->lastInsertId();
        }
        return $ok;
    }

    // READ

    public function obtenerPorId(int $id): ?Estanteria {
        $stmt = $this->db->prepare($this->sqlBase() . " WHERE e.id = :id");
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch();
        if (!$fila) return null;
        return $this->hidratar($fila);
    }

    public function obtenerPorUsuarioYLibro(int $usuarioId, int $libroId): ?Estanteria {
        $stmt = $this->db->prepare(
            $this->sqlBase() . " WHERE e.usuario_id = :uid AND e.libro_id = :lid"
        );
        $stmt->execute([':uid' => $usuarioId, ':lid' => $libroId]);
        $fila = $stmt->fetch();
        if (!$fila) return null;
        return $this->hidratar($fila);
    }

    public function obtenerPorUsuario(int $usuarioId, ?string $estado = null): array {
        $sql    = $this->sqlBase() . " WHERE e.usuario_id = :uid";
        $params = [':uid' => $usuarioId];
        if ($estado !== null) {
            $sql           .= " AND e.estado = :estado";
            $params[':estado'] = $estado;
        }
        $stmt = $this->db->prepare($sql . " ORDER BY l.titulo");
        $stmt->execute($params);
        return array_map(fn($f) => $this->hidratar($f), $stmt->fetchAll());
    }

    // UPDATE

    public function actualizar(): bool {
        $sql  = "UPDATE estanterias
                 SET estado = :estado,
                     pagina_actual = :pagina_actual,
                     progreso_porcentaje = :progreso_porcentaje
                 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':estado'              => $this->estado,
            ':pagina_actual'       => $this->pagina_actual,
            ':progreso_porcentaje' => $this->progreso_porcentaje,
            ':id'                  => $this->id,
        ]);
    }

    public function actualizarPagina(int $pagina): bool {
        $this->pagina_actual = $pagina;
        $totalPaginas        = $this->libro->paginas ?? 0;
        $this->progreso_porcentaje = $totalPaginas > 0
            ? (int) round(($pagina / $totalPaginas) * 100)
            : 0;
        return $this->actualizar();
    }

    // DELETE

    public function eliminar(): bool {
        $stmt = $this->db->prepare("DELETE FROM estanterias WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    // OTROS

    private function sqlBase(): string {
        return "SELECT e.*,
                       u.nombre AS u_nombre, u.email AS u_email,
                       u.password AS u_password, u.rol AS u_rol,
                       u.fecha_registro AS u_fecha_registro,
                       l.titulo AS l_titulo, l.descripcion AS l_descripcion,
                       l.portada AS l_portada, l.paginas AS l_paginas,
                       l.autor_id, a.nombre AS autor_nombre, a.bio AS autor_bio,
                       l.genero_id, g.nombre AS genero_nombre
                FROM estanterias e
                JOIN usuarios u ON e.usuario_id = u.id
                JOIN libros   l ON e.libro_id   = l.id
                JOIN autores  a ON l.autor_id   = a.id
                JOIN generos  g ON l.genero_id  = g.id";
    }

    private function hidratar(array $f): Estanteria {
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

        $obj                      = new Estanteria($this->db);
        $obj->id                  = (int) $f['id'];
        $obj->usuario             = $usuario;
        $obj->libro               = $libro;
        $obj->estado              = $f['estado'];
        $obj->pagina_actual       = (int) $f['pagina_actual'];
        $obj->progreso_porcentaje = (int) $f['progreso_porcentaje'];
        return $obj;
    }
}