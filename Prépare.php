<?php
session_start();
require_once "connect.php";
 
if(isset($_POST['envoyer']))
{
    
    $login = $_POST['login'];
    $password = $_POST['password'];

    // CLIENT
    $req1 = $pdo->prepare("SELECT * FROM client WHERE Adresse=?");
    $req1->execute([$login]);
    $client = $req1->fetch();

    if($client && password_verify($password , $client['mot_de_passeCl']))
    {
        $_SESSION["codeCli"] = $client["idCli"];
        header("Location:PageClients.php");
        exit();
    }

    // COMMERCIAL
    $req2 = $pdo->prepare("SELECT * FROM commerciaux WHERE Adresse=?");
    $req2->execute([$login]);
    $commercial = $req2->fetch();

    if($commercial && password_verify($password , $commercial["mot_de_passeC"]))//ON fait password_verify car le mot de passe est en hash
    {
        $_SESSION["codeCom"] = $commercial['idCom'];
        header("Location:PageCommercant.php?id");
        exit();
    }

    // ERREUR
    $erreur = "❌ Vous n'êtes pas enregistré. Veuillez vous inscrire.";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Agence</title>
<link rel="stylesheet" href="style.css"/>
</head>

<body>

<?php if(isset($erreur)){ ?>
    <p style="color:red;"><?= $erreur ?></p>
<?php } ?>

<div class="container">

    <form method="POST" autocomplete="off">
	<!-- piège navigateur (anti autofill) -->
        <input type="text" name="fake_user" style="display:none" autocomplete="username">
        <input type="password" name="fake_pass" style="display:none" autocomplete="new-password">


        <h2>Connexion</h2>

        <input type="text" name="login" placeholder="Login" required>
        
        <br><br>

        <input type="password" name="password" placeholder="Password" required>

        <br><br>

        <button type="submit" name="envoyer">Envoyer🙌</button>
    </form>

    <br>

    <div class="inscription">
        <a href="Inscription.php">Je n'ai pas de compte😓</a>
    </div>
    <a href="accueil.php">Retour</a>

</div>

</body>
</html>