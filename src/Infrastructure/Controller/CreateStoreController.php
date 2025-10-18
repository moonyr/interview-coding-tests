<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Store\UseCase\CreateStoreHandler;

class CreateStoreController
{
    public function __construct(private CreateStoreHandler $createStoreHandler)
    {
    }

    public function create(): void
    {
        /**
         * @var string $json
         */
        $json = file_get_contents('php://input');
        /**
         * @var array<string, string> $data
         */
        $data = json_decode($json, true) ?? [];

        try {
            $store = $this->createStoreHandler->handle($data);

            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'store' => [
                    'name' => $store->getName(),
                    'address' => $store->getAddress(),
                    'postalCode' => $store->getPostalCode(),
                    'city' => $store->getCity(),
                    'country' => $store->getCountry(),
                    'phoneNumber' => $store->getPhoneNumber(),
                    'createdAt' => $store->getCreatedAt()->format(DATE_ATOM),
                ],
            ]);
        } catch (\DomainException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Internal server error']);
        }
    }
}
