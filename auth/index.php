<?php
    require_once '../data/config.php';

    session_start();

    if (isset($_SESSION['user_login'])) {
        header('location: ../');
    };

    $page = $_GET['page'];

    if ($page == "login") {
        //
    } else if ($page == "register") {
        //
    } else if ($page == "logout") {
        //
    } else {
        header('location: ../');
    };
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discutons !</title>

    <link rel="stylesheet" href="../src/css/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- SEE HOME AND LOG IN MENU -->
    <div class="navbar">
        <a href="./">Accueil</a>

        <?php if (isset($_SESSION['user_login'])) { ?>
        <a href="./user/?id=" class="login">Profil</a>
        <?php } else { ?>
        <a href="./auth/?page=login" class="login">Connexion</a>
        <?php }; ?>
    </div>
</body>
</html>