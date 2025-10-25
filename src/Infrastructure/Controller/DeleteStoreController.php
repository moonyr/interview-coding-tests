<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Store\UseCase\DeleteStoreHandler;
use App\Domain\Store\Repository\StoreRepositoryInterface;
use App\Infrastructure\Security\AuthMiddleware;
use App\Infrastructure\Security\JwtEncoderInterface;
use App\Infrastructure\Security\JwtService;

class DeleteStoreController
{
    public function __construct(
        private DeleteStoreHandler $handler,
        private JwtEncoderInterface $encoder,
    )
    {
    }

    public function delete(): void
    {
        $jwtService = new JwtService($this->encoder);

        $authMiddleware = new AuthMiddleware($jwtService);
        $jwtPayload = $authMiddleware->handle();
        if ($jwtPayload === null) {
            return;
        }

        /**
        * @var string|null $identifiant
        */
        $identifiant = $_GET['id'] ?? null;

        try {
            $this->handler->handle($identifiant);
            echo json_encode(['status' => 'success']);
        } catch (\DomainException $e) {
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
