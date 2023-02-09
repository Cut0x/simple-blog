<?php
    require_once '../data/config.php';

    session_start();

    $page = $_GET['page'];

    if ($page == "login") {
        if (isset($_SESSION['user_login'])) {
            header('location: ../');
        };

        $identifiant = strip_tags($_REQUEST["btn_login_identifiant"]);
        $password = strip_tags($_REQUEST["btn_login_password"]);
        
        try {
            $select_stmt = $db -> prepare("SELECT * FROM tbl_users WHERE identifiant=:uid");

            $select_stmt -> execute(
                array(
                    ':uid' => $identifiant,
                )
            );

            $row = $select_stmt -> fetch(PDO::FETCH_ASSOC);
            
            if ($select_stmt->rowCount() > 0) {
                if ($identifiant == $row["identifiant"]) {
                    if (password_verify($password, $row["password"])) {
                        $_SESSION["user_login"] = $row["user_id"];
                        
                        header("location: ../");
                    } else {
                        $errorMsg[] = "Mauvais mot de passe !";
                    }
                } else {
                    $errorMsg[] = "Identifiant introuvable !";
                }
            } else {
                $errorMsg[] = "Aucun compte n'existe sous cet identifiant !";
            }
        } catch(PDOException $e) {
            $e -> getMessage();
        };
    } else if ($page == "register") {
        if (isset($_SESSION['user_login'])) {
            header('location: ../');
        };

        if (isset($_REQUEST['btn_register'])) {
            $username = strip_tags($_REQUEST["btn_register_username"]);
            $identifiant = strip_tags($_REQUEST["btn_register_identifiant"]);
            $password = strip_tags($_REQUEST["btn_register_password"]);
            
            try {	
                $select_stmt = $db -> prepare("SELECT identifiant FROM tbl_users WHERE identifiant=:uid");
                
                $select_stmt -> execute(
                    array(
                        ':uid' => $identifiant
                    )
                );

                $check = $db -> query('SELECT identifiant FROM tbl_users WHERE identifiant="'.$identifiant.'"');

                $row = $select_stmt -> fetch(PDO::FETCH_ASSOC);
                    
                try {
                    if ($check -> rowCount() !== 1) {
                        $new_password = password_hash($password, PASSWORD_DEFAULT);
                        
                        $insert_stmt = $db -> prepare("INSERT INTO tbl_users (username, identifiant, password) VALUES (:uname,:uid,:upassword)");				
                        
                        if ($insert_stmt -> execute(
                            array(
                                ':uname' => $username,
                                ':uid' => $identifiant,
                                ':upassword' => $new_password
                            ))) {
                            header('location: ./?page=login&result=succes');
                        };
                    } else {
                        $errorMsg[] = "Identifiant déjà utilisé !";
                    };
                } catch(PDOException $e) {
                    $errorMsg[] = "Identifiant déjà utilisé !";
                }
            } catch(PDOException $e) {
                $e -> getMessage();
            };
        };
    } else if ($page == "logout") {
        if (!isset($_SESSION['user_login'])) {
            header('location: ./?page=login');
        };
        
        header("location: ./?page=login&result=disconnected");
        
        session_destroy();
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
    <title>Authentification !</title>

    <link rel="stylesheet" href="../src/css/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- SEE HOME AND LOG IN MENU -->
    <div class="navbar">
        <a href="./">Accueil</a>

        <?php if (isset($_SESSION['user_login'])) { ?>
        <a href="./user/?id=">Profil</a>
        <?php } else { ?>
        <a href="./auth/?page=login" class="actif">Connexion</a>
        <?php }; ?>
    </div>

    <div style="margin: 150px;"></div>

    <!-- FORMULAIRES -->
    <?php if ($page == "login") { ?>
    <div class="page">
        <form method="post">
            <h1>
                Formulaire de connexion
            </h1>

            <?php if(isset($errorMsg)) { ?>
		    <?php foreach($errorMsg as $error) { ?>
    	    <div style="margin: 25px;"></div>

            <div class="message_box">
                <div class="warn_message">
                    <span style="color: red;"><i class="bi bi-exclamation-circle-fill"></i></span> <?= $error; ?>
                </div>
            </div>

    	    <div style="margin: 25px;"></div>
            <?php }; ?>
		    <?php }; ?>

            <?php if (isset($_GET['result']) && $_GET['result'] == "succes") { ?>
    	    <div style="margin: 25px;"></div>

            <div class="message_box">
                <div class="succes_message">
                    <span style="color: darkgreen;"><i class="bi bi-check-circle-fill"></i></span> Compte enregistré avec succès !
                </div>
            </div>

    	    <div style="margin: 25px;"></div>
            <?php }; ?>

            <?php if (isset($_GET['result']) && $_GET['result'] == "disconnected") { ?>
    	    <div style="margin: 25px;"></div>

            <div class="message_box">
                <div class="succes_message">
                    <span style="color: darkgreen;"><i class="bi bi-check-circle-fill"></i></span> Vous avez été déconnecté avec succès !
                </div>
            </div>

    	    <div style="margin: 25px;"></div>
            <?php }; ?>

            <div class="cont">
                <input type="text" name="btn_login_identifiant" id="login_identifiant" placeholder="Votre identifiant">
            </div>

            <div style="margin: 30px;"></div>

            <div class="cont">
                <input type="password" name="btn_login_password" id="login_password" placeholder="Votre mot de passe">
            </div>

            <div style="margin: 30px;"></div>

            <input type="submit" name="btn_login" value="Connexion">

            <p>Pas de compte ? <a href="./?page=register">Enregistre toi !</a></p>
        </form>
    </div>
    <?php } else if ($page == "register") { ?>
    <div class="page">
        <form method="post">
            <h1>
                Formulaire d'inscription
            </h1>

            <?php if(isset($errorMsg)) { ?>
		    <?php foreach($errorMsg as $error) { ?>
    	    <div style="margin: 25px;"></div>

            <div class="message_box">
                <div class="warn_message">
                    <span style="color: red;"><i class="bi bi-exclamation-circle-fill"></i></span> <?= $error; ?>
                </div>
            </div>

    	    <div style="margin: 25px;"></div>
            <?php }; ?>
		    <?php }; ?>

            <div class="cont">
                <input type="text" name="btn_register_username" id="register_username" placeholder="Votre pseudo">
            </div>

            <div style="margin: 30px;"></div>

            <div class="cont">
                <input type="text" name="btn_register_identifiant" id="register_identifiant" placeholder="Votre identifiant">
            </div>

            <div style="margin: 30px;"></div>

            <div class="cont">
                <input type="password" name="btn_register_password" id="register_password" placeholder="Votre mot de passe">
            </div>

            <div style="margin: 30px;"></div>

            <input type="submit" name="btn_register" value="Enregistrer">

            <p>Déjà un compte ? <a href="./?page=login">Connecte toi !</a></p>
        </form>
    </div>
    <?php }; ?>
</body>
</html>