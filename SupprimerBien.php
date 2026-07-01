<?php
   session_start();
  
   if(!isset($_SESSION["codeCom"]))
   {
       header("Location: Prépare.php");
       exit();
   }
      require("connect.php");

      if(!isset($_GET['idBien']))
      {
          die("ID invalide");
      }
      
      $code = intval($_GET['idBien']);

      $img = $pdo->prepare("SELECT url FROM photobiens WHERE codeBien=?");
        $img->execute([$code]);

    while($photo = $img->fetch())
    {
      $path = "images/" . $photo['url'];

    if(file_exists($path))
     {
        unlink($path); // supprime fichier
      }
}

      $req = $pdo->prepare("DELETE  FROM photobiens WHERE codeBien=?");//ici on supprime les photos de la table photos dans mysql
        $req->execute([$code]);

     $req= $pdo->prepare("DELETE  FROM bien WHERE idBien=?");//supprime les elements de la table bien
     $req->execute([$code]);
      
     header("location: PageCommercant.php");
     exit();
   ?>