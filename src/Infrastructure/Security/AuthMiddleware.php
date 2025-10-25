<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

class AuthMiddleware
{
    public function __construct(private JwtService $jwtService) {}

    public function handle(): ?object
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!is_string($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
            http_response_code(401);
            echo json_encode(['error' => 'Missing or invalid token']);
            return null;
        }

        $token = substr($authHeader, 7);
        $decoded = $this->jwtService->verifyToken($token);

        if (!$decoded) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired token']);
            return null;
        }

        return $decoded;
    }
}
