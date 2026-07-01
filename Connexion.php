<?php
require_once "connect.php";
session_start();
if(isset($_POST['envoyer']))
{
    
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Vérifier CLIENT
    $req1 = $pdo->prepare("SELECT * FROM client WHERE Adresse=?");
    $req1->execute([$login]);
    $client = $req1->fetch();

    if($client && password_verify($password , $client['mot_de_passeCl']))
    {
        $_SESSION["codeCli"] = $client['idCli'];
        header("Location:PageClients.php");
        exit();
    }

    // Vérifier COMMERCIAL
    $req2 = $pdo->prepare("SELECT * FROM commerciaux WHERE Adresse=?");
    $req2->execute([$login]);
    $commercial = $req2->fetch();

    if($commercial && password_verify($password , $commercial["mot_de_passeC"]))
    {
        $_SESSION["codeCom"] = $commercial['idCom'];
        header("Location: PageCommercant.php?");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Agence</title>
<link rel="stylesheet" href="style.css"/>
</head>

<body>

<div class="container">

    <form method="POST" autocomplete="off">

        <!-- piège navigateur (anti autofill) -->
        <input type="text" name="fake_user" style="display:none" autocomplete="username">
        <input type="password" name="fake_pass" style="display:none" autocomplete="new-password">

        <h2>Connexion</h2>
		<p>🤏Juste pour une dernière vérification</p>

        👇<input type="text" name="login" placeholder="Login" required autocomplete="off">
        
        <br><br>

        👇<input type="password" name="password" placeholder="Password" required autocomplete="off">

        <br><br>

        <button type="submit" name="envoyer">Envoyer</button>
        <a href="Prépare.php">Retour</a>

    </form>

</body>

</body>
</html>