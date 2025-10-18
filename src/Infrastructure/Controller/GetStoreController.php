<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Store\UseCase\GetStoreHandler;

class GetStoreController
{
    public function __construct(private GetStoreHandler $handler)
    {
    }

    public function get(): void
    {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing id parameter']);
            return;
        }

        /** @var string $identifiant */
        $identifiant = $_GET['id'];

        try {
            $store = $this->handler->handle($identifiant);
            echo json_encode([
                'id' => $store->getId(),
                'name' => $store->getName(),
                'address' => $store->getAddress(),
                'postalCode' => $store->getPostalCode(),
                'city' => $store->getCity(),
                'country' => $store->getCountry(),
                'phoneNumber' => $store->getPhoneNumber(),
                'createdAt' => $store->getCreatedAt()->format(DATE_ATOM),
            ]);
        } catch (\DomainException $e) {
            http_response_code(404);
            echo json_encode(['error' => 'Store not found']);
            return;
        }
    }
}
