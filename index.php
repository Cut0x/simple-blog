<?php
    require_once './data/config.php';

    session_start();

    if (isset($_SESSION['user_login'])) {
        $id = $_SESSION['user_login'];
    
        $select_stmt = $db->prepare("SELECT * FROM tbl_users WHERE user_id=:uid");
        $select_stmt->execute(
            array(
                ":uid" => $id
            )
        );
        
        $row = $select_stmt -> fetch(
            PDO::FETCH_ASSOC
        );
    };

    $publications = $db -> query('SELECT * FROM tbl_publications ORDER BY date_publication DESC');

    $publications_count = $publications -> rowCount();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toost - Discutons !</title>

    <link rel="stylesheet" href="./src/css/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- SEE HOME AND LOG IN MENU -->
    <div class="navbar">
        <a href="./" class="actif">Accueil</a>

        <?php if (isset($_SESSION['user_login'])) { ?>
        <a href="./user/?id=<?= $_SESSION['user_login']; ?>">Profil</a>
        <?php } else { ?>
        <a href="./auth/?page=login">Connexion</a>
        <?php }; ?>
    </div>

    <!-- CHECK AND MESSAGE FOR USER LOGIN -->
    <?php if (!isset($_SESSION['user_login'])) { ?>
    <div class="message_box">
        <div class="warn_message">
            <h1>
                <span style="color: red;"><i class="bi bi-exclamation-circle-fill"></i></span> Pour réagir aux publications, vous devez vous <a href="./auth/?page=login">connecter</a> !
            </h1>
        </div>
    </div>
    <?php }; ?>

    <div style="margin: 25px;"></div>

    <!-- CERTIFICATION REQUEST -->
    <div class="button">
        <?php if (isset($_SESSION['user_login']) && $row['certified'] == 0) { ?>
        <a href="#">Demander la certification !</a>
        <?php } else if (isset($_SESSION['user_login']) && $row['certified'] == 1) { ?>
        <p>
            Vous êtes certifié ! <span style="color: lightblue;"><i class="bi bi-patch-check-fill"></i></span>
        </p>
        <?php }; ?>
    </div>

    <div style="margin: 25px;"></div>

    <!-- SEE PUBLICATION -->
    <div class="container">
        <?php if ($publications_count == 0) { ?>
        <div class="message_box">
            <div class="warn_message">
                <h1>
                    <span style="color: red;"><i class="bi bi-exclamation-circle-fill"></i></span> Il n'y a actuellement aucune publication ! <?php if (isset($_SESSION['user_login'])) { echo "Sois le premier à en faire une !"; }; ?>
                </h1>
            </div>
        </div>
        <?php }; ?>

        <?php while($p = $publications -> fetch()) { ?>
            <div class="obj">
                <p class="author">
                    <a href="./user/?id=<?= $p['author_id']; ?>">User</a> <?php if ($p_author['certified'] == 1) { echo '<a href="#" class="certif"><i class="bi bi-patch-check-fill"></i></a>'; }; ?>
                </p>
                <p class="content">
                    <?= nl2br($p['content']); ?>
                </p>
            </div>
        <?php }; ?>
    </div>
</body>
</html>