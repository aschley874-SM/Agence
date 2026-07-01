<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
          body 
        {
            background-color:rgb(185, 194, 186);
            text-align: center;
            margin-top: 100px;
        }

        h1
        {
            color: green;
        }
        form
    {
        width:350px;
        margin-left:35%;
        background:white;
        padding:20px;
        border-radius:8px;
    }
    input[type="text"],input[type="password"] 
    {
        width:100%;
        padding:5px;
    }
    label 
    {
      font-weight:bold;
    }
    button:hover
    {
       background:green;
    }
    button 
    {
      margin-top:5%;
    }
    h3 
    {
      color:red;
      background:white;
      width:40%;
      margin-left:32%;
      border-radius:5px;
    }
</style>
</head>
<body>

<form method="post">
<h2>Authentification:</h2>

<label>Login :</label>
<input type="text" name="login" required>

<label>Mot de passe :</label>
<input type="password" name="password" required>

<button type="submit" name="connexion">Se connecter</button>
</form>

<?php  

$connexion = new PDO("mysql:host=localhost;dbname=agence","root","");

if(isset($_POST["connexion"]))
{
    $log = $_POST["login"];
    $pass = $_POST["password"];

      $req= $connexion->prepare(" SELECT * FROM commerciaux WHERE Adresse=? AND mot_de_passeC=?");
       $req= execute([$log,$pass]);
       $com= $req->fetch();    

      $req2= $connexion->prepare(" SELECT * FROM client WHERE Adresse=? AND mot_de_passeCl=?");
      $req2 = execute([$log,$pass]);
      $cli= $req2->fetch();

    if($com)
    {
      $_SESSION["codeCom"] = $com['idCom']; // session commerçant
      header("Location: PageCommercant.php");
      exit();
    }
    elseif($cli)
    {
      $_SESSION["codeCli"] = $com['idCli']; // session commerçant
      header("Location: PageClients.php");
      exit();
    }
    else
    {
        echo "<h3>Erreur: login ou mot de passe incorrect</h3>";
    }
}
?>

</body>
</html>