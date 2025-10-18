<?php

declare(strict_types=1);

namespace App\Application\Store\UseCase;

use App\Domain\Store\Repository\StoreRepositoryInterface;
use DomainException;

class DeleteStoreHandler
{
    public function __construct(private StoreRepositoryInterface $repository)
    {
    }

    public function handle(string|null $identifiant): void
    {
        $store = $this->repository->findById($identifiant);
        if (!$store) {
            throw new DomainException('Store not found');
        }
        $this->repository->delete($store);
    }
}
