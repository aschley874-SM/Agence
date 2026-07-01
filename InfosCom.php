<?php
    session_start();
    require("Connect.php");
    require("functions.php");
            
  $codeCom = $_SESSION["codeCom"] ?? null;
    if(!isset($_SESSION["codeCom"]))
     {
        header("Location: Prépare.php");
        exit();
    }

    $req = $pdo->prepare("SELECT * FROM commerciaux WHERE idCom= ?");
    $req->execute([$codeCom]);
    $com = $req->fetch();

    $img = $pdo->prepare("SELECT url FROM photocommerciaux WHERE codeCom= ? LIMIT 1");
      $img->execute([$codeCom]);
      $photo = $img->fetch();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Agence</title>
  <style>
        body 
        {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .profile-container
         {
            width: 400px;
            margin: 50px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 25px;
            text-align: center;
        }

        .profile-container h1 
        {
            margin-bottom: 20px;
            color: #333;
        }

        .profile-img 
        {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #3498db;
            margin-bottom: 15px;
        }

        .profile-info 
        {
            text-align: left;
            margin-top: 15px;
        }

    .profile-info p 
        {
            padding: 8px;
            border-bottom: 1px solid #eee;
            margin: 0;
            font-size: 14px;
        }

     .profile-info strong 
        {
            color: #555;
        }

        .error
         {
            color: red;
            font-weight: bold;
        }
</style>
</head>
<body>
<?php
   echo "<div class='profile-container'>";
    echo "<h1>Profil du commercial</h1>";

    if($com)
    {
        $chemin = "imageP/". $photo['url'];
        if(file_exists($chemin))
        {
            echo "<img src='".$chemin."' class='profile-img'>";
        }
        else
        {
            echo "Image introuvable : ".$chemin;
        }

        echo "<p><strong>Nom :</strong> ".htmlspecialchars($com['Nom'])."</p>";
        echo "<p><strong>Prénom :</strong> ".htmlspecialchars($com['Prenom'])."</p>";
        echo "<p><strong>Email :</strong> ".htmlspecialchars($com['Adresse'])."</p>";
        echo "<p><strong>Téléphone :</strong> ".htmlspecialchars($com['Telephone'])."</p>";
        echo "<p><strong>Profession :</strong> ".htmlspecialchars($com['Profession'])."</p>";
    }
    else
    {
        echo "<p>COmmercial introuvable</p>";
    }
     echo"<a href='ParametreC.php'>Retour</a>";
  echo"</div>";
?>
</body>
</html>
