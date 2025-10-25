<?php

declare(strict_types=1);

namespace App\Domain\Store\Repository;

interface UserRepositoryInterface
{
    /**
    * Récupère un utilisateur par son username.
    *
    * @param string $username
    * @return array{ id: string, username: string, password: string, email?: string }|null
    */
    public function findByUsername(string $username): ?array;

    /**
    * Vérifie les identifiants d'un utilisateur.
    *
    * @param string $username Le nom d'utilisateur
    * @param string $password Le mot de passe en clair à vérifier
    * @return array{
    *     id: string,
    *     username: string,
    *     password: string,
    *     email?: string
    * }|null Retourne l'utilisateur si les identifiants sont corrects, sinon null
    */
    public function verifyCredentials(string $username, string $password): ?array;
}
