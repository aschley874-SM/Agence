<?php
session_start();

if (isset($_POST['choix'])) 
{

    $_SESSION['choix'] = $_POST['choix'];

    if ($_POST['choix'] == "commercant")
     {
        header("Location: Commercant.php");
        exit();
    }

    if ($_POST['choix'] == "client") 
    {
        header("Location: client.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Agence</title>
<link rel="stylesheet" href="style1.css"/>
</head>
<body>

<div class="container">

<form method="POST">

<h2>Choissez votre type d'inscription✍️:</h2>

<label>
    <input type="radio" name="choix" value="commercant">
    Commerçant(e)
</label>

<label>
    <input type="radio" name="choix" value="client">
    Client(e)
</label>

<br>

<button type="submit">Continuer</button>

    <a href="accueil.php">Retour</a>

</form>

</div>
</body>
</html>