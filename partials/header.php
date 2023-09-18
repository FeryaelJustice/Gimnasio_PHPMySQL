<?php
// SESSION
// Start session
session_start();

// NAVIGATION
// Defined allowed pages (bona practica)
define("allowed", [
    "reservar",
    "reserves",
    "usuaris"
]);
$page = (isset($_GET['page'])) ? $_GET['page'] : 'reservar';
$hostname = "localhost"; // domain if is in production
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
            <a class="navbar-brand" href="./index.php?page=reservar"><strong>Gimnàs</strong></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <?php
                    //$current_dir = str_replace("partials","",str_replace("C:/xampp/htdocs/",$hostname . "/",str_replace("\\","/",dirname(__FILE__))));
                    //echo $current_dir;

                    $links = [
                        "reservar" => "index.php?page=reservar",
                        "reserves" => "index.php?page=reserves",
                        "usuaris" => "index.php?page=usuaris"
                    ];

                    $currentFileName = $_SERVER['SCRIPT_NAME'];
                    if (end(explode("/", $currentFileName)) != "index.php") {
                        $links = [
                            "reservar" => "../../../index.php?page=reservar",
                            "reserves" => "../../../index.php?page=reserves",
                            "usuaris" => "../../../index.php?page=usuaris"
                        ];
                    }

                    /*
                    $links = [
                        "reservar" => $current_dir . "index.php?page=reservar",
                        "reserves" => $current_dir . "index.php?page=reserves",
                        "usuaris" => $current_dir . "index.php?page=usuaris"
                    ];
                    */

                    ?>
                    <a class="nav-link<?php echo ($page == "reservar" ? " active\" aria-current=\"page" : "") ?>" href="<?= $links["reservar"] ?>">Reservar pista</a>
                    <a class="nav-link<?php echo ($page == "reserves" ? " active\" aria-current=\"page" : "") ?>" href="<?= $links["reserves"] ?>">Veure reserves</a>
                    <a class="nav-link<?php echo ($page == "usuaris" ? " active\"aria-current=\"page" : "") ?>" href="<?= $links["usuaris"] ?>">Usuaris</a>
                </div>
            </div>
        </div>
    </nav>