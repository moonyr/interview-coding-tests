<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

interface JwtEncoderInterface {
    
    /**
     * Encode un payload en JWT.
     *
     * @param array<string, int|string> $payload Tableau associatif contenant les données du token
     * @return string Le JWT encodé
     */
    public function encode(array $payload): string;
    
    /**
     * Décode un JWT et retourne le payload.
     *
     * @param string $token Le JWT à décoder
     * @return object|null Le payload décodé sous forme d'objet, ou null si invalide
     */
    public function decode(string $token): ?object;
}
