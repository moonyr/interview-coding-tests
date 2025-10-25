<?php

use App\Application\Auth\UseCase\LoginHandler;
use App\Application\Store\UseCase\CreateStoreHandler;
use App\Application\Store\UseCase\DeleteStoreHandler;
use App\Application\Store\UseCase\GetStoreHandler;
use App\Application\Store\UseCase\ListStoreHandler;
use App\Application\Store\UseCase\UpdateStoreHandler;
use App\Infrastructure\Controller\AuthController;
use App\Infrastructure\Controller\CreateStoreController;
use App\Infrastructure\Controller\DeleteStoreController;
use App\Infrastructure\Controller\GetStoreController;
use App\Infrastructure\Controller\ListStoreController;
use App\Infrastructure\Controller\UpdateStoreController;
use App\Infrastructure\Http\Router;
use App\Infrastructure\Database\SqliteStoreRepository;
use App\Infrastructure\Database\SqliteUserRepository;
use App\Infrastructure\Security\FirebaseJwtWrapper;
use App\Infrastructure\Security\JwtService;

$databaseUrl = getenv('DATABASE_URL') ?: '/data/database.sqlite';

$pdo = new PDO($databaseUrl);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$storeRepository = new SqliteStoreRepository($pdo);
$userRepository = new SqliteUserRepository($pdo);

$fireBaseJwtWrapper = new FirebaseJwtWrapper('SECRET');
$jwtService = new JwtService($fireBaseJwtWrapper);

$authController = new AuthController(
    new LoginHandler(
        $userRepository,
        $jwtService
    )
);

$createStoreController = new CreateStoreController(
    new CreateStoreHandler(
        $storeRepository,
    ),
    $fireBaseJwtWrapper
);

$listStoreController = new ListStoreController(
    new ListStoreHandler(
        $storeRepository
    ),
    $fireBaseJwtWrapper
);

$getStoreController = new GetStoreController(
    new GetStoreHandler(
        $storeRepository
    ),
    $fireBaseJwtWrapper
);

$updateStoreController = new UpdateStoreController(
    new UpdateStoreHandler(
        $storeRepository
    ),
    $fireBaseJwtWrapper
);

$deleteStoreController = new DeleteStoreController(
    new DeleteStoreHandler(
        $storeRepository
    ),
    $fireBaseJwtWrapper
);

$router = new Router();
$router->addRoute('/login', $authController, 'login');
$router->addRoute('/stores/create', $createStoreController, 'create');
$router->addRoute('/stores', $listStoreController, 'list');
$router->addRoute('/stores/show', $getStoreController, 'get');
$router->addRoute('/stores/update', $updateStoreController, 'update');
$router->addRoute('/stores/delete', $deleteStoreController, 'delete');


$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$router->handleRequest($requestUri);
