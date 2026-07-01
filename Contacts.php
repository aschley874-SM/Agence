<?php
    session_start();
    require("Connect.php");
    require("functions.php");

    if (!isset($_SESSION["codeCli"])) 
    {
        header("Location: Prépare.php");
        exit();
    }
    
    $codeCli = $_SESSION["codeCli"];

    $req = $pdo->prepare("SELECT * FROM contacts");
    $req->execute();
    $contacts = $req->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Agence</title>
  <style>
        body 
        {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .profile-container
         {
            width: 400px;
            margin: 50px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 25px;
            text-align: center;
        }

        .profile-container h1 
        {
            margin-bottom: 20px;
            color: #333;
        }

        .profile-img 
        {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #3498db;
            margin-bottom: 15px;
        }

        .profile-info 
        {
            text-align: left;
            margin-top: 15px;
        }

    .profile-info p 
        {
            padding: 8px;
            border-bottom: 1px solid #eee;
            margin: 0;
            font-size: 14px;
        }

     .profile-info strong 
        {
            color: #555;
        }

        .error
         {
            color: red;
            font-weight: bold;
        }
</style>
</head>
<body>
<?php
   echo "<div class='profile-container'>";
    echo "<h1>Nos contacts disponible</h1>";

    foreach ($contacts as $contact) 
    {

        // image du contact
        $img = $pdo->prepare("SELECT url FROM photocont WHERE id_contact = ? LIMIT 1");
        $img->execute([$contact['id']]);
        $photo = $img->fetch(PDO::FETCH_ASSOC);
    
        $chemin = ($photo && isset($photo['url'])) ? "imageP/" . $photo['url'] : null;
    
        echo "<div style='border:1px solid #ccc; margin:10px; padding:10px;'>";
    
        if ($chemin && file_exists($chemin)) 
        {
            echo "<img src='$chemin' class='profile-img'>";
        } 
        else
         {
            echo "<p>Image introuvable</p>";
        }
    
        echo "<p><strong>Nom :</strong> " . htmlspecialchars($contact['prenom']) . "</p>";
        echo "<p><strong>Prénom :</strong> " . htmlspecialchars($contact['nom']) . "</p>";
        echo "<p><strong>Téléphone :</strong> " . htmlspecialchars($contact['telephone']) . "</p>";
        echo "</div>";
    }
 echo"   <a href='PageClients.php' class='btn btn-dark ms-2'>
    Retour
    </a>";
?>
</body>
</html>
