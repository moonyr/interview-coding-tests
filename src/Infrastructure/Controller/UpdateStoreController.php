<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Store\UseCase\UpdateStoreHandler;
use App\Infrastructure\Security\AuthMiddleware;
use App\Infrastructure\Security\JwtEncoderInterface;
use App\Infrastructure\Security\JwtService;

class UpdateStoreController
{
    public function __construct(
        private UpdateStoreHandler $handler,
        private JwtEncoderInterface $encoder,
    )
    {
    }

    public function update(): void
    {
        $jwtService = new JwtService($this->encoder);

        $authMiddleware = new AuthMiddleware($jwtService);
        $jwtPayload = $authMiddleware->handle();
        if ($jwtPayload === null) {
            return;
        }

        /**
         * @var string $json
         */
        $json = file_get_contents('php://input');
        $data = json_decode($json, true) ?? [];
        $identifiant = $_GET['id'] ?? null;

        try {
            /**
             * @var string|null $identifiant
             * @var array<string, string> $data
             */
            $store = $this->handler->handle($identifiant, $data);
            http_response_code(200);
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
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
