    <?php
    require_once "connect.php";
    session_start();

    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    $choix = strtolower(trim($_POST['choix'] ?? ''));

    if (!$choix) 
    {
        die("❌ Choix non défini");
    }

   
    if (!isset($_POST['Nom'], $_POST['Prenom'], $_POST['Adresse'],$_POST['mot_de_passe'], $_POST['Age']))
    {
        die("❌ Champs manquants");
    }

    $nom = $_POST['Nom'];
    $prenom = $_POST['Prenom'];
    $email = $_POST['Adresse'];
    $password = $_POST['mot_de_passe'];
    $genre = $_POST['genre'] ?? null;
    $tel = trim($_POST['Telephone'] ?? '');
    $age = $_POST['Age'];
    $profession = $_POST['Profession'] ?? null;

   
    if (empty($tel)) 
    {
        die("❌ Téléphone obligatoire");
    }

    if (!preg_match("/^[0-9+ ]+$/", $tel))
    {
        die("❌ Numéro invalide");
    }

    if (empty($genre)) 
    {
        die("❌ Genre obligatoire");
    }

  
    if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) 
    {
        die("❌ Image obligatoire");
    }


    $hash = password_hash($password, PASSWORD_DEFAULT);

   
    if (!is_dir("imageP")) 
    {
        mkdir("imageP");
    }

  
    $imageName = time() . "_" . $_FILES['image']['name'];

   
    move_uploaded_file($_FILES['image']['tmp_name'], "imageP/" . $imageName);

    if ($choix == "commercant") 
    {
        $req = $pdo->query("SELECT COUNT(*) FROM commerciaux");
        $nbCom = $req->fetchColumn();

        if ($age < 18) 
        {
            $message = "❌ Minimum 18 ans pour commerçant";
            $type = "error";
        }
        elseif ($nbCom >= 20) 
        {
            $message = "❌ Limite atteinte";
            $type = "error";
        }
        else
        {
            $insert = $pdo->prepare(" INSERT INTO commerciaux(Nom, Prenom, Adresse, Telephone, Profession, mot_de_passeC, genre, Age)
                                        VALUES (?,?,?,?,?,?,?,?)");
            $insert->execute([$nom, $prenom, $email, $tel, $profession, $hash, $genre, $age,]);

            $idCom = $pdo->lastInsertId();

            $photo = $pdo->prepare(" INSERT INTO photocommerciaux(url, codeCom)  VALUES (?,?)
            ");
            $photo->execute([$imageName, $idCom]);

            $message= "✅ Commerçant enregistré avec succès";
            $type = "success";
        }
    }

   

    elseif ($choix == "client") 
    {
        if ($age < 13)
        {
            $message= "❌ Minimum 13 ans pour client";
            $type = "error";
        }
        else
        {
            $insert = $pdo->prepare("INSERT INTO client(Nom, Prenom, Adresse, Telephone, mot_de_passeCl, genre, Age,Profession) VALUES (?,?,?,?,?,?,?,?)");
            $insert->execute([$nom, $prenom, $email, $tel, $hash, $genre, $age,$profession]);

            $idClient = $pdo->lastInsertId();

            $photo = $pdo->prepare(" INSERT INTO photoclients(url, codeClient) VALUES (?,?)");
            $photo->execute([$imageName, $idClient]);

            $message ="✅ Client enregistré avec succès";
            $type = "success";
        }
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    <title>Résultat</title>
    <style>
    body{
        font-family: Arial;
        background: linear-gradient(135deg, #0077b6, #00b4d8);
        min-height: 100vh;
        display:flex;
        justify-content:center;
        align-items:center;
    }
    .box{
        background:white;
        padding:30px;
        border-radius:10px;
        text-align:center;
        width:350px;
    }
    .success{ color:green; }
    .error{ color:red; }
    a{
        display:block;
        margin-top:15px;
        background:#2ecc71;
        color:white;
        padding:10px;
        text-decoration:none;
        border-radius:6px;
    }

    </style>
    </head>
    <body>
    <div class="box">
        <h2 class="<?= $type ?>">
            <?= $message ?>
        </h2>

        <?php if ($type === "error") : ?>
            <a href="Inscription.php">⬅ Retour à l'inscription</a>
        <?php else : ?>
            <a href="Connexion.php">➡ Aller à la connexion</a>
        <?php endif; ?>
    </div>
    </body>
    </html>
