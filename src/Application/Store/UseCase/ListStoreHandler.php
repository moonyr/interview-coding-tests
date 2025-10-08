<?php

declare(strict_types=1);

namespace App\Application\Store\UseCase;

use App\Domain\Store\Repository\StoreRepositoryInterface;

class ListStoreHandler
{
    public function __construct(private StoreRepositoryInterface $repository)
    {
    }

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, 'ASC'|'DESC'> $orderBy
     *
     * @return array<\App\Domain\Store\Entity\Store>
     */
    public function handle(array $criteria = [], array $orderBy = []): array
    {
        return $this->repository->findAll($criteria, $orderBy);
    }
}
