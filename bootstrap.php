<?php

use App\Application\Store\UseCase\CreateStoreHandler;
use App\Application\Store\UseCase\DeleteStoreHandler;
use App\Application\Store\UseCase\GetStoreHandler;
use App\Application\Store\UseCase\ListStoreHandler;
use App\Application\Store\UseCase\UpdateStoreHandler;
use App\Infrastructure\Controller\CreateStoreController;
use App\Infrastructure\Controller\DeleteStoreController;
use App\Infrastructure\Controller\GetStoreController;
use App\Infrastructure\Controller\ListStoreController;
use App\Infrastructure\Controller\UpdateStoreController;
use App\Infrastructure\Http\Router;
use App\Infrastructure\Database\SqliteStoreRepository;

$databaseUrl = getenv('DATABASE_URL') ?: '/data/database.sqlite';

$pdo = new PDO($databaseUrl);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$repository = new SqliteStoreRepository($pdo);

$createStoreController = new CreateStoreController(
    new CreateStoreHandler(
        $repository
    )
);

$listStoreController = new ListStoreController(
    new ListStoreHandler(
        $repository
    )
);

$getStoreController = new GetStoreController(
    new GetStoreHandler(
        $repository
    )
);

$updateStoreController = new UpdateStoreController(
    new UpdateStoreHandler(
        $repository
    )
);

$deleteStoreController = new DeleteStoreController(
    new DeleteStoreHandler(
        $repository
    )
);

$router = new Router();
$router->addRoute('/stores/create', $createStoreController, 'create');
$router->addRoute('/stores', $listStoreController, 'list');
$router->addRoute('/stores/show', $getStoreController, 'get');
$router->addRoute('/stores/update', $updateStoreController, 'update');
$router->addRoute('/stores/delete', $deleteStoreController, 'delete');


$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$router->handleRequest($requestUri);
