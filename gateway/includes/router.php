<?php

require_once './includes/Database.php';
session_start();

// Méthode de traitement pour la mise à jour de l'adresse IP publique
function updateIpPublic()
{
    // Vérifier si la méthode de la requête est POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Vérifier si la clé "ipaddress" est définie dans les données du formulaire
        if (isset($_POST['ipaddress'])) {
            // Récupérer et traiter l'adresse IP envoyée
            $ipAddress = $_POST['ipaddress'];
            $currentDate = new DateTimeImmutable();
            // Formater la date en "dd/mm/yyyy"
            $formattedDate = $currentDate->format('d/m/Y');

            // Créer une instance de la classe Database avec vos informations de connexion
            $database = new Database();
            // Vérifier si l'adresse IP existe déjà dans la base de données
            $existingIp = $database->select('SELECT ip_public FROM noippublic WHERE ip_public = ?', [$ipAddress]);
            if (empty($existingIp)) {
                // Si l'adresse IP n'existe pas, l'insérer dans la base de données
                $database->insert("INSERT INTO noippublic (date_update, ip_public) VALUES ('" . $formattedDate . "', '" . $ipAddress . "')");
                $database->insert("INSERT INTO countupdate (action, date_update) VALUES ('POST - /ip-public-update (new ip)', '" . $formattedDate . "')");
                echo "Nouvelle adresse IP insérée avec succès : $ipAddress";
            } else {
                $database->insert("INSERT INTO countupdate (action, date_update) VALUES ('POST - /ip-public-update (not changed ip)', '" . $formattedDate . "')");
                echo "L'adresse IP existe déjà dans la base de données : $ipAddress";
            }
        } else {
            // Si la clé "ipaddress" n'est pas définie, afficher une erreur
            http_response_code(400); // Bad Request
            echo "Erreur : L'adresse IP n'a pas été spécifiée.";
        }
    } else {
        // Si la méthode de la requête n'est pas POST, afficher une erreur
        http_response_code(405); // Method Not Allowed
        echo "Erreur : Méthode non autorisée. Seules les requêtes POST sont acceptées.";
    }
}

// Méthode de traitement pour la mise à jour des informations server
function updateInformationServer()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['cputmp']) && isset($_POST['ramfree']) && isset($_POST['ramused']) && isset($_POST['diskused']) && isset($_POST['disktotal'])) {
            $currentDate = new DateTimeImmutable();
            // Formater la date en "dd/mm/yyyy"
            $formattedDate = $currentDate->format('d/m/Y H:i:s');

            // Créer une instance de la classe Database avec vos informations de connexion
            $database = new Database();

            $database->insert("INSERT INTO updateinfoserver (cputmp, ramfree, ramused, diskused, disktotal, dateupdate) VALUES (".$_POST['cputmp'].", ".($_POST['ramfree']/(1024*1024)).", ".($_POST['ramused']/(1024*1024)).", ".($_POST['diskused']/(1024*1024)).", ".$_POST['disktotal'].", '".$formattedDate."')");
            echo "Information serveur mise à jour";
        }
    }
}

function nextLogin()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['auth0']) && isset($_POST['auth1']) && isset($_POST['auth2']) && isset($_POST['auth3'])) {
            $database = new Database();

            $nextAuth = $database->select("SELECT CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END AS pass_exists FROM pass WHERE auth1 = " . $_POST['auth0'] . " AND auth2 = " . $_POST['auth1'] . " AND auth3 = " . $_POST['auth2'] . " AND auth4 = " . $_POST['auth3']);
            if (!empty($nextAuth)) {
                $flag = $nextAuth[0]["pass_exists"];
                if ($flag == 1) {
                    header("Location: https://apimaster.flixmail.fr/infoip");
                    exit;
                }
            } else {
                header("Location: https://apimaster.flixmail.fr/");
                exit;
            }
        }
    }
}

// dashboard pour afficher les informations de l'ip et du serveur
function dashboardIpManager()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        include('./pages/login.html');
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['dashlogin']) && isset($_POST['dashpassword'])) {
            $database = new Database();

            $password = hash('ripemd320', $_POST['dashpassword']);
            $existingUser = $database->select("SELECT id FROM user WHERE login = '" . $_POST['dashlogin'] . "' AND  password = '" . $password . "'");
            if (!empty($existingUser)) {
                $_SESSION["user"] = $existingUser[0]["id"];
                $_SESSION["connection"] = true;
                //include('./pages/dashboard.php');
                header("Location: https://apimaster.flixmail.fr/dashboard-noip");
                exit;
            } else {
                echo "Mot de passe ou login incorrect";
            }
        } else {
            // Si la clé "ipaddress" n'est pas définie, afficher une erreur
            http_response_code(400); // Bad Request
            echo "Erreur : L'adresse IP n'a pas été spécifiée.";
        }
    }
}

//méthode pour checker si l'utilisateur est connecter
function dashboardInformation()
{
    if (session_status() != PHP_SESSION_NONE) {
        if (isset($_SESSION["user"]) && $_SESSION["connection"] !== null) {
            include('./pages/dashboard.php');
        } else {
            header("Location: https://apimaster.flixmail.fr/infoip");
            exit;
        }
    } else {
        header("Location: https://apimaster.flixmail.fr/infoip");
        exit;
    }
}

// deconnexion de l'utilisateur
function logout()
{
    // Détruire toutes les données de session
    $_SESSION = array();
    // Détruire la session
    session_destroy();
    header("Location: https://apimaster.flixmail.fr/");
    exit;
}
