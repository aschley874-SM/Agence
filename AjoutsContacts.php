
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Agence</title>
  <link rel="stylesheet" href="style2.css"/>
</head>

<body>

<form method="post" enctype="multipart/form-data">
            <h1>Fiche de contact</h1>
  Nom:<input type="text" name="Nom" required>
  Prenom<input type="text" name="Prenom"  required>
    Tel:<input type="tel" name="Telephone" required>
    
 Image:<input type="file" name="image">

  <button type="submit" name="OK">Ajout</button>
</form>


<?php
   require("connect.php");
 if (isset($_POST["OK"])) 
 {

      $nom=$_POST["Nom"];
      $prenom=$_POST["Prenom"];
      $tel= $_POST["Telephone"];

      $req = $pdo->prepare("INSERT INTO contacts (nom, prenom, telephone) VALUES (?, ?, ?)");
      $req->execute([$nom, $prenom, $tel]);
     $idContact = $pdo->lastInsertId();

     if (!empty($_FILES["image"]["name"])) 
    {
        // créer dossier si n'existe pas
        if (!is_dir("imageP")) 
        {
            mkdir("imageP");
        }

        // renommer image
        $img = time() . "_" . $_FILES["image"]["name"];

        // déplacer fichier
        move_uploaded_file($_FILES["image"]["tmp_name"], "imageP/" . $img);

        // enregistrer dans la base
        $imgReq = $pdo->prepare("INSERT INTO photocont(url,id_contact) VALUES (?, ?)");
        $imgReq->execute([$img, $idContact]);
    }

    }
?>

</body>
</html>

