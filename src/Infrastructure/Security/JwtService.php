<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    public function __construct(private JwtEncoderInterface $encoder)
    {
    }

    public function generateToken(string $userId): string
    {
        $payload = [
            'sub' => $userId,
            'iat' => time(),
            'exp' => time() + 3600
        ];
        return $this->encoder->encode($payload);
    }

    public function verifyToken(string $token): ?object
    {
        return $this->encoder->decode($token);
    }
}
