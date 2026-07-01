<?php
session_start();
require("connect.php");

$codeCli = $_SESSION["codeCli"] ?? null;
$id = $_GET["id"] ?? null;

if(!$codeCli || !$id){
    exit("Erreur");
}

// vérifier réaction
$check = $pdo->prepare("SELECT * FROM reaction_bien WHERE codeCli=? AND codeBien=?");
$check->execute([$codeCli, $id]);
$exist = $check->fetch();

if(!$exist){
    // nouveau dislike
    $pdo->prepare("INSERT INTO reaction_bien(codeCli, codeBien, type) VALUES(?,?, 'dislike')")
        ->execute([$codeCli, $id]);

    $pdo->prepare("UPDATE bien SET dislikes = dislikes + 1 WHERE idBien=?")
        ->execute([$id]);
}
else if($exist['type'] == 'like'){
    // switch like → dislike
    $pdo->prepare("UPDATE reaction_bien SET type='dislike' WHERE codeCli=? AND codeBien=?")
        ->execute([$codeCli, $id]);

    $pdo->prepare("UPDATE bien 
        SET likes = likes - 1, dislikes = dislikes + 1 
        WHERE idBien=?")
        ->execute([$id]);
}

// renvoyer le nombre de dislikes
$req = $pdo->prepare("SELECT dislikes FROM bien WHERE idBien=?");
$req->execute([$id]);
echo $req->fetchColumn();