<?php

declare(strict_types=1);

namespace App\Application\Store\UseCase;

use App\Domain\Store\Entity\Store;
use App\Domain\Store\Repository\StoreRepositoryInterface;
use DomainException;

class UpdateStoreHandler
{
    public function __construct(private StoreRepositoryInterface $repository)
    {
    }

    /**
     * @param string|null $identifiant
     * @param array<string, string> $data
     */
    public function handle(string|null $identifiant, array $data): Store
    {
        $store = $this->repository->findById($identifiant);
        if (!$store) {
            throw new DomainException('Store not found');
        }

        $store->update(
            $data['name'] ?? null,
            $data['address'] ?? null,
            $data['postalCode'] ?? null,
            $data['city'] ?? null,
            $data['country'] ?? null,
            $data['phoneNumber'] ?? null
        );

        $this->repository->update($store);

        return $store;
    }
}
