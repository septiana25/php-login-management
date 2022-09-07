<?php

namespace IanSeptiana\PHP\MVC\LOGIN\Repository;

use IanSeptiana\PHP\MVC\LOGIN\Domain\User;
use PDO;

/**
 * handle semua function logic yg berhubungan dengan database
 */
class UserRepository
{
    private PDO $conn;

    /**
     * set auto konek database dari parameter UserRpository
     *
     * @param PDO $conn
     */
    public function __construct( PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Insert data user lalu kembalikan data usernya
     *
     * @param User $user object domain
     * @return User
     */
    public function save(User $user): User
    {
        $statement = $this->conn->prepare("INSERT INTO users(id, name, password) VALUES(?,?,?)");
        $statement->execute([
            $user->id, $user->name, $user->password
        ]);
        return $user;
    }

    /**
     * Mencari data user berdasarkan id. cek terlebih dahulu id nya.
     *
     * @param string $id
     * @return User|null
     */
    public function findById(string $id): ?User
    {
        $statement = $this->conn->prepare("SELECT id, name, password FROM users WHERE id= ?");
        $statement->execute([$id]);

        try {
            if ($row = $statement->fetch()) {
                
                $user = new User();
                $user->id = $row['id'];
                $user->name = $row['name'];
                $user->password = $row['password'];
                
                return $user;
            }else{
                return null;
            }

        } finally {
           $statement->closeCursor();
        }
    }

    /**
     * hapus semua data user di database
     *
     * @return void
     */
    public function deleteAll()
    {
        $this->conn->exec("DELETE FROM users");
    }

    public function update(User $user): User
    {
        $statement = $this->conn->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");
        $statement->execute([$user->name, $user->password, $user->id]);

        return $user;
    }

}
