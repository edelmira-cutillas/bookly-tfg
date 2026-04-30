<?php

require_once '../utils/conexion.php';
require_once 'Usuario.php';
require_once 'Libro.php';

class Anotacion {
    private PDO $db;

    public int     $id;
    public Usuario $usuario;
    public Libro   $libro;
    public string  $texto;
    public string  $tipo   = 'Nota';   // 'Cita' | 'Fragmento' | 'Nota'
    public ?int    $pagina = null;

    public const TIPOS = ['Cita', 'Fragmento', 'Nota'];

    public function __construct(PDO $conexion) {
         $this->db = $conexion;
    }

    // CREATE

    public function crear(): bool {
        $this->validarTipo();
        $sql  = "INSERT INTO anotaciones (usuario_id, libro_id, texto, tipo, pagina)
                 VALUES (:usuario_id, :libro_id, :texto, :tipo, :pagina)";
        $stmt = $this->db->prepare($sql);
        $ok   = $stmt->execute([
            ':usuario_id' => $this->usuario->id,
            ':libro_id'   => $this->libro->id,
            ':texto'      => $this->texto,
            ':tipo'       => $this->tipo,
            ':pagina'     => $this->pagina,
        ]);
        if ($ok) {
            $this->id = (int) $this->db->lastInsertId();
        }
        return $ok;
    }

    // READ

    public function obtenerPorId(int $id): ?Anotacion {
        $stmt = $this->db->prepare($this->sqlBase() . " WHERE an.id = :id");
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch();
        if (!$fila) return null;
        return $this->hidratar($fila);
    }

    public function obtenerPorUsuarioYLibro(int $usuarioId, int $libroId): array {
        $stmt = $this->db->prepare(
            $this->sqlBase() . " WHERE an.usuario_id = :uid AND an.libro_id = :lid
             ORDER BY an.pagina ASC, an.id ASC"
        );
        $stmt->execute([':uid' => $usuarioId, ':lid' => $libroId]);
        return array_map(fn($f) => $this->hidratar($f), $stmt->fetchAll());
    }

    public function obtenerPorUsuario(int $usuarioId, ?string $tipo = null): array {
        $sql    = $this->sqlBase() . " WHERE an.usuario_id = :uid";
        $params = [':uid' => $usuarioId];
        if ($tipo !== null) {
            $sql           .= " AND an.tipo = :tipo";
            $params[':tipo'] = $tipo;
        }
        $stmt = $this->db->prepare($sql . " ORDER BY l.titulo, an.pagina ASC");
        $stmt->execute($params);
        return array_map(fn($f) => $this->hidratar($f), $stmt->fetchAll());
    }

    // UPDATE

    public function actualizar(): bool {
        $this->validarTipo();
        $sql  = "UPDATE anotaciones
                 SET texto = :texto, tipo = :tipo, pagina = :pagina
                 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':texto'  => $this->texto,
            ':tipo'   => $this->tipo,
            ':pagina' => $this->pagina,
            ':id'     => $this->id,
        ]);
    }

    // DELETE

    public function eliminar(): bool {
        $stmt = $this->db->prepare("DELETE FROM anotaciones WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    // OTROS

    private function validarTipo(): void {
        if (!in_array($this->tipo, self::TIPOS, true)) {
            throw new InvalidArgumentException(
                "Tipo inválido. Usa: " . implode(', ', self::TIPOS)
            );
        }
    }

    private function sqlBase(): string {
        return "SELECT an.*,
                       u.nombre AS u_nombre, u.email AS u_email,
                       u.password AS u_password, u.rol AS u_rol,
                       u.fecha_registro AS u_fecha_registro,
                       l.titulo AS l_titulo, l.descripcion AS l_descripcion,
                       l.portada AS l_portada, l.paginas AS l_paginas,
                       l.autor_id, a.nombre AS autor_nombre, a.bio AS autor_bio,
                       l.genero_id, g.nombre AS genero_nombre
                FROM anotaciones an
                JOIN usuarios u ON an.usuario_id = u.id
                JOIN libros   l ON an.libro_id   = l.id
                JOIN autores  a ON l.autor_id    = a.id
                JOIN generos  g ON l.genero_id   = g.id";
    }

    private function hidratar(array $f): Anotacion {
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

        $obj           = new Anotacion($this->db);
        $obj->id       = (int) $f['id'];
        $obj->usuario  = $usuario;
        $obj->libro    = $libro;
        $obj->texto    = $f['texto'];
        $obj->tipo     = $f['tipo'];
        $obj->pagina   = $f['pagina'] !== null ? (int) $f['pagina'] : null;
        return $obj;
    }
}