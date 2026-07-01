<?php
   session_start();
  require("connect.php");
  if(!isset($_SESSION["codeCom"]))
  {
      header("Location: Prépare.php");
      exit();
  }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Agence ASHY</title>
    <style>
    body 
    {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #74ebd5, #ACB6E5);
    height: 100vh;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
      }

        form 
        {
            background: white;
            padding: 30px;
            width: 350px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-align: center;
        }

        h1
        {
            margin-bottom: 20px;
            color: #333;
        }

        .option 
        {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin: 12px 0;
            font-size: 16px;
        }

        .option input 
        {
            margin-right: 10px;
            transform: scale(1.2);
        }

        input[type="submit"] 
        {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            background: #007BFF;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        input[type="submit"]:hover
        {
            background: #0056b3;
        }

        .option:hover
        {
            background: #f1f1f1;
            padding: 8px;
            border-radius: 8px;
        }
        </style>
</head>
<body>


<form method="post">
    <h1>Paramètres</h1>

    <label class="option">
        <input type="radio" name="choix" value="DECO" required>
        Déconnexion
    </label>
    <label class="option">
        <input type="radio" name="choix" value="SUP">
        Supprimer le compte
    </label>
    <label class="option">
        <input type="radio" name="choix" value="MOD">
        Modifier le compte
    </label>
    <label class="option">
        <input type="radio" name="choix" value="INF">
        Infos du compte
    </label>

    <input type="submit" name="executer" value="OK">
    <a href="PageCommercant.php">Retour</a>
</form>

<?php 
 
 if(isset($_POST["executer"]))
 {
     $choix = $_POST["choix"];
     $codeCom = $_SESSION["codeCom"];
 
     if($choix == "DECO")
     {

         header("Location: Prépare.php");
         exit();
     }
 
     elseif($choix == "SUP")
     {
         $pdo->beginTransaction();
 
         $req = $pdo->prepare("DELETE FROM photocommerciaux WHERE codeCom=?");
         $req->execute([$codeCom]);
 
         $req = $pdo->prepare("DELETE FROM commerciaux WHERE idCom=?");
         $req->execute([$codeCom]);
 
         $pdo->commit();
         session_destroy();
         header("Location: Prépare.php");
         exit();
     }
 
     elseif($choix == "MOD")
     {
         header("Location: ModifCom.php");
         exit();
     }
     elseif($choix == "INF")
     {
         header("Location: InfosCom.php");
         exit();
     }
 }
?>