<?php
session_start();

// --- AGGIUNGI QUESTE DUE RIGHE PER IL DEBUG ---
error_reporting(E_ALL);
ini_set('display_errors', '1');

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// --- AGGIUNGI ANCHE QUESTO PER SLIM ---
$app->addErrorMiddleware(true, true, true);

// L'URL della tua API locale
$apiBaseUrl = 'http://localhost:8010';

// ==========================================
// ROTTE PUBBLICHE (HOME E QUERY)
// ==========================================
$app->get('/', function (Request $request, Response $response) {
    return $response->withHeader('Location', '/query/1')->withStatus(302);
});

$app->get('/query/{id}', function (Request $request, Response $response, $args) use ($apiBaseUrl) {
    $id = (int)$args['id'];
    
    $queryInfo = [
        1 => ['title' => '1. Pezzi distinti', 'params' => []],
        2 => ['title' => '2. Fornitori (tutti i pezzi)', 'params' => []],
        3 => ['title' => '3. Fornitori (tutti i pezzi x colore)', 'params' => ['colore']],
        4 => ['title' => '4. Pezzi esclusivi', 'params' => ['azienda']],
        5 => ['title' => '5. Fornitori costosi', 'params' => []],
        6 => ['title' => '6. Prezzo più alto in assoluto', 'params' => []],
        7 => ['title' => '7. Fornitori esclusivi x colore', 'params' => ['colore']],
        8 => ['title' => '8. Fornitori bicolore (AND)', 'params' => ['colore1', 'colore2']],
        9 => ['title' => '9. Fornitori bicolore (OR)', 'params' => ['colore1', 'colore2']],
        10 => ['title' => '10. Pezzi multi-fornitore', 'params' => ['min_fornitori']]
    ];

    if (!array_key_exists($id, $queryInfo)) {
        $response->getBody()->write("Errore: Endpoint non trovato.");
        return $response->withStatus(404);
    }

    $queryParams = $request->getQueryParams();
    $actualLimit = (int)($queryParams['limit'] ?? 10);
    $apiParams = $queryParams;
    $apiParams['limit'] = $actualLimit + 1; // Trucco del +1 per l'impaginazione

    $queryString = !empty($apiParams) ? '?' . http_build_query($apiParams) : '';
    // Chiamata all'API locale (es: http://localhost:8010/api/query1)
    $apiUrl = $apiBaseUrl . '/api/query' . $id . $queryString;

    $apiResponse = @file_get_contents($apiUrl);
    $result = $apiResponse ? json_decode($apiResponse, true) : null;

    $dati = $result['data'] ?? [];
    $meta = $result['meta'] ?? [];
    $meta['limit'] = $actualLimit;

    $hasNextPage = false;
    if (count($dati) > $actualLimit) {
        $hasNextPage = true;
        array_pop($dati);
    }

    ob_start();
    require __DIR__ . '/../src/views/home.php';
    $html = ob_get_clean();

    $response->getBody()->write($html);
    return $response->withHeader('Content-Type', 'text/html');
});

// ==========================================
// ROTTE DI AUTENTICAZIONE
// ==========================================
$app->get('/login', function (Request $request, Response $response) {
    ob_start();
    require __DIR__ . '/../src/views/login.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response->withHeader('Content-Type', 'text/html');
});

$app->post('/login', function (Request $request, Response $response) use ($apiBaseUrl) {
    $data = $request->getParsedBody();
    
    // Preparazione della richiesta POST per l'API
    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => 'Content-Type: application/json',
            'content' => json_encode(['username' => $data['username'], 'password' => $data['password']])
        ]
    ];
    $context  = stream_context_create($options);
    $apiResponse = @file_get_contents($apiBaseUrl . '/login', false, $context);
    $result = $apiResponse ? json_decode($apiResponse, true) : null;

    if ($result && $result['status'] === 'success') {
        $_SESSION['is_admin'] = true;
        $_SESSION['user'] = $result['user']['username'];
        return $response->withHeader('Location', '/admin')->withStatus(302);
    } else {
        return $response->withHeader('Location', '/login?error=1')->withStatus(302);
    }
});

$app->get('/logout', function (Request $request, Response $response) {
    session_destroy();
    return $response->withHeader('Location', '/')->withStatus(302);
});

// ==========================================
// ROTTE ADMIN
// ==========================================
$app->get('/admin', function (Request $request, Response $response) use ($apiBaseUrl) {
    // Controllo sicurezza
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
        return $response->withHeader('Location', '/login')->withStatus(302);
    }

    // 1. Scarichiamo i Fornitori
    $fornitoriJson = @file_get_contents($apiBaseUrl . '/admin/fornitori');
    $fornitori = $fornitoriJson ? json_decode($fornitoriJson, true)['data'] ?? [] : [];

    // 2. Scarichiamo i Pezzi
    $pezziJson = @file_get_contents($apiBaseUrl . '/admin/pezzi');
    $pezzi = $pezziJson ? json_decode($pezziJson, true)['data'] ?? [] : [];

    // 3. Scarichiamo il Catalogo
    $catalogoJson = @file_get_contents($apiBaseUrl . '/admin/catalogo');
    $catalogo = $catalogoJson ? json_decode($catalogoJson, true)['data'] ?? [] : [];

    ob_start();
    require __DIR__ . '/../src/views/admin.php';
    $html = ob_get_clean();
    
    $response->getBody()->write($html);
    return $response->withHeader('Content-Type', 'text/html');
});

$app->run();