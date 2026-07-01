<?php
   session_start();
   require_once "connect.php";
   require_once "functions.php";
  
    $codeCli = $_SESSION["codeCli"] ?? null;
    if(!isset($_SESSION["codeCli"]))
    {
        header("Location: Prépare.php");
        exit();
    }

   //ici c'as fais ensorte que la valeur fav soit un tableau obligatoirement 
   if(!isset($_SESSION["fav"]) || !is_array($_SESSION["fav"]))
   {
    $_SESSION["fav"] = [];
    }
       
    if(!isset($_SESSION["fav"]))
    {
        $_SESSION["fav"]=[];
    }
       
    if(isset($_POST["favorit"]))
    {
        $fav= $_POST["favorit"];
        $check= $pdo->prepare("SELECT * from favoris WHERE codeBien=? AND codeCli=?");
        $check->execute([$fav, $codeCli]);

        if($check->rowCount() >0)
        {
            //on supprime de la base si il est deja dans la table des favoris et on ajoute
            $sup = $pdo->prepare("DELETE FROM favoris WHERE codeBien=? AND codeCli=?");
            $sup->execute([$fav, $codeCli]);
            //on enleve de la session
            $_SESSION["fav"]= array_diff($_SESSION["fav"], [$fav]);
        }
        else 
        {
            $yes= $pdo->prepare("INSERT INTO favoris(codeBien,codeCli) value(?,?)");
            $yes->execute([$fav,$codeCli]);
            $_SESSION["fav"][]= $fav;
        }
        header("location:".$_SERVER["PHP_SELF"]);
    }  



    $search = "";

    if (isset($_GET['recherche'])) 
     {
        $search = htmlspecialchars($_GET['recherche']);
    }

    if (!empty($search)) 
    {
        $req = $pdo->prepare("SELECT * FROM bien WHERE LOWER(Nom) LIKE LOWER(?)");
        $req->execute(["%" . $search . "%"]);
     }

    else 
    {
        $req = $pdo->query("SELECT * FROM bien");
     }

    $biens = $req->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
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
<body>

    <h1 class="text-center my-4">LES VENTES DISPONIBLE SUR ASHY.COM</h1>

    <div class="header-top mb-4">
        <div class="container d-flex align-items-center py-2">
            
            <marquee class="flex-grow-1 rounded-pill shadow-sm" 
                    style="background-color: #FFCC00; color: white; font-weight: bold; padding: 8px 15px; border: 1px solid #e6b800;">
                🔥 DERNIÈRES OPPORTUNITÉS : Villa Dakar Prestige et bien d'autres à découvrir dès maintenant !
            </marquee>

            <a href="Panier.php" class="btn btn-warning ms-2">
            <?php
                        $nbFav = $pdo->prepare("SELECT COUNT(*) FROM favoris WHERE codeCli=?");
                        $nbFav->execute([$codeCli]);
                        $totalFav = $nbFav->fetchColumn();
            ?>
                🛒 Panier (<?= $totalFav ?>)
            </a>
            <a href="ParametreCL.php" class="btn btn-dark ms-2">
                Parmetres⚙️
            </a>
            <a href="Contacts.php" class="btn btn-primary ms-2">
                 Contacts📞
            </a>
        </div>
    </div>
    
    <div class="container mb-5 mt-4"> 
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <form action="" method="GET" class="d-flex shadow-sm p-2 bg-white rounded border">
                    <input type="text" name="recherche" class="form-control border-0" 
                        placeholder="Quel bien recherchez-vous ?" 
                        value="<?= $search ?>">
                    <button type="submit" class="btn btn-primary ms-2">🔍</button>
                    <?php if(!empty($search)): ?>
                        <a href="PageClients.php" class="btn btn-outline-secondary ms-2">X</a>
                    <?php endif; ?>
                </form>
                <?php if(!empty($search)): ?>
                    <p class="text-muted mt-2 small">Résultats pour : "<strong><?= $search ?></strong>"</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row g-4">

        <?php  foreach($biens as $p)  
        { 

            $imgReq = $pdo->prepare("SELECT * FROM photobiens WHERE codeBien=? LIMIT 1");
            $imgReq->execute([$p['idBien']]);
            $photo = $imgReq->fetch();
        ?>

            <div class="col-12 col-md-4">

                <div class="card h-100 shadow-sm">

                    <!-- IMAGE -->
                    <?php if($photo && !empty($photo['url'])) { ?>
                        <img src="images/<?= htmlspecialchars($photo['url']) ?>"
                            class="card-img-top"
                            style="height:200px; object-fit:cover;">
                    <?php } else { ?>
                        <div class="bg-light text-center py-5">Pas d'image</div>
                    <?php } ?>

                    <!-- BODY -->
                    <div class="card-body">
                        <h5 class="text-uppercase fw-bold"><?= $p['Nom'] ?></h5>
                        <p>
                            <strong>Prix:</strong>
                            <?= number_format($p['Prix'],0,',',' ') ?> FCFA
                        </p>
                    </div>

                    <!-- FOOTER -->
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

                        <div class="d-flex justify-content-between align-items-center">

                            <form method="post">
                                <input type="hidden" name="favorit" value="<?= $p['idBien'] ?>">
                                <button class="btn btn-outline-danger btn-sm">
                                    <?= in_array($p['idBien'], $_SESSION['fav']) ? "❤️" : "🤍" ?>
                                </button>
                            </form>

                            <a href="detailBiens.php?id=<?= $p['idBien'] ?>"class="btn btn-primary btn-sm">
                                Voir plus
                            </a>

                        </div>

                    </div>

                </div>
            </div>

        <?php } ?>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
