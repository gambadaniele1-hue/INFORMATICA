# 📦 Fornitori DB - Gestione Catalogo & API REST

Questo progetto è un'applicazione web completa per la gestione di Fornitori, Pezzi e del relativo Catalogo. È sviluppata in PHP utilizzando [Slim Framework 4] ed è divisa in due componenti separati per mantenere un'architettura pulita:

1. **Backend (API REST):** Si interfaccia con il database MySQL e fornisce i dati in formato JSON. Gira sulla porta `8010`.
2. **Frontend (Dashboard UI):** Consuma l'API e mostra l'interfaccia grafica all'utente, inclusa un'area ad accesso riservato (Admin). Gira sulla porta `8000`.

---

## 🛠 Prerequisiti

Per far girare questo progetto sul tuo ambiente locale, devi avere installati:
* **PHP** (versione 7.4 o superiore)
* **MySQL** o MariaDB (es. tramite XAMPP, MAMP, Laragon)
* **Composer** (per installare le dipendenze di Slim Framework)

---

## 🚀 Guida all'Installazione (Passo a Passo)

### 1. Configurazione del Database (MySQL)
Prima di avviare il codice, dobbiamo preparare i dati.

1. Avvia il tuo server MySQL (es. accendi il modulo MySQL dal pannello di XAMPP).
2. Apri **phpMyAdmin** (solitamente all'indirizzo `http://localhost/phpmyadmin`).
3. Crea un nuovo database e chiamalo esattamente **`fornitori_db`**.
4. Importa il file **`fornitori_db.sql`** (incluso in questa repository) all'interno del database appena creato. Questo creerà le tabelle `Fornitori`, `Pezzi` e `Catalogo` e inserirà i dati di base.
5. **Crea l'utente Amministratore:** Vai nella scheda "SQL" di phpMyAdmin, assicurati di aver selezionato `fornitori_db`, ed esegui questa query per creare la tabella `Users` e l'account di accesso:

   ```sql
   CREATE TABLE IF NOT EXISTS `Users` (
     `id` int NOT NULL AUTO_INCREMENT,
     `username` varchar(50) NOT NULL,
     `password` varchar(255) NOT NULL,
     PRIMARY KEY (`id`)
   );

   -- Inserisce l'utente 'admin' con password 'password123' (criptata in BCRYPT)
   INSERT INTO `Users` (`username`, `password`) VALUES 
   ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
2. Configurazione e Avvio del Backend (API)
Il backend deve essere configurato per connettersi al database appena creato.

Apri il tuo terminale o prompt dei comandi.

Entra nella cartella del backend:

Bash
cd backend-slim
Installa le dipendenze PHP tramite Composer:

Bash
composer install
Configura le credenziali del DB: Apri il file src/Core/config.php (o dove tieni i parametri di configurazione nel backend) e assicurati che i dati corrispondano al tuo MySQL locale. Esempio per XAMPP:

PHP
define('DB_HOST', '127.0.0.1'); // o 'localhost'
define('DB_NAME', 'fornitori_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // Lascia vuoto se non hai una password in locale
Avvia il server integrato di PHP per l'API sulla porta 8010:

Bash
php -S localhost:8010 -t public
⚠️ Attenzione: Lascia questo terminale aperto. Se lo chiudi, l'API si spegnerà.

3. Configurazione e Avvio del Frontend (UI)
Ora dobbiamo avviare l'interfaccia grafica che comunicherà con l'API.

Apri un NUOVO terminale (affiancandolo a quello del backend che sta già girando).

Entra nella cartella del frontend:

Bash
cd frontend-slim
Installa le dipendenze:

Bash
composer install
Avvia il server integrato di PHP per il Frontend sulla porta 8000:

Bash
php -S localhost:8000 -t public
🎮 Come Utilizzare l'Applicazione
Ora che entrambi i server stanno girando in parallelo, apri il tuo browser preferito.

🔍 Area Pubblica (Data Explorer)
Vai all'indirizzo:
👉 http://localhost:8000

Da qui potrai navigare nel menu laterale per eseguire le 10 query SQL preimpostate. L'interfaccia supporta l'applicazione di filtri (come la ricerca per colore o azienda) e gestisce l'impaginazione dei risultati.

⚙️ Area Amministrazione (CRUD)
Per gestire fisicamente i dati nel database, vai all'indirizzo:
👉 http://localhost:8000/login

Usa le seguenti credenziali per accedere:

Username: admin

Password: password123

Una volta dentro, avrai accesso alla Dashboard Admin dove potrai visualizzare in tempo reale i dati completi di:

🏢 Fornitori

⚙️ Pezzi

💰 Catalogo (associazioni Fornitore-Pezzo e relativi costi)

🐛 Risoluzione dei Problemi Frequenti (Troubleshooting)
Errore 500 / "Unknown database 'fornitori_db'": Il backend non trova il database. Assicurati di aver importato correttamente il file SQL in phpMyAdmin e che i parametri in backend-slim/src/Core/config.php siano esatti.

Le pagine del frontend caricano ma non ci sono dati: Verifica che il terminale del backend (porta 8010) sia aperto e stia funzionando. Il frontend ha bisogno del backend per ricevere i dati.

Errore "404 Not Found" nel terminale: Assicurati di aver avviato i server aggiungendo -t public al comando. Il comando esatto deve essere php -S localhost:PORTA -t public eseguito dalla radice della cartella (backend o frontend).

Login fallito ("Credenziali non valide"): Verifica che l'hash BCRYPT nel database non sia stato tagliato o incollato male. Deve corrispondere esattamente alla stringa indicata nel passaggio 1.


***

Questo README guida chiunque dalla "A" alla "Z". Se hai bisogno di aggiungere altre