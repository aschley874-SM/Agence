<?php
session_start();
require("connect.php");

$codeCli = $_SESSION["codeCli"] ?? null;
$id = $_GET["id"] ?? null;

if(!$codeCli || !$id){
    exit("Erreur");
}

// vérifier si déjà une réaction
$check = $pdo->prepare("SELECT * FROM reaction_bien WHERE codeCli=? AND codeBien=?");
$check->execute([$codeCli, $id]);
$exist = $check->fetch();

if(!$exist){
    // nouveau like
    $pdo->prepare("INSERT INTO reaction_bien(codeCli, codeBien, type) VALUES(?,?, 'like')")
        ->execute([$codeCli, $id]);

    $pdo->prepare("UPDATE bien SET likes = likes + 1 WHERE idBien=?")
        ->execute([$id]);
}
else if($exist['type'] == 'dislike'){
    // switch dislike → like
    $pdo->prepare("UPDATE reaction_bien SET type='like' WHERE codeCli=? AND codeBien=?")
        ->execute([$codeCli, $id]);

    $pdo->prepare("UPDATE bien 
        SET dislikes = dislikes - 1, likes = likes + 1 
        WHERE idBien=?")
        ->execute([$id]);
}

// renvoyer le nombre de likes
$req = $pdo->prepare("SELECT likes FROM bien WHERE idBien=?");
$req->execute([$id]);
echo $req->fetchColumn();