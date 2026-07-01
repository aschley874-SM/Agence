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

        if(isset($_POST["envoi_reserver"]))
        {

            $codeBien = $_POST["envoi_reserver"];

            
            $check = $pdo->prepare("SELECT * FROM historique  WHERE codeBien=? AND codeCli=?");
            $check->execute([$codeBien, $codeCli]);

            if($check->rowCount() > 1) 
            {
            // supprimer historique
                $sup = $pdo->prepare("DELETE FROM historique  WHERE codeBien=? AND codeCli=?");
                $sup->execute([$codeBien, $codeCli]);

            }
            else 
            {

                // ajouter historique
                $ins = $pdo->prepare("  INSERT INTO historique(codeBien, codeCli,codeReservation) VALUES(?,?,?)");
                if($reservation)
                {
                     $ins->execute([$codeBien, $codeCli, $reservation['id']]);
                    }
                
            }

            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
        
        $req = $pdo->prepare("SELECT h.*,b.idBien,b.Nom,b.Prix,b.likes,b.dislikes,h.date_de_reservation FROM historique h
                                JOIN bien b ON h.codeBien = b.idBien 
                                LEFT JOIN reservation r ON h.codeReservation = r.id WHERE h.codeCli = ?");
         $req->execute([$codeCli]);
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


<h1 class="text-center my-4">Historique des réservations :</h1>
<div class="header-top mb-4">
    <div class="container d-flex align-items-center py-2">
        
        <marquee class="flex-grow-1 rounded-pill shadow-sm" 
                 style="background-color: #FFCC00; color: white; font-weight: bold; padding: 8px 15px; border: 1px solid #e6b800;">
               Vos reservations seront envoyer au commercials 
        </marquee>

        <a href="Reservation.php" class="btn btn-dark ms-3">
                Retour
        </a>
    </div>
</div>
<section id="boostrap">
<div class="container py-4">

    <div class="row row-cols-1 row-cols-md-3 g-4">

    <?php while($p = $req->fetch()) 
    { 

        // Photo du bien
        $imgReq = $pdo->prepare("SELECT * FROM photobiens WHERE codeBien=? LIMIT 1");
        $imgReq->execute([$p['idBien']]);
        $photo = $imgReq->fetch();
    ?>

        <div class="col">
            <div class="card h-100 shadow-sm">

                <!-- IMAGE -->
                <?php if($photo && !empty($photo['url'])) { ?>
                    <img src="images/<?= htmlspecialchars($photo['url']) ?>"
                         class="card-img-top"
                         style="height:200px; object-fit:cover;">
                <?php } else { ?>
                    <div class="bg-light text-center py-5">
                        Pas d'image
                    </div>
                <?php } ?>

                <!-- BODY -->
                <div class="card-body">
                    <h5 class="card-title text-uppercase fw-bold text-dark">
                        <?= htmlspecialchars($p['Nom']) ?>
                    </h5>

                    <p class="card-text fs-6">
                        <strong>Prix:</strong>
                        <span class="text-primary fw-semibold">
                            <?= number_format($p['Prix'], 0, ',', ' ') ?> FCFA
                        </span>
                    </p>
                </div>

                <!-- FOOTER -->
                <div class="card-footer bg-white">

                    <!-- Likes -->
                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <button class="btn btn-success btn-sm"
                                onclick="likeBien(<?= $p['idBien'] ?>)"
                                id="like-<?= $p['idBien'] ?>">
                            👍 <?= formatLikes($p['likes']) ?>
                        </button>

                        <button class="btn btn-danger btn-sm"
                                onclick="dislikeBien(<?= $p['idBien'] ?>)"
                                id="dislike-<?= $p['idBien'] ?>">
                            👎 <?= formatLikes($p['dislikes']) ?>
                        </button>

                    </div>

                    <!-- Bouton voir plus -->
                    <a href="detailBiens.php?id=<?= $p['idBien'] ?>"
                       class="btn btn-primary btn-sm w-100">
                        Voir plus
                    </a>

                </div>

            </div>
        </div>

    <?php } ?>

    </div>
</div>
</section>