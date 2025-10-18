<?php

declare(strict_types=1);

namespace App\Application\Store\UseCase;

use App\Domain\Store\Entity\Store;
use App\Domain\Store\Repository\StoreRepositoryInterface;
use DomainException;

class GetStoreHandler
{
    public function __construct(private StoreRepositoryInterface $repository)
    {
    }

    public function handle(string $identifiant): Store
    {
        $store = $this->repository->findById($identifiant);
        if (!$store) {
            throw new DomainException('Store not found');
        }
        return $store;
    }
}
