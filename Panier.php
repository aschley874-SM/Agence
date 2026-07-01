<?php   
        session_start();

        require("Connect.php");
        require_once("functions.php");

        $codeCli = $_SESSION["codeCli"] ?? null;
        if(!isset($_SESSION["codeCli"]))
        {
            header("Location: Prépare.php");
            exit();
        }

        if(isset($_POST['reserver']))
        {
            if(!isset($_SESSION["codeCli"]))
            {
                die("Veuillez vous connecter !");
            }
        
            $codeCL = $_SESSION["codeCli"];
            $codeBien = $_POST["reserver"];
        
             
            $check = $pdo->prepare("SELECT * FROM reservation WHERE codeBien=? AND codeCli=?");
            $check->execute([$codeBien, $codeCL]);
        
            if($check->rowCount() > 0)
            {
               
                $sup = $pdo->prepare("DELETE FROM reservation WHERE codeBien=? AND codeCli=?");
                $sup->execute([$codeBien, $codeCL]);
            }
            else if ($check->rowCount() == 0)
            {
               
                        $yes = $pdo->prepare("INSERT INTO reservation(codeBien, codeCli) VALUES(?,?)");
                        $yes->execute([$codeBien, $codeCL]);
            }
        
            header("Location: Reservation.php");
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

<h1 class="text-center my-4">Les Ventes mise en favoris:</h1>
<div class="header-top mb-4">
    <div class="container d-flex align-items-center py-2">
        
        <marquee class="flex-grow-1 rounded-pill shadow-sm" 
                 style="background-color: #FFCC00; color: white; font-weight: bold; padding: 8px 15px; border: 1px solid #e6b800;">
            🔥 Vos biens préferés peuvent etre mise en reservation faite votre choix!
        </marquee>

        <a href="PageClients.php" class="btn btn-dark ms-2">
                Retour
        </a>
      
         <a href="Reservation.php" class="btn btn-warning ms-2">
         Reservation(s)
        </a> 
    </div>
</div>
               
<section id="boostrap">
<div class="container">
   <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php 
     $req = $pdo->prepare("SELECT *FROM favoris JOIN bien ON favoris.codeBien = bien.idBien WHERE favoris.codeCli = ?");
        $req->execute([$codeCli]);?>
   <?php while($p = $req->fetch()) 
{
    $imgReq = $pdo->prepare("SELECT * FROM photobiens WHERE codeBien=? LIMIT 1");
    $imgReq->execute([$p['idBien']]);
    $photo = $imgReq->fetch();
?>

<div class="col">
    <div class="card h-100 shadow-sm">

      
        <?php if($photo && !empty($photo['url'])) { ?><img src="images/<?= htmlspecialchars($photo['url']) ?>"
                 class="card-img-top" style="height:200px; object-fit:cover;">
        <?php } else { ?>
            <div class="bg-light text-center py-5">Pas d'image</div>
        <?php } ?>

      
        <div class="card-body">
            <h5 class="card-title text-uppercase">
                <?= htmlspecialchars($p['Nom']) ?>
            </h5>

            <p>
                <strong>Prix :</strong>
                <?= number_format($p['Prix'], 0, ',', ' ') ?> FCFA
            </p>
        </div>

 
        <div class="card-footer bg-white">

    
            <div class="d-flex justify-content-between mb-2">
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

          
            <div class="d-flex justify-content-between">

                <form method="post">
                    <button type="submit"
                            name="reserver"
                            value="<?= $p['idBien'] ?>"
                            class="btn btn-outline-dark btn-sm">
                        Réserver
                    </button>
                </form>

            
                <a href="detailBiens.php?id=<?= $p['idBien'] ?>"
                   class="btn btn-primary btn-sm">
                   Voir plus
                </a>

                <!-- SUPPRIMER -->
                <a href="SuppressionFav.php?id=<?= $p['idBien']?>" class="btn btn-sm btn-danger px-1"> Supprimer des favoris</a>

            </div>

        </div>

    </div>
</div>

<?php } ?>
</section>

      