    <?php
    require_once "connect.php";
    session_start();
    if(!isset($_GET['id']) || empty($_GET['id']))
    {
        die("Bien introuvable !");
    }

    $id = $_GET['id'];
    // Produit
    $req = $pdo->prepare("SELECT * FROM bien WHERE idBien=?");
    $req->execute([$id]);
    $p = $req->fetch();
    if(!$p)
    {
        die("Bien introuvable !");
    }
    // Images
    $imgs = $pdo->prepare("SELECT * FROM photobiens WHERE codeBien=?");
    $imgs->execute([$p['idBien']]); // ✔️ plus sûr

    $photos = [];
    while($img = $imgs->fetch())
    {
        $photos[] = $img['url'];
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Détail bien</title>

    <style>
    body 
    {
        font-family: Arial;
        background: #eef6f9;
        text-align: center;
    }

    .container 
    {
        width: 60%;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    .slide
    {
        width: 100%;
        height: 350px;
        object-fit: cover;
        display: none;
        border-radius: 10px;
    }

    button
    {
        padding: 10px 15px;
        margin: 10px;
        cursor: pointer;
        border: none;
        border-radius: 6px;
        background: #0077b6;
        color: white;
    }

    button:hover
    {
        background: #023e8a;
    }

    a 
    {
        display: inline-block;
        margin-top: 15px;
        color: #0077b6;
        text-decoration: none;
    }
    </style>

    <script>
    let index = 0;

    function showImage(i)
    {
        let images = document.getElementsByClassName("slide");

        if(images.length == 0) return;

        for(let j=0; j<images.length; j++)
        {
            images[j].style.display = "none";
        }

        images[i].style.display = "block";
    }

    function next()
    {
        let images = document.getElementsByClassName("slide");
        index++;
        if(index >= images.length) index = 0;
        showImage(index);
    }

    function prev()
    {
        let images = document.getElementsByClassName("slide");
        index--;
        if(index < 0) index = images.length - 1;
        showImage(index);
    }

    window.onload = function()
    {
        showImage(index);
    }
    </script>

    </head>

    <body>

    <div class="container">

        <h2><?= $p['Type'] ?></h2>

        <h3><?= $p['Nom'] ?></h3>

        <p><strong>Prix :</strong> <?= number_format($p['Prix'],0,',',' ') ?> FCFA</p>

     <p><strong>Adresse :</strong> <?= $p['Adresse_Position'] ?></p>
   
    <p><strong>Description :</strong> <?= $p['Description'] ?></p>

     <p>👍 Likes : <?= $p['likes'] ?></p>
     <p>👎 Dislikes : <?= $p['dislikes'] ?></p>

    <h3>Photos</h3>

    <?php foreach($photos as $img){ ?>
        <img class="slide" src="images/<?= $img ?>">
    <?php } ?>
    <div>
        <button onclick="prev()">⬅️ Précédent</button>
        <button onclick="next()">Suivant ➡️</button>
    </div>

    <a href="PageClients.php">← Retour</a>

    </div>

 </body>
</html>