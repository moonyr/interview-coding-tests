<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Store\UseCase\ListStoreHandler;

class ListStoreController
{
    public function __construct(private ListStoreHandler $handler)
    {
    }

    public function list(): void
    {
        $criteria = [];
        $orderBy = [];

        if (isset($_GET['city'])) {
            $criteria['city'] = $_GET['city'];
        }
        if (isset($_GET['country'])) {
            $criteria['country'] = $_GET['country'];
        }
        if (isset($_GET['sort'])) {

            /**
             * @var string $field
             */
            $field = $_GET['sort'];
            /**
             * @var string $direction
             */
            $direction = $_GET['direction'] ?? 'ASC';
            $orderBy[$field] = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        }

        /** @var array<\App\Domain\Store\Entity\Store> $stores */
        $stores = $this->handler->handle($criteria, $orderBy);

        $result = array_map(fn($store) => [
            'id' => $store->getId(),
            'name' => $store->getName(),
            'address' => $store->getAddress(),
            'postalCode' => $store->getPostalCode(),
            'city' => $store->getCity(),
            'country' => $store->getCountry(),
            'phoneNumber' => $store->getPhoneNumber(),
            'createdAt' => $store->getCreatedAt()->format(DATE_ATOM),
        ], $stores);

        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
