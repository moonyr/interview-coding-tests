<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class FirebaseJwtWrapper implements JwtEncoderInterface {
    public function __construct(private string $secret) {}

    /**@param array<string, string> $payload */
    public function encode(array $payload): string {
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function decode(string $token): ?object {
        try {
            return JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }
}