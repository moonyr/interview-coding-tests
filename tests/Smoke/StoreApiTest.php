<?php

namespace Tests\Smoke;

use App\Infrastructure\Security\JwtService;
use PDO;
use PHPUnit\Framework\TestCase;

class StoreApiTest extends TestCase
{
    private string $baseUrl = 'http://php-backend-web';
    private static string $testDbPath = '/app/data/database.sqlite';
    private static string $token = '';

    public static function setUpBeforeClass(): void
    {
        if (file_exists(self::$testDbPath)) {
            unlink(self::$testDbPath);
        }

        $pdo = new PDO('sqlite:' . self::$testDbPath, 0777);
        chmod(self::$testDbPath, 0666);
        
        $pdo->exec("
            CREATE TABLE stores (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                address TEXT NOT NULL,
                postal_code TEXT NOT NULL,
                city TEXT NOT NULL,
                country TEXT NOT NULL,
                phone_number TEXT NOT NULL,
                created_at TEXT NOT NULL,
                updated_at TEXT
            );
        ");

        $pdo->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                created_at TEXT NOT NULL
            );
        ");

        $hash = password_hash('test', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO users (username, password, created_at) VALUES ('test', '$hash', datetime('now'))");

        putenv("DATABASE_URL=sqlite:" . self::$testDbPath);

        $jwtService = new JwtService();
        self::$token = $jwtService->generateToken(1);
    }

    public function testCreateStore(): void
    {
        $data = [
            'name' => 'Magasin Test',
            'address' => '1 rue de Test',
            'postalCode' => '75000',
            'city' => 'Paris',
            'country' => 'France',
            'phoneNumber' => '+33123456789',
        ];

        $response = $this->curl('POST', '/stores/create', $data);
        $this->assertSame(201, $response['status']);
    }

    public function testGetStores(): void
    {
        $response = $this->curl('GET', '/stores');
        $this->assertSame(200, $response['status']);
        $this->assertIsArray(json_decode($response['body'], true));
    }

    public function testUpdateStore(): void
    {
        $data = [
            'name' => 'Magasin Modifié',
            'address' => '124 Rue Exemple',
        ];

        $response = $this->curl('PUT', '/stores/update?id=1', $data);
        $this->assertSame(200, $response['status']);

        $body = json_decode($response['body'], true);
        $this->assertArrayHasKey('status', $body);
        $this->assertSame('success', $body['status']);
    }

    public function testDeleteStore(): void
    {
        $response = $this->curl('DELETE', '/stores/delete?id=1');
        $this->assertSame(200, $response['status']);

        $body = json_decode($response['body'], true);
        $this->assertArrayHasKey('status', $body);
        $this->assertSame('success', $body['status']);
    }

    private function curl(string $method, string $endpoint, array $data = []): array
    {
        $ch = curl_init("{$this->baseUrl}{$endpoint}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . self::$token,
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if (in_array($method, ['POST', 'PUT'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ['status' => $status, 'body' => $body];
    }
}
