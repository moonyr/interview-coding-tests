<?php

declare(strict_types=1);

namespace App\Domain\Store\Repository;

use App\Domain\Store\Entity\Store;

interface StoreRepositoryInterface
{
    public function save(Store $store): void;

    public function update(Store $store): void;

    public function findById(string|null $identifiant): ?Store;

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, 'ASC'|'DESC'> $orderBy
     * @return Store[]
     */
    public function findAll(array $criteria = [], array $orderBy = []): array;

    public function delete(Store $store): void;
}
