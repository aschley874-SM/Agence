<?php
   session_start();
  require("connect.php");
  
if(!isset($_SESSION["codeCli"]))
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
        <input type="radio" name="choix" value="INFO">
         Infos du compte
    </label>

    <input type="submit" name="executer" value="OK">
    <a href="PageClients.php">Retour</a>
</form>

<?php 
        if(isset($_POST["executer"]))
        {
            $_SESSION['choix'] = $_POST['choix'];

            if( $_POST['choix']=="DECO")
            {
                header("location:Prépare.php");
                exit();
            }

            elseif($_POST["choix"]=="SUP")
            {
                $req= $pdo->prepare("DELETE  FROM client WHERE idCli=?");
                $req->execute([$_SESSION["codeCli"]]);
                $req2 = $pdo->prepare("DELETE FROM photoclients WHERE codeClient=?");
                $req2->execute([$_SESSION["codeCli"]]);

                //cv detruire la session enpechant son activation
                session_destroy();
                header("Location: Prépare.php");
                exit();
            }

            elseif($_POST["choix"]=="MOD")
            {
                header("Location: ModifCli.php");
                exit();
            }
            
            elseif($_POST["choix"]=="INFO")
            {
                header("Location: InfosCli.php");
                exit();
            }
            else
            {
                echo"Donnez un choix!";
            }
        }
?>