<?php

require_once '../utils/conexion.php';

class Genero {
    private PDO $db;

    public int $id;
    public string $nombre;

    public function __construct(PDO $conexion) {
        $this->db = $conexion;
    }

    // CREATE

    public function crear(): bool {
        $sql  = "INSERT INTO generos (nombre) VALUES (:nombre)";
        $stmt = $this->db->prepare($sql);
        $ok   = $stmt->execute([':nombre' => $this->nombre]);
        if ($ok) {
            $this->id = (int) $this->db->lastInsertId();
        }
        return $ok;
    }

    // READ

    public function obtenerPorId(int $id): ?Genero {
        $stmt = $this->db->prepare("SELECT * FROM generos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch();
        if (!$fila) return null;
        return $this->hidratar($fila);
    }

    public function obtenerTodos(): array {
        $stmt = $this->db->query("SELECT * FROM generos ORDER BY nombre");
        return array_map(fn($f) => $this->hidratar($f), $stmt->fetchAll());
    }

    // UPDATE

    public function actualizar(): bool {
        $sql  = "UPDATE generos SET nombre = :nombre WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $this->nombre,
            ':id'     => $this->id,
        ]);
    }

    // DELETE

    public function eliminar(): bool {
        $stmt = $this->db->prepare("DELETE FROM generos WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    // OTROS

    private function hidratar(array $fila): Genero {
    $obj = new Genero($this->db);
    $obj->id = (int) $fila['id'];
    $obj->nombre = $fila['nombre'];
    return $obj;
}
}