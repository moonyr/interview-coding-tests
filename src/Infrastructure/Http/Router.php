<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

class Router
{
    /**
     * @var array<string, array{object, string}>
     * Example: '/home' => [HomeController::class, 'index']
     */
    private array $routes = [];

    public function addRoute(string $uri, object $controller, string $method): void
    {
        $this->routes[$uri] = [$controller, $method];
    }

    public function handleRequest(string $requestUri): void
    {
        $uri = parse_url($requestUri, PHP_URL_PATH);

        if (!isset($this->routes[$uri])) {
            http_response_code(404);
            echo "Route not found";
            return;
        }

        [$controller, $method] = $this->routes[$uri];
        $controller->$method();
    }
}
