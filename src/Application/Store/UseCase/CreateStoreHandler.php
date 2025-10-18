<?php

declare(strict_types=1);

namespace App\Application\Store\UseCase;

use App\Domain\Store\Entity\Store;
use App\Domain\Store\Repository\StoreRepositoryInterface;
use DateTimeImmutable;

class CreateStoreHandler
{
    public function __construct(private StoreRepositoryInterface $repository)
    {
    }

    /**
     * @param array<string, string> $data
     */
    public function handle(array $data): Store
    {
        $store = new Store(
            null,
            $data['name'] ?? '',
            $data['address'] ?? '',
            $data['postalCode'] ?? '',
            $data['city'] ?? '',
            $data['country'] ?? '',
            $data['phoneNumber'] ?? '',
            new DateTimeImmutable(),
        );

        $this->repository->save($store);

        return $store;
    }
}
