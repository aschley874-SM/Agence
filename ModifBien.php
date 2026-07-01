<!DOCTYPE html>
 <html>
    <head>
      <meta charset="UTF-8">
      <link rel="stylesheet" href="style2.css"/>
      <style>
     
        </style>
</head>
 <?php
     require("connect.php");
        $code = $_GET['idBien'];
        if(!$code)
        {
          die("ID manquant !");
        }

      $req= $pdo->prepare("SELECT * FROM bien WHERE idBien=?");// a partir d'ici on recupere les infos du produit
        $req->execute([$code]);
        $B= $req->fetch();
       
?>
<body>
       
           

        <form method="post" enctype="multipart/form-data">
        <h1>Modifications:</h1>
          <input type="text" name="nom" value="<?= $B['Nom'] ?>" required>

            <input type="text" name="adresse" value="<?= $B['Adresse_Position'] ?>" required>

             <select name="type">
                <option <?= $B['Type']=="Appartement" ? "selected" : "" ?>>Appartement</option>
                <option <?= $B['Type']=="Villa" ? "selected" : "" ?>>Villa</option>
            </select>

            <input type="number" name="prix" value="<?= $B['Prix'] ?>" required>

            <input type="text" name="description" value="<?= $B['Description'] ?>">

         <input type="submit" value="Ajouter" name="modif">
         <a href="PageCommercant.php">Retour</a>
    </form><br><br><br>
</body>
<?php
        if(isset($_POST["modif"]))
        {   

            $nom= $_POST["nom"];
            $adresse=$_POST["adresse"];
            $type= $_POST["type"];
            $description= $_POST["description"];
            $prix= $_POST["prix"];


            $code = $_GET['idBien'];
            $req= $pdo->prepare("UPDATE bien SET Nom=?,Adresse_Position=?, Type=?, Description=?, Prix=? WHERE idBien=?");
            $req->execute([$nom,$adresse,$type,$description,$prix,$code]);

            echo"<h2 style='color=green; font-wheight=bold'>modification reussite!!</h2>";


        }

?>
</html>