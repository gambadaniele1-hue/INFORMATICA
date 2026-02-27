<?php
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response) {
    return $response->withHeader('Location', '/1')->withStatus(302);
});

$app->get('/{id}', function (Request $request, Response $response, $args) {
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

    $baseUrl = 'https://dev.eu-01.alpinenode.it/SLIM_API/public';
    $apiUrl = $baseUrl . '/' . $id;

    $queryParams = $request->getQueryParams();
    $apiParams = $queryParams;

    // --- IL TRUCCO DEL +1 ---
    // Leggiamo il limite richiesto (se non c'è, è 10 di default)
    $actualLimit = (int)($queryParams['limit'] ?? 10);
    // Chiediamo all'API un elemento in più!
    $apiParams['limit'] = $actualLimit + 1; 

    if (!empty($apiParams)) {
        $apiUrl .= '?' . http_build_query($apiParams);
    }

    $apiResponse = @file_get_contents($apiUrl);
    $result = $apiResponse ? json_decode($apiResponse, true) : null;

    $dati = $result['data'] ?? [];
    $meta = $result['meta'] ?? [];
    
    // Ripristiniamo il limite corretto nei meta per non confondere la grafica
    $meta['limit'] = $actualLimit;

    // --- CONTROLLIAMO SE C'E' LA PAGINA SUCCESSIVA ---
    $hasNextPage = false;
    if (count($dati) > $actualLimit) {
        $hasNextPage = true; // Ne abbiamo ricevuti più del limite, quindi c'è un'altra pagina!
        array_pop($dati);    // Rimuoviamo l'ultimo elemento "spia" per non mostrarlo in tabella
    }

    ob_start();
    require __DIR__ . '/../src/views/home.php';
    $html = ob_get_clean();

    $response->getBody()->write($html);
    return $response->withHeader('Content-Type', 'text/html');
});

$app->run();