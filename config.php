<?php
require 'vendor/autoload.php';

// PEM.txt contient les clés client_id et client_secret
define('CLIENT_ID', trim(file_get_contents(dirname(__FILE__) . '/pem/client_id.txt')));
define('CLIENT_SECRET', trim(file_get_contents(dirname(__FILE__) . '/pem/client_secret.txt')));

define('REDIRECT_URI', 'http://localhost/api/callback');

define('TOKEN_URL', 'https://accounts.spotify.com/api/token'); // URL pour récupérer le token d'accès
define('SCOPES', 'user-read-private user-read-email user-top-read'); // Permissions demandées à l'utilisateur (SCOPES)

define('AUTH_URL', 'https://accounts.spotify.com/authorize?response_type=code&client_id=' . CLIENT_ID . '&scope=' . SCOPES . '&redirect_uri=' . REDIRECT_URI); // URL pour l'authentification OAuth2

define('LOGS_FILE', dirname(__FILE__) . '/logs/api_log.json'); // Fichier de logs


define('BASE', 'api_logs'); // Nom de la base de données
define('ADRESSE', 'localhost'); // Adresse du serveur
define('PORT', 3306); // Port du serveur
define('USER', 'root'); // Nom d'utilisateur
define('PASSWD', 'root'); // Mot de passe

// Objet PDO représentant la connexion à la base de données
$db = new PDO
(
    // !! une seule ligne, sans espace !!
    "mysql:host=" . ADRESSE . ";port=" . PORT . ";dbname=" . BASE . ";charset=utf8", USER, PASSWD
);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

define('DB', $db); // Constante pour accéder à la base de données depuis les autres fichiers

?>
