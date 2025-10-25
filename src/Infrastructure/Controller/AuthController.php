<?php

namespace App\Infrastructure\Controller;

use App\Application\Auth\UseCase\LoginHandler;

class AuthController
{
    public function __construct(private LoginHandler $handler) {}

    public function login(): void
    {        
        $raw = file_get_contents('php://input');
        if ($raw === false) {
            http_response_code(400);
            echo json_encode(['error' => 'Corps de requête invalide']);
            return;
        }
        
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'JSON invalide']);
            return;
        }

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        if (!is_string($username) || !is_string($password)) {
            http_response_code(400);
            echo json_encode(['error' => 'Champs invalides']);
            return;
        }

        $token = $this->handler->handle($username, $password);

        if (!$token) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode(['token' => $token]);
    }
}
