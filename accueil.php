<?php
require_once "connect.php";
require_once "functions.php";

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


if(isset($_POST['connecter']))
{
    header("Location: Prépare.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Agence ASHY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .header-top {
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

function dislikeBien(id)
{
    fetch("dislike.php?id=" + id)
    .then(response => response.text())
    .then(data => {
        document.getElementById("dislike-" + id).innerHTML = "👎 " + data;
    });
}
</script>

</head>

<body>

<h1 class="text-center my-4">BIENVENU SUR ASHY.COM</h1>

<div class="header-top mb-4">
    <div class="container d-flex align-items-center py-2">

        <marquee class="flex-grow-1 rounded-pill shadow-sm"
                 style="background-color: #FFCC00; color: white; font-weight: bold; padding: 8px 15px;">
            🔥 DERNIÈRES OPPORTUNITÉS : Villa Dakar Prestige et bien d'autres !
        </marquee>

        <form method="POST" class="ms-3">
            <button type="submit" name="connecter" class="btn btn-primary btn-sm">
                🔐 Se connecter
            </button>
        </form>

    </div>
</div>


<div class="container mb-4">
    <form method="GET" class="d-flex">
        <input type="text" name="recherche" class="form-control"
               placeholder="Quel bien recherchez-vous ?"
               value="<?= htmlspecialchars($search) ?>">

        <button class="btn btn-primary ms-2">🔍</button>

        <?php if(!empty($search)): ?>
            <a href="accueil.php" class="btn btn-outline-secondary ms-2">X</a>
        <?php endif; ?>
    </form>

    <?php if(!empty($search)): ?>
        <p class="text-muted mt-2">
            Résultats pour : "<strong><?= $search ?></strong>"
        </p>
    <?php endif; ?>
</div>


<div class="container">
    <div class="row row-cols-1 row-cols-md-3 g-4">

    <?php foreach($biens as $p) 
    { 

        $imgReq = $pdo->prepare("SELECT * FROM photobiens WHERE codeBien=? LIMIT 1");
        $imgReq->execute([$p['idBien']]);
        $photo = $imgReq->fetch();
    ?>

        <div class="col">
            <div class="card h-100 shadow-sm">

                <?php if($photo && !empty($photo['url'])) { ?>
                    <img src="images/<?= htmlspecialchars($photo['url']) ?>"
                         class="card-img-top"
                         style="height:200px; object-fit:cover;">
                <?php } else { ?>
                    <div class="bg-light text-center py-5">Pas d'image</div>
                <?php } ?>

                <div class="card-body">
                    <h5 class="text-uppercase fw-bold">
                        <?= htmlspecialchars($p['Nom']) ?>
                    </h5>

                    <p>
                        <strong>Prix:</strong>
                        <?= number_format($p['Prix'],0,',',' ') ?> FCFA
                    </p>
                </div>

                <div class="card-footer d-flex justify-content-between align-items-center bg-white">

                    <div class="d-flex gap-3">
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

                    <a href="DetailBien.php?idBien=<?= $p['idBien'] ?>"
                       class="btn btn-primary btn-sm">
                        Voir plus
                    </a>

                </div>
            </div>
        </div>

    <?php } ?>

    </div>

    <!-- SI VIDE -->
    <?php if(count($biens) == 0): ?>
        <div class="alert alert-warning text-center mt-4">
            Aucun bien trouvé pour "<?= $search ?>"
        </div>
    <?php endif; ?>

</div>

</body>
</html>