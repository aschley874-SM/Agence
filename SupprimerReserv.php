<?php
session_start();
      require("connect.php");

      if(!isset($_SESSION["codeCli"]))
      {
          header("Location: Prépare.php");
          exit();
      }
      

      if(!isset($_GET["id"]))
      {
          die("ID invalide");
      }
      
      $code2 = intval($_GET["id"]);
      $codeCli = $_SESSION["codeCli"];

     $req= $pdo->prepare("DELETE  FROM reservation WHERE codeCli=? AND codeBien=?");
     $req->execute([$codeCli,$code2]);
      
     header("location: Reservation.php");
   ?>