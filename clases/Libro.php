<?php

require_once '../utils/conexion.php';
require_once 'Autor.php';
require_once 'Genero.php';

class Libro {
    private PDO $db;

    public int     $id;
    public string  $titulo;
    public Autor   $autor;
    public Genero  $genero;
    public ?string $descripcion = null;
    public ?string $portada     = null;
    public ?int    $paginas     = null;

    public function __construct(PDO $conexion) {
        $this->db = $conexion;
    }

    // CREATE

    public function crear(): bool {
        $sql  = "INSERT INTO libros (titulo, descripcion, portada, paginas, autor_id, genero_id)
                 VALUES (:titulo, :descripcion, :portada, :paginas, :autor_id, :genero_id)";
        $stmt = $this->db->prepare($sql);
        $ok   = $stmt->execute([
            ':titulo'       => $this->titulo,
            ':descripcion'  => $this->descripcion,
            ':portada'      => $this->portada,
            ':paginas'      => $this->paginas,
            ':autor_id'     => $this->autor->id,
            ':genero_id'    => $this->genero->id,
        ]);
        if ($ok) {
            $this->id = (int) $this->db->lastInsertId();
        }
        return $ok;
    }

    // READ

    public function obtenerPorId(int $id): ?Libro {
        $sql = "SELECT l.*,
                       a.nombre AS autor_nombre, a.bio AS autor_bio,
                       g.nombre AS genero_nombre
                FROM libros l
                JOIN autores a ON l.autor_id  = a.id
                JOIN generos g ON l.genero_id = g.id
                WHERE l.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch();
        if (!$fila) return null;
        return $this->hidratar($fila);
    }

    public function obtenerTodos(): array {
        $sql  = "SELECT l.*,
                        a.nombre AS autor_nombre, a.bio AS autor_bio,
                        g.nombre AS genero_nombre
                 FROM libros l
                 JOIN autores a ON l.autor_id  = a.id
                 JOIN generos g ON l.genero_id = g.id
                 ORDER BY l.titulo";
        $stmt = $this->db->query($sql);
        return array_map(fn($f) => $this->hidratar($f), $stmt->fetchAll());
    }

    public function obtenerPorGenero(int $generoId): array {
        $sql  = "SELECT l.*,
                        a.nombre AS autor_nombre, a.bio AS autor_bio,
                        g.nombre AS genero_nombre
                 FROM libros l
                 JOIN autores a ON l.autor_id  = a.id
                 JOIN generos g ON l.genero_id = g.id
                 WHERE l.genero_id = :genero_id
                 ORDER BY l.titulo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':genero_id' => $generoId]);
        return array_map(fn($f) => $this->hidratar($f), $stmt->fetchAll());
    }

    public function buscarPorTitulo(string $termino): array {
        $sql  = "SELECT l.*,
                        a.nombre AS autor_nombre, a.bio AS autor_bio,
                        g.nombre AS genero_nombre
                 FROM libros l
                 JOIN autores a ON l.autor_id  = a.id
                 JOIN generos g ON l.genero_id = g.id
                 WHERE l.titulo LIKE :termino
                 ORDER BY l.titulo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':termino' => '%' . $termino . '%']);
        return array_map(fn($f) => $this->hidratar($f), $stmt->fetchAll());
    }

    public function buscar(string $termino = '', int $generoId = 0): array {
        $sql = "SELECT l.*,
                    a.nombre AS autor_nombre, a.bio AS autor_bio,
                    g.nombre AS genero_nombre
                FROM libros l
                JOIN autores a ON l.autor_id  = a.id
                JOIN generos g ON l.genero_id = g.id
                WHERE 1=1";

        $params = [];

        if ($termino !== '') {
            $sql .= " AND (l.titulo LIKE :termino OR a.nombre LIKE :termino)";
            $params[':termino'] = '%' . $termino . '%';
        }

        if ($generoId > 0) {
            $sql .= " AND l.genero_id = :genero_id";
            $params[':genero_id'] = $generoId;
        }

        $sql .= " ORDER BY l.titulo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return array_map(fn($f) => $this->hidratar($f), $stmt->fetchAll());
    }

    public function buscarConPaginacion(string $termino = '', int $generoId = 0, int $limit = 8, int $offset = 0): array {
        $sql = "SELECT l.*,
                    a.nombre AS autor_nombre, a.bio AS autor_bio,
                    g.nombre AS genero_nombre
                FROM libros l
                JOIN autores a ON l.autor_id  = a.id
                JOIN generos g ON l.genero_id = g.id
                WHERE 1=1";

        $params = [];

        if ($termino !== '') {
            $sql .= " AND (l.titulo LIKE :termino OR a.nombre LIKE :termino)";
            $params[':termino'] = '%' . $termino . '%';
        }

        if ($generoId > 0) {
            $sql .= " AND l.genero_id = :genero_id";
            $params[':genero_id'] = $generoId;
        }

        $sql .= " ORDER BY l.titulo LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return array_map(fn($f) => $this->hidratar($f), $stmt->fetchAll());
    }

    public function contarResultadosBusqueda(string $termino = '', int $generoId = 0): int {
        $sql = "SELECT COUNT(*) 
                FROM libros l
                JOIN autores a ON l.autor_id = a.id
                WHERE 1=1";

        $params = [];

        if ($termino !== '') {
            $sql .= " AND (l.titulo LIKE :termino OR a.nombre LIKE :termino)";
            $params[':termino'] = '%' . $termino . '%';
        }

        if ($generoId > 0) {
            $sql .= " AND l.genero_id = :genero_id";
            $params[':genero_id'] = $generoId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    // UPDATE

    public function actualizar(): bool {
        $sql  = "UPDATE libros
                 SET titulo = :titulo, descripcion = :descripcion,
                     portada = :portada, paginas = :paginas,
                     autor_id = :autor_id, genero_id = :genero_id
                 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titulo'      => $this->titulo,
            ':descripcion' => $this->descripcion,
            ':portada'     => $this->portada,
            ':paginas'     => $this->paginas,
            ':autor_id'    => $this->autor->id,
            ':genero_id'   => $this->genero->id,
            ':id'          => $this->id,
        ]);
    }

    // DELETE

    public function eliminar(): bool {
        $stmt = $this->db->prepare("DELETE FROM libros WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    // OTROS

    private function hidratar(array $fila): Libro {
        $autor         = new Autor($this->db);
        $autor->id     = (int) $fila['autor_id'];
        $autor->nombre = $fila['autor_nombre'];
        $autor->bio    = $fila['autor_bio'];

        $genero         = new Genero($this->db);
        $genero->id     = (int) $fila['genero_id'];
        $genero->nombre = $fila['genero_nombre'];

        $obj              = new Libro($this->db);
        $obj->id          = (int) $fila['id'];
        $obj->titulo      = $fila['titulo'];
        $obj->descripcion = $fila['descripcion'];
        $obj->portada     = $fila['portada'];
        $obj->paginas     = $fila['paginas'] !== null ? (int) $fila['paginas'] : null;
        $obj->autor       = $autor;
        $obj->genero      = $genero;
        return $obj;
    }
}