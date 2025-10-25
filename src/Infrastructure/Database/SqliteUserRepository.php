<?php

namespace App\Infrastructure\Database;

use App\Domain\Store\Repository\UserRepositoryInterface;
use DateTime;
use PDO;

class SqliteUserRepository implements UserRepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                created_at TEXT NOT NULL
            )
        ");

        $this->ensureTestUser();
    }

    private function ensureTestUser(): void
    {
        $username = 'test';
        $plainPassword = 'test';

        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);

        if ($stmt->fetch(PDO::FETCH_ASSOC) === false) {
            $hash = password_hash($plainPassword, PASSWORD_DEFAULT);
            $insert = $this->pdo->prepare("
                INSERT INTO users (username, password, created_at)
                VALUES (:username, :password, :created_at)
            ");
            $insert->execute([
                'username'   => $username,
                'password'   => $hash,
                'created_at' => (new DateTime())->format(DATE_ATOM),
            ]);
        }
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            return null;
        }

        /** @var array{id: string, username: string, password: string, email?: string} $user */
        return $user;
    }

    public function verifyCredentials(string $username, string $password): ?array
    {
        $user = $this->findByUsername($username);
        if (!$user) {
            return null;
        }

        if (password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }
}
