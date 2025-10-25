<?php

declare(strict_types=1);

namespace App\Application\Auth\UseCase;

use App\Infrastructure\Database\SqliteUserRepository;
use App\Infrastructure\Security\JwtService;

class LoginHandler
{
    public function __construct(
        private SqliteUserRepository $userRepository,
        private JwtService $jwtService
    ) {}

    public function handle(string $username, string $password): ?string
    {
        $user = $this->userRepository->verifyCredentials($username, $password);
        if (!$user) {
            return null;
        }

        return $this->jwtService->generateToken($user['id']);
    }
}
