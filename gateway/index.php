<?php
require_once './includes/router.php';
require_once './includes/proxy.php';

// Vérifie et traite la requête
function handleRequest($method, $path)
{
    switch ($method) {
        case 'GET':
            // Traiter les requêtes GET
            switch ($path) {
                    // Définir les routes GET ici
                case '/infophp':
                    phpinfo();
                    break;
                case '/infoip':
                    dashboardIpManager();
                    break;
                case '/dashboard-noip':
                    dashboardInformation();
                    break;
                case '/':
                   // include('./pages/index.html');
                   // break;
                   requestApiService('/');
                case '/logout':
                    logout();
                    break;
                default:
                    // Route non trouvée, afficher une erreur 404
                    //http_response_code(404);
                    include('./pages/404.html');
                    break;
            }
            break;

        case 'POST':
            // Traiter les requêtes POST
            switch ($path) {
                    // Définir les routes POST ici
                case '/ip-public-update':
                    updateIpPublic();
                    break;
                case '/server-update':
                    updateInformationServer();
                    break;
                case '/infoip':
                    dashboardIpManager();
                    break;
                    case '/next':
                        nextLogin();
                        break;
                default:
                    // Route non trouvée, afficher une erreur 404
                    http_response_code(404);
                    include('./pages/404.html');
                    break;
            }
            break;

        default:
            // Méthode non autorisée, afficher une erreur 405
            http_response_code(405);
            echo "Méthode non autorisée";
            break;
    }
}

// Obtenir la méthode de la requête HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Obtenir le chemin de la requête HTTP
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Traiter la requête
handleRequest($method, $path);
