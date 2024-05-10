<?php

require_once './includes/Database.php';
session_start();

$ch;

// Fonction pour rediriger les requêtes GET
function proxyGetRequest($url, $cookies)
{
    // Initialiser la session cURL
    $ch = curl_init();

    // Configuration de cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Suivre les redirections

    // Transférer les cookies reçus
    if (!empty($cookies)) {
        $cookieString = '';
        foreach ($cookies as $name => $value) {
            $cookieString .= $name . '=' . $value . '; ';
        }
        curl_setopt($ch, CURLOPT_COOKIE, $cookieString);
    }

    // Exécution de la requête
    $response = curl_exec($ch);

    // Vérification des erreurs
    if (curl_errno($ch)) {
        // Gestion des erreurs
        $error = curl_error($ch);
        curl_close($ch);
        die("Erreur lors de la requête : $error");
    }

    // Récupérer les nouveaux cookies
    $newCookies = curl_getinfo($ch, CURLINFO_COOKIELIST);
    foreach ($newCookies as $cookie) {
        $parts = explode("\t", $cookie);
        if (count($parts) >= 7) {
            $cookies[$parts[5]] = $parts[6];
        }
    }

    // Fermeture de la session cURL
    curl_close($ch);

    // Retourner la réponse et les nouveaux cookies
    return ['response' => $response, 'cookies' => $cookies];
}

// Fonction pour rediriger les requêtes POST et DELETE
function proxyRequest($url, $data, $method, $cookies)
{
    // Initialiser la session cURL
    $ch = curl_init();

    // Configuration de cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Suivre les redirections

    // Transférer les cookies reçus
    if (!empty($cookies)) {
        $cookieString = '';
        foreach ($cookies as $name => $value) {
            $cookieString .= $name . '=' . $value . '; ';
        }
        curl_setopt($ch, CURLOPT_COOKIE, $cookieString);
    }

    // Exécution de la requête
    $response = curl_exec($ch);

    // Vérification des erreurs
    if (curl_errno($ch)) {
        // Gestion des erreurs
        $error = curl_error($ch);
        curl_close($ch);
        die("Erreur lors de la requête : $error");
    }

    // Récupérer les nouveaux cookies
    $newCookies = curl_getinfo($ch, CURLINFO_COOKIELIST);
    foreach ($newCookies as $cookie) {
        $parts = explode("\t", $cookie);
        if (count($parts) >= 7) {
            $cookies[$parts[5]] = $parts[6];
        }
    }

    // Fermeture de la session cURL
    curl_close($ch);

    // Retourner la réponse et les nouveaux cookies
    return ['response' => $response, 'cookies' => $cookies];
}

function requestApiService($uri)
{
    $database = new Database();
    $serverIp = $database->select("SELECT ip_public FROM noippublic ORDER BY id DESC LIMIT 1");
    // URL du serveur cible
    $targetUrl = $serverIp[0]['ip_public'].':62580'.$uri; 
    // Récupérer les cookies de la requête
    $cookies = [];
    if (!empty($_COOKIE)) {
        foreach ($_COOKIE as $name => $value) {
            $cookies[$name] = $value;
        }
    }
    // Vérifier la méthode de la requête
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method === 'GET') {
        // Si la méthode est GET, utiliser la fonction proxyGetRequest
        $responseAndCookies = proxyGetRequest($targetUrl, $cookies);
    } elseif ($method === 'POST' || $method === 'DELETE') {
        // Si la méthode est POST ou DELETE, utiliser la fonction proxyRequest avec les données du formulaire
        $data = file_get_contents('php://input');
        $responseAndCookies = proxyRequest($targetUrl, $data, $method, $cookies);
    } else {
        // Autres méthodes non prises en charge
        http_response_code(405); // Méthode non autorisée
        exit('Méthode non autorisée');
    }

    // Envoyer les nouveaux cookies dans l'en-tête de réponse
    foreach ($responseAndCookies['cookies'] as $name => $value) {
        setcookie($name, $value);
    }

    // Envoyer les en-têtes de réponse
    // (facultatif : vous pouvez ajouter d'autres en-têtes selon les besoins)
    header('Content-Type: ' . $responseAndCookies['contentType']);

    // Retourner la réponse du serveur cible
    echo $responseAndCookies['response'];
}
