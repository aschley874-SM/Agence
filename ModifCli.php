<?php
session_start();
require("connect.php");


 if (!isset($_SESSION["codeCli"])) 
 {
    header("Location: Prépare.php");
    exit();
 }

$code = $_SESSION["codeCli"];


$req = $pdo->prepare("SELECT * FROM client WHERE idCli=?");
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
            <h1>Fiche Client</h1>
  Nom:<input type="text" name="Nom" value="<?= htmlspecialchars($C['Nom']) ?>" required>
  Prenom<input type="text" name="Prenom" value="<?= htmlspecialchars($C['Prenom']) ?>" required>
  Email:<input type="email" name="Adresse" value="<?= htmlspecialchars($C['Adresse']) ?>" required>

  <input type="password" name="mot_de_passe" placeholder="Nouveau mot de passe">

  Genre:<select name="genre">
    H<option value="Homme" <?= $C['genre']=="Homme" ? "selected" : "" ?>>Homme</option>
   F <option value="Femme" <?= $C['genre']=="Femme" ? "selected" : "" ?>>Femme</option>
  </select>

    Tel:<input type="text" name="Telephone" value="<?= htmlspecialchars($C['Telephone']) ?>">
     Age: <input type="number" name="Age" value="<?= htmlspecialchars($C['Age']) ?>" required>
   Profession<input type="text" name="Profession" value="<?= htmlspecialchars($C['Profession']) ?>" required>

 Image:<input type="file" name="image">

  <button type="submit" name="OK">Modifier</button>
  <a href="ParametreCL.php">Retour</a>
</form>


<?php
 if (isset($_POST["OK"])) 
 {

    $sql = "UPDATE client SET 
    Nom=?, Prenom=?, Adresse=?, Telephone=?, Age=?, Profession=?, genre=? 
    WHERE idCli=?";

        $params = [$_POST["Nom"], $_POST["Prenom"], $_POST["Adresse"],
                    $_POST["Telephone"],$_POST["Age"],$_POST["Profession"], $_POST["genre"], $code];

  $pdo->prepare($sql)->execute($params);
    // mot de passe
    if (!empty($_POST["mot_de_passe"]))
     {
        $sql .= ", mot_de_passe=?";
        $params[] = password_hash($_POST["mot_de_passe"], PASSWORD_DEFAULT);
    }

    if (!empty($_FILES["image"]["name"])) 
    {

        if (!is_dir("imageP")) 
        {
            mkdir("imageP");
        }
    
        $img = time() . "_" . $_FILES["image"]["name"];
        move_uploaded_file($_FILES["image"]["tmp_name"], "imageP/" . $img);
    
        // vérifier si image existe déjà
        $check = $pdo->prepare("SELECT idPCL FROM photoclients WHERE codeClient=?");
        $check->execute([$code]);
        $exist = $check->fetch();
    
        if ($exist) 
        {
           
            $up = $pdo->prepare("UPDATE photoclients SET url=? WHERE codeClient=?");
            $up->execute([$img, $code]);
        }
         else 
         {
            
            $ins = $pdo->prepare("INSERT INTO photoclients(url, codeClient) VALUES(?, ?)");
            $ins->execute([$img, $code]);
        }
    }
 }
?>

</body>
</html>