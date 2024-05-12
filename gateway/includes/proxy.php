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
        header("Location: http://" . $targetUrl);
        exit();
    } else {
        header("Location: http://" . $targetUrl);
        exit();
    }
}