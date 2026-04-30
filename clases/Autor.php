<?php

require_once '../utils/conexion.php';

class Autor {
    private PDO $db;

    public int     $id;
    public string  $nombre;
    public ?string $bio = null;

    public function __construct(PDO $conexion) {
        $this->db = $conexion;
    }

    // CREATE

    public function crear(): bool {
        $sql  = "INSERT INTO autores (nombre, bio) VALUES (:nombre, :bio)";
        $stmt = $this->db->prepare($sql);
        $ok   = $stmt->execute([
            ':nombre' => $this->nombre,
            ':bio'    => $this->bio,
        ]);
        if ($ok) {
            $this->id = (int) $this->db->lastInsertId();
        }
        return $ok;
    }

    // READ

    public function obtenerPorId(int $id): ?Autor {
        $stmt = $this->db->prepare("SELECT * FROM autores WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch();
        if (!$fila) return null;
        return $this->hidratar($fila);
    }

    public function obtenerTodos(): array {
        $stmt = $this->db->query("SELECT * FROM autores ORDER BY nombre");
        return array_map(fn($f) => $this->hidratar($f), $stmt->fetchAll());
    }

    // UPDATE

    public function actualizar(): bool {
        $sql  = "UPDATE autores SET nombre = :nombre, bio = :bio WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $this->nombre,
            ':bio'    => $this->bio,
            ':id'     => $this->id,
        ]);
    }

    // DELETE

    public function eliminar(): bool {
        $stmt = $this->db->prepare("DELETE FROM autores WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    // OTROS

    private function hidratar(array $fila): Autor {
        $obj         = new Autor($this->db);
        $obj->id     = (int) $fila['id'];
        $obj->nombre = $fila['nombre'];
        $obj->bio    = $fila['bio'];
        return $obj;
    }
}