<?php

require_once './includes/Database.php';
session_start();

function requestApiService($uri)
{
    $database = new Database();
    $serverIp = $database->select("SELECT ip_public FROM noippublic ORDER BY id DESC LIMIT 1");
    // URL du serveur cible
    $targetUrl = $serverIp[0]['ip_public'] . ':62580' . $uri;

    // Vérifier la méthode de la requête
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method === 'GET') {
        header("Location: https://" . $targetUrl);
        exit();
    } else {

        // Récupération des données POST
        $post_data = http_build_query($_POST);
        
        // Redirection vers la nouvelle URL avec les données POST
        header('Location: https://' . $targetUrl, true, 307);
        header('Content-Length: ' . strlen($post_data));
        header('Content-Type: application/x-www-form-urlencoded');
        echo $post_data;
        exit;
    }
}
