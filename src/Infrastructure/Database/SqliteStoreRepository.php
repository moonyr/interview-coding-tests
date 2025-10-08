<?php

namespace App\Infrastructure\Database;

use App\Domain\Store\Entity\Store;
use App\Domain\Store\Repository\StoreRepositoryInterface;
use DateTimeImmutable;
use PDO;

class SqliteStoreRepository implements StoreRepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS stores (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                address TEXT NOT NULL,
                postal_code TEXT NOT NULL,
                city TEXT NOT NULL,
                country TEXT NOT NULL,
                phone_number TEXT NOT NULL,
                created_at TEXT NOT NULL,
                updated_at TEXT NULL
            )
        ");
    }

    public function save(Store $store): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO stores (name, address, postal_code, city, country, phone_number, created_at)
            VALUES (:name, :address, :postal_code, :city, :country, :phone_number, :created_at)
        ");
        $stmt->execute([
            ':name' => $store->getName(),
            ':address' => $store->getAddress(),
            ':postal_code' => $store->getPostalCode(),
            ':city' => $store->getCity(),
            ':country' => $store->getCountry(),
            ':phone_number' => $store->getPhoneNumber(),
            ':created_at' => $store->getCreatedAt()->format(DATE_ATOM),
        ]);
    }

    public function update(Store $store): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE stores
            SET name = :name,
                address = :address,
                postal_code = :postal_code,
                city = :city,
                country = :country,
                phone_number = :phone_number,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $store->getId(),
            ':name' => $store->getName(),
            ':address' => $store->getAddress(),
            ':postal_code' => $store->getPostalCode(),
            ':city' => $store->getCity(),
            ':country' => $store->getCountry(),
            ':phone_number' => $store->getPhoneNumber(),
        ]);
    }

    public function findById(string|null $identifiant): ?Store
    {
        $stmt = $this->pdo->prepare("SELECT * FROM stores WHERE id = :id");
        $stmt->execute([':id' => $identifiant]);
        /**
         * @var array<string>
         */
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Store(
            $row['id'],
            $row['name'],
            $row['address'],
            $row['postal_code'],
            $row['city'],
            $row['country'],
            $row['phone_number'],
            new DateTimeImmutable($row['created_at']),
        );
    }

    public function findAll(array $criteria = [], array $orderBy = []): array
    {
        $query = "SELECT * FROM stores";
        $params = [];
        if ($criteria) {
            $clauses = [];
            foreach ($criteria as $field => $value) {
                $clauses[] = "$field = :$field";
                $params[":$field"] = $value;
            }
            $query .= " WHERE " . implode(" AND ", $clauses);
        }

        if ($orderBy) {
            $orders = [];
            foreach ($orderBy as $field => $dir) {
                $dir = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';
                $orders[] = "$field $dir";
            }
            $query .= " ORDER BY " . implode(", ", $orders);
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        /**
         * @var array<array<string>> $rows
         */
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $stores = [];
        foreach ($rows as $row) {
            $stores[] = new Store(
                $row['id'],
                $row['name'],
                $row['address'],
                $row['postal_code'],
                $row['city'],
                $row['country'],
                $row['phone_number'],
                new DateTimeImmutable($row['created_at']),
            );
        }

        return $stores;
    }

    public function delete(Store $store): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM stores WHERE id = :id");
        $stmt->execute([':id' => $store->getId()]);
    }
}
