<?php
namespace App\Controllers;

use PDO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController {

    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function login(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
    
        // LOGICA DI EMERGENZA: Entra se user è admin e password è admin123
        if ($username === 'admin' && $password === 'admin123') {
            $payload = [
                'status' => 'success',
                'user' => ['username' => 'admin']
            ];
            $response->getBody()->write(json_encode($payload));
            return $response->withStatus(200);
        }
    
        // Se non è l'admin di emergenza, procedi col DB (opzionale)
        $payload = ['status' => 'error', 'message' => 'Credenziali errate'];
        $response->getBody()->write(json_encode($payload));
        return $response->withStatus(401);
    }
}