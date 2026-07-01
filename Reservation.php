<?php
    session_start();
    require("Connect.php");
    require("functions.php");
            
  $codeCli = $_SESSION["codeCli"] ?? null;
    if(!isset($_SESSION["codeCli"]))
     {
        header("Location: Prépare.php");
        exit();
    }

    // ajout et  suppression des reservation
    if(isset($_POST['reserver']))
    {
            
    if(!isset($_POST['reserver']))
    {
        die("Veuillez vous connecter!");
    }
        $codeBien = $_POST['reserver'];
        $codeCL = $_SESSION["codeCli"];
    $check = $pdo->prepare("SELECT * FROM reservation WHERE codeBien=? AND codeCli=?");
    $check->execute([$codeBien, $codeCli]);

    if($check->rowCount() > 1)
    {
        $sup = $pdo->prepare("DELETE FROM reservation WHERE codeBien=? AND codeCli=?");
        $sup->execute([$codeBien, $codeCli]);
    }
    else
    {
        $yes = $pdo->prepare("INSERT INTO reservation(codeBien, codeCli) VALUES(?,?)");
        $yes->execute([$codeBien, $codeCli]);
    }

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

    //afficharege des reservations
    $req = $pdo->prepare("SELECT r.*, b.idBien, b.Nom, b.Prix, b.likes, b.dislikes FROM reservation r  
                            JOIN bien b ON r.codeBien = b.idBien  WHERE r.codeCli = ?");

    $req->execute([$codeCli]);

        if(isset($_POST["envoi_reserver"]))
        {

            $codeBien = $_POST["envoi_reserver"];

            // verification dans historique
            $check = $pdo->prepare("SELECT * FROM historique  WHERE codeBien=? AND codeCli=?");
            $check->execute([$codeBien, $codeCli]);

            if($check->rowCount() > 0) 
            {
          
                $sup = $pdo->prepare("DELETE FROM historique  WHERE codeBien=? AND codeCli=?");
                $sup->execute([$codeBien, $codeCli]);

            }
            else 
            {

              
                $ins = $pdo->prepare("  INSERT INTO historique(codeBien, codeCli) VALUES(?,?)");
                $ins->execute([$codeBien, $codeCli]);
            }

            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
?>
<!DOCTYPE html>
<head>
<meta charset="UTF-8">
    <title>Agence ASHY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      
        .header-top 
    {
            background: #f8f9fa;
             padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
    </style>
        <script> 
    
    function likeBien(id)
    {
       
        fetch("like.php?id=" + id)
        
        
        .then(response => response.text())
        
     
        .then(data => {
           
            document.getElementById("like-" + id).innerHTML = "👍 " + data;
        });
    }


    function dislikeBien(id){
      
        fetch("dislike.php?id=" + id)
        
        
        .then(response => response.text())
        
       
        .then(data => {
            
            document.getElementById("dislike-" + id).innerHTML = "👎 " + data;
        });
    }
</script>
</head>


<h1 class="text-center my-4">Reservation(s):</h1>
<div class="header-top mb-4">
    <div class="container d-flex align-items-center py-2">
        
        <marquee class="flex-grow-1 rounded-pill shadow-sm" 
                 style="background-color: #FFCC00; color: white; font-weight: bold; padding: 8px 15px; border: 1px solid #e6b800;">
            🔥  Possibilitée de cofirmer ou de supprimmer une reservation donc prennez votre temps!!
        </marquee>

        <a href="Panier.php" class="btn btn-dark ms-3">
                Retour
        </a>
        <a href="HistoriqueReserveCli.php" class="btn btn-primary ms-3">
            Historique des reservations
            </a>
    </div>
</div>

<section id="boostrap">
<div class="container">
    <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php 
     
      
      while($p = $req->fetch())
    {
        // Recupere la premiere photo pour ce bien
        $imgReq = $pdo->prepare("SELECT * FROM photobiens WHERE codeBien=? LIMIT 1");
         $imgReq->execute([$p['codeBien']]);
        $photo = $imgReq->fetch();
    ?>
        <div class="col">
            <div class="card h-120 shadow-sm">
                <?php if($photo && !empty($photo['url'])){ ?>
                  <img src="images/<?= htmlspecialchars($photo['url']) ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                <?php } else { ?>
                  <div class="bg-light text-center py-5">Pas d'image</div>
                <?php } ?>
                
                <div class="card-body">
    <h5 class="card-title text-uppercase" style="font-weight: bold; color: #333;">
        <?= $p['Nom'] ?>
    </h5>
    <p class="card-text" style="font-size: 1.1rem;">
        <strong>Prix:</strong> 
        <span style="color: #2c3e50;">
            <?= number_format($p['Prix'], 0, ',', ' ') ?> FCFA
        </span>
    </p>
</div>

<div class="card-footer bg-white border-top-0 py-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <button class="btn btn-success btn-sm" onclick="likeBien(<?= $p['idBien'] ?>)" id="like-<?= $p['idBien'] ?>">
            👍 <?= formatLikes($p['likes']) ?>
        </button>
        
        <button class="btn btn-danger btn-sm" onclick="dislikeBien(<?= $p['idBien'] ?>)" id="dislike-<?= $p['idBien'] ?>">
            👎 <?= formatLikes($p['dislikes']) ?>
        </button>
                </div>
        
     <form method="post" class="m-0">
    <button type="submit"  name="envoi_reserver"  value="<?= $p['idBien'] ?>" class="btn btn-outline-dark btn-sm">
          Envoyer réservation
    </button>
     </form>

     <div class="d-flex gap-2">
    <a href="detailBiens.php?id=<?= $p['idBien'] ?>" class="btn btn-sm btn-primary">
        Voir plus
    </a>
    <a href="SupprimerReserv.php?id=<?= $p['idBien']?>" class="btn btn-sm btn-danger">
        Supprimer des reservation</a>
        </div>  
</div>
 <?php } ?>
    </div>
</section>
