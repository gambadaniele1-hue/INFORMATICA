<?php
require_once("../src/Core/config.php");
use DI\Container;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

// 1. Configurazione del Container
$container = new Container();

$container->set(PDO::class, function () {
    $host = DB_HOST;
    $dbname = DB_NAME;
    $user = DB_USER;
    $pass = DB_PASS;

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
});

// 2. Inizializzazione dell'app
AppFactory::setContainer($container);
$app = AppFactory::create();

// Abilita il parsing del body per leggere JSON in POST/PUT
$app->addBodyParsingMiddleware();

// 3. Middleware intelligente per il Content-Type (JSON di default)
$app->add(function (Request $request, $handler) {
    $response = $handler->handle($request);
    if (!$response->hasHeader('Content-Type')) {
        return $response->withHeader('Content-Type', 'application/json');
    }
    return $response;
});

$app->addErrorMiddleware(true, true, true);

// 4. ROTTA BASE: Documentazione
$app->get("/", function (Request $request, Response $response) {
    $html = '<!DOCTYPE html><html><head><title>API Docs</title></head><body><h1>API Fornitori DB - Backend Attivo</h1></body></html>';
    $response->getBody()->write($html);
    return $response->withHeader('Content-Type', 'text/html');
});

// ==========================================
// ROTTE API (BACKEND)
// ==========================================

// Autenticazione
$app->post("/login", \App\Controllers\AuthController::class . ":login");

// Le 10 Query Pubbliche
$app->group('/api', function (RouteCollectorProxy $group) {
    for ($i = 1; $i <= 10; $i++) {
        $group->get("/query{$i}", \App\Controllers\EsercizioController::class . ":eseguiQuery{$i}");
    }
});

// Rotte Admin (Gestione Database)
$app->group('/admin', function (RouteCollectorProxy $group) {
    // Fornitori
    $group->get('/fornitori', \App\Controllers\AdminController::class . ':getFornitori');
    $group->post('/fornitori', \App\Controllers\AdminController::class . ':createFornitore');
    $group->put('/fornitori/{fid}', \App\Controllers\AdminController::class . ':updateFornitore');
    $group->delete('/fornitori/{fid}', \App\Controllers\AdminController::class . ':deleteFornitore');

    // Pezzi
    $group->get('/pezzi', \App\Controllers\AdminController::class . ':getPezzi');
    $group->post('/pezzi', \App\Controllers\AdminController::class . ':createPezzo');
    $group->put('/pezzi/{pid}', \App\Controllers\AdminController::class . ':updatePezzo');
    $group->delete('/pezzi/{pid}', \App\Controllers\AdminController::class . ':deletePezzo');

    // Catalogo
    $group->get('/catalogo', \App\Controllers\AdminController::class . ':getCatalogo');
    $group->post('/catalogo', \App\Controllers\AdminController::class . ':createCatalogo');
    $group->put('/catalogo/{fid}/{pid}', \App\Controllers\AdminController::class . ':updateCatalogo');
    $group->delete('/catalogo/{fid}/{pid}', \App\Controllers\AdminController::class . ':deleteCatalogo');
});

$app->run();