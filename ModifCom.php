<?php
session_start();
require("connect.php");


    if(!isset($_SESSION["codeCom"])) 
     {
        header("Location: Prépare.php");
        exit();
     }

    $code = $_SESSION["codeCom"];

    $req = $pdo->prepare("SELECT * FROM commerciaux WHERE idCom=?");
    $req->execute([$code]);
    $C = $req->fetch(PDO::FETCH_ASSOC);

  if (!$C) 
     {
        die("Client introuvable !");
    }
 ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Agence</title>
  <link rel="stylesheet" href="style2.css"/>
</head>

<body>



<form method="post" enctype="multipart/form-data">
            <h1>Fiche Commercial</h1>
            Nom:<input type="text" name="Nom" value="<?= htmlspecialchars($C['Nom']) ?>" required>
  Prenom<input type="text" name="Prenom" value="<?= htmlspecialchars($C['Prenom']) ?>" required>
  Email:<input type="email" name="Adresse" value="<?= htmlspecialchars($C['Adresse']) ?>" required>

  <input type="password" name="mot_de_passe" placeholder="Nouveau mot de passe">
  Genre:
<select name="genre">
  <option value="Homme" <?= $C['Genre']=="Homme" ? "selected" : "" ?>>Homme</option>
  <option value="Femme" <?= $C['Genre']=="Femme" ? "selected" : "" ?>>Femme</option>
</select><br>
    Tel:<input type="text" name="Telephone" value="<?= htmlspecialchars($C['Telephone']) ?>">
     Age: <input type="number" name="Age" value="<?= htmlspecialchars($C['Age']) ?>" required>
   Profession<input type="text" name="Profession" value="<?= htmlspecialchars($C['Profession']) ?>" required>

 Image:<input type="file" name="image">

  <button type="submit" name="OK">Modifier</button>
  <a href="ParametreC.php">Retour</a>
</form>


<?php
if (isset($_POST["OK"])) 
{

    $sql ="UPDATE commerciaux SET Nom=?, Prenom=?, Adresse=?, Telephone=?, Age=?, Profession=?, Genre=? WHERE idCom=?";

        $params = [$_POST["Nom"], $_POST["Prenom"], $_POST["Adresse"],
                    $_POST["Telephone"],$_POST["Age"],$_POST["Profession"], $_POST["genre"], $code];

$pdo->prepare($sql)->execute($params);
    
        //ici on fait ensorte que le mot de passe ne soit pas changee
    if (!empty($_POST["mot_de_passe"]))
     {
        $sql .= ", mot_de_passe=?";
        $params[] = password_hash($_POST["mot_de_passe"], PASSWORD_DEFAULT);
    }

    if (!empty($_FILES["image"]["name"])) 
    {

        if (!is_dir("imageP")) 
        {
            mkdir("imageO");
        }
    
        $img = time() . "_" . $_FILES["image"]["name"];
        move_uploaded_file($_FILES["image"]["tmp_name"], "imageP/" . $img);
    
       
        $check = $pdo->prepare("SELECT idPC FROM photocommerciaux WHERE codeCom=?");
        $check->execute([$code]);
        $exist = $check->fetch();
    
        if ($exist) 
        {
            
            $up = $pdo->prepare("UPDATE photocommerciaux SET url=? WHERE codeCom=?");
            $up->execute([$img, $code]);
        }
         else 
         {
            
            $ins = $pdo->prepare("INSERT INTO photocommerciaux(url, codeCom) VALUES(?, ?)");
            $ins->execute([$img, $code]);
        }
    }
}
?>

</body>
</html>