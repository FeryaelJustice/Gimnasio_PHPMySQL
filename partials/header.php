<?php
// SESSION
// Start session
session_start();
// Session variables
// Veure reserves
$_SESSION["dateFrom"] = "2022-11-22 00:00:00";
$_SESSION["dateTo"] = "2022-11-26 23:59:59";

// COOKIES
// Veure reserves
$dateFromCookie = "dateFrom"; // Name identifier
setcookie($dateFromCookie, "2022-11-22 00:00:00", time() + (86400 * 30), "/"); // Expiration: 86400 = 1 day * 30 = 1 month
$dateToCookie = "dateTo"; // Name identifier
setcookie($dateToCookie, "2022-11-26 23:59:59", time() + (86400 * 30), "/"); // Expiration 86400 = 1 day * 30 = 1 month

// NAVIGATION
// Defined allowed pages (bona practica)
define("allowed", [
    "reservar",
    "reserves",
    "usuaris"
]);
$page = (isset($_GET['page'])) ? $_GET['page'] : 'reservar';
?>

<!-- HEADER -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Fernando Gonzalez Serrano">
    <meta name="description" content="Tasca DWES3 - Gimnàs amb mySQL">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <title>Tasca DWES3 - Gimnàs amb mySQL</title>
</head>

<body>

    <!-- NAVEGACIÓ -->
    <nav class="navbar navbar-dark navbar-expand-lg bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="./index.php?page=reservar">Gimnàs</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <!-- <a class="nav-link disabled">Home</a> -->
                    <a class="nav-link<?php echo ($page == "reservar" ? " active\" aria-current=\"page" : "")?>" href="./index.php?page=reservar">Reservar pista</a>
                    <a class="nav-link<?php echo ($page == "reserves" ? " active\" aria-current=\"page" : "")?>" href="./index.php?page=reserves">Veure reserves</a>
                    <a class="nav-link<?php echo ($page == "usuaris" ? " active\"aria-current=\"page" : "")?>" href="./index.php?page=usuaris">Usuaris</a>
                </div>
            </div>
        </div>
    </nav>