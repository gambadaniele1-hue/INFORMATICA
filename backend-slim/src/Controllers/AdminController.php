<?php
namespace App\Controllers;

use PDO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminController {

    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Metodo helper per inviare risposte JSON pulite
    private function respond(Response $response, $data, int $status = 200) {
        $response->getBody()->write(json_encode($data));
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }

    // ==========================================
    // FORNITORI
    // ==========================================
    public function getFornitori(Request $request, Response $response) {
        $stmt = $this->db->query("SELECT * FROM Fornitori");
        return $this->respond($response, ['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }

    public function createFornitore(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $stmt = $this->db->prepare("INSERT INTO Fornitori (fid, fnome, indirizzo) VALUES (:fid, :fnome, :indirizzo)");
        $stmt->execute([
            'fid' => $data['fid'],
            'fnome' => $data['fnome'],
            'indirizzo' => $data['indirizzo'] ?? null
        ]);
        return $this->respond($response, ['status' => 'success', 'message' => 'Fornitore creato'], 201);
    }

    public function updateFornitore(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        $stmt = $this->db->prepare("UPDATE Fornitori SET fnome = :fnome, indirizzo = :indirizzo WHERE fid = :fid");
        $stmt->execute([
            'fnome' => $data['fnome'],
            'indirizzo' => $data['indirizzo'] ?? null,
            'fid' => $args['fid']
        ]);
        return $this->respond($response, ['status' => 'success', 'message' => 'Fornitore aggiornato']);
    }

    public function deleteFornitore(Request $request, Response $response, array $args) {
        $stmt = $this->db->prepare("DELETE FROM Fornitori WHERE fid = :fid");
        $stmt->execute(['fid' => $args['fid']]);
        return $this->respond($response, ['status' => 'success', 'message' => 'Fornitore eliminato']);
    }

    // ==========================================
    // PEZZI
    // ==========================================
    public function getPezzi(Request $request, Response $response) {
        $stmt = $this->db->query("SELECT * FROM Pezzi");
        return $this->respond($response, ['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }

    public function createPezzo(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $stmt = $this->db->prepare("INSERT INTO Pezzi (pid, pnome, colore) VALUES (:pid, :pnome, :colore)");
        $stmt->execute([
            'pid' => $data['pid'],
            'pnome' => $data['pnome'],
            'colore' => $data['colore'] ?? null
        ]);
        return $this->respond($response, ['status' => 'success', 'message' => 'Pezzo creato'], 201);
    }

    public function updatePezzo(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        $stmt = $this->db->prepare("UPDATE Pezzi SET pnome = :pnome, colore = :colore WHERE pid = :pid");
        $stmt->execute([
            'pnome' => $data['pnome'],
            'colore' => $data['colore'] ?? null,
            'pid' => $args['pid']
        ]);
        return $this->respond($response, ['status' => 'success', 'message' => 'Pezzo aggiornato']);
    }

    public function deletePezzo(Request $request, Response $response, array $args) {
        $stmt = $this->db->prepare("DELETE FROM Pezzi WHERE pid = :pid");
        $stmt->execute(['pid' => $args['pid']]);
        return $this->respond($response, ['status' => 'success', 'message' => 'Pezzo eliminato']);
    }

    // ==========================================
    // CATALOGO (Associazioni Fornitore-Pezzo)
    // ==========================================
    public function getCatalogo(Request $request, Response $response) {
        $stmt = $this->db->query("SELECT * FROM Catalogo");
        return $this->respond($response, ['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }

    public function createCatalogo(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $stmt = $this->db->prepare("INSERT INTO Catalogo (fid, pid, costo) VALUES (:fid, :pid, :costo)");
        $stmt->execute([
            'fid' => $data['fid'],
            'pid' => $data['pid'],
            'costo' => $data['costo']
        ]);
        return $this->respond($response, ['status' => 'success', 'message' => 'Voce catalogo creata'], 201);
    }

    public function updateCatalogo(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        $stmt = $this->db->prepare("UPDATE Catalogo SET costo = :costo WHERE fid = :fid AND pid = :pid");
        $stmt->execute([
            'costo' => $data['costo'],
            'fid' => $args['fid'],
            'pid' => $args['pid']
        ]);
        return $this->respond($response, ['status' => 'success', 'message' => 'Costo aggiornato nel catalogo']);
    }

    public function deleteCatalogo(Request $request, Response $response, array $args) {
        $stmt = $this->db->prepare("DELETE FROM Catalogo WHERE fid = :fid AND pid = :pid");
        $stmt->execute([
            'fid' => $args['fid'],
            'pid' => $args['pid']
        ]);
        return $this->respond($response, ['status' => 'success', 'message' => 'Voce rimossa dal catalogo']);
    }
}