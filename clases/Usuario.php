<?php

require_once '../utils/conexion.php';

class Usuario {
    private PDO $db;

    public int     $id;
    public string  $nombre;
    public string  $email;
    public string  $password; 
    public string  $rol = 'usuario'; 
    public ?string $fecha_registro = null;

    public function __construct(PDO $conexion) {
        $this->db = $conexion;
    }

    // CREATE

    public function crear(): bool {
        $sql = "INSERT INTO usuarios (nombre, email, password, rol)
                VALUES (:nombre, :email, :password, :rol)";
        
        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([
            ':nombre'   => $this->nombre,
            ':email'    => $this->email,
            ':password' => $this->password,
            ':rol'      => $this->rol,
        ]);

        if ($ok) {
            $this->id = (int) $this->db->lastInsertId();
            $u = $this->obtenerPorId($this->id);
            $this->fecha_registro = $u?->fecha_registro;
        }
        return $ok;
    }

    // READ

    public function obtenerPorId(int $id): ?Usuario {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch();
        if (!$fila) return null;
        return $this->hidratar($fila);
    }

    public function obtenerPorEmail(string $email): ?Usuario {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $fila = $stmt->fetch();
        
        if (!$fila) return null;
        
        return $this->hidratar($fila);
    }

    public function verificarPassword(string $passwordPlano): bool {
        return $this->password === $passwordPlano;
    }

    // UPDATE

    public function actualizar(): bool {
        $sql  = "UPDATE usuarios
                 SET nombre = :nombre, email = :email,
                     password = :password, rol = :rol
                 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'   => $this->nombre,
            ':email'    => $this->email,
            ':password' => $this->password,
            ':rol'      => $this->rol,
            ':id'       => $this->id,
        ]);
    }

    // OTROS

    private function hidratar(array $fila): Usuario {
        $obj                = new Usuario($this->db);
        $obj->id            = (int) $fila['id'];
        $obj->nombre        = $fila['nombre'];
        $obj->email         = $fila['email'];
        $obj->password      = $fila['password'];
        $obj->rol           = $fila['rol'];
        $obj->fecha_registro = $fila['fecha_registro'];
        return $obj;
    }
}