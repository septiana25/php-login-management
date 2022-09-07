<?php

namespace IanSeptiana\PHP\MVC\LOGIN\Repository;

use IanSeptiana\PHP\MVC\LOGIN\Domain\Session;
use PDO;

class SessionRepository  
{
    private PDO $conn;

    public function __construct(PDO $pdo)
    {
        $this->conn = $pdo;
    }

    public function save(Session $session): Session
    {
        $stmt = $this->conn->prepare("INSERT INTO sessions(id, user_id) VALUES (?,?)");
        $stmt->execute([$session->id, $session->userId]);

        return $session;
    }

    public function findById(string $id): ?Session
    {
        $stmt = $this->conn->prepare("SELECT id, user_id FROM sessions WHERE id = ?");
        $stmt->execute([$id]);

        try {
            if ($row = $stmt->fetch()) {
                $session = new Session();
                $session->id = $row['id'];
                $session->userId = $row['user_id'];

                return $session;
            }else{
                return null;
            }
        } finally {
            $stmt->closeCursor();
        }
    }

    public function deleteById(string $id): void
    {
        $stmt = $this->conn->prepare("DELETE FROM sessions WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function deleteAll(): void
    {
        $stmt = $this->conn->exec("DELETE FROM sessions");
    }

}
