<?php
  session_start();
  
if(!isset($_SESSION["codeCom"]))
{
    header("Location: Prépare.php");
    exit();
}
        require("Connect.php");

        if(isset($_POST['envoie']))
     {
        
            $nom = $_POST['nom'];
            $adresse = $_POST['adresse'];
            $type = $_POST['type'];
            $prix = $_POST['prix'];
            $description = $_POST['description'];
        
            // insertion bien
            $req = $pdo->prepare("INSERT INTO bien(Nom, Type, Prix, Description, Adresse_Position)  VALUES (?, ?, ?, ?, ?)");
            $req->execute([$nom, $type, $prix, $description, $adresse]);
        
            $idBien = $pdo->lastInsertId();
        
            // ✅ GESTION DES IMAGES
            if(!empty($_FILES['images']['name'][0])) 
        {
        
                foreach($_FILES['images']['tmp_name'] as $key => $tmp) 
                {
                     $name = $_FILES['images']['name'][$key];
                    move_uploaded_file($tmp, "images/".$name);
        
                    $img = $pdo->prepare("INSERT INTO photobiens(url, codeBien) VALUES(?, ?)");
                    $img->execute([$name, $idBien]);
                }
            }
            echo"<h3 style='color:green'>Bien ajouté avec succès !</h3>";
        }
?>
<!DOCTYPE html>
<html>
    <head>
         <meta charset="UTF-8">
         <title>Agence ASHY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Petit ajustement pour que le marquee et le bouton soient harmonieux */
        .header-top 
    {
            background: #f8f9fa;
             padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        
        #formu
        {
               width: 400px;
              margin: 40px auto;
              padding: 20px;
              background: white;
              border-radius: 10px;
             box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        #exo2 h2 
        {
            text-align: center;
             margin-bottom: 20px;
        }


#exo2 label 
{
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

#exo2 input,
#exo2 select 
{
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border: 1px;
    border-radius: 5px;
}


#formu input[type="submit"] 
{
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    margin-left:30%;
}

#formu input[type="submit"]:hover
 {
    background-color:green;
}

#recherche
  {
       margin-left:35%;
  }
  #recherche input
  {
     height:10%;
  }
  
  img
     {
         border-radius:5px;
      }
  table
     {
          width:90%;
         margin:5% ;
         border-collapse:collapse;
        background:white;
      }

    th
      {
        background:#3498db;
         color:white;
        padding:10px;
     }

    td
    {
        padding:10px;
        text-align:center;
        border-bottom:1px solid #ddd;
    }

  </style>

</head>
<body>    
    

<h1 class="text-center my-4">Espace commerçant:</h1>
<div class="header-top mb-4">
    <div class="container d-flex align-items-center py-2">
        
        <marquee class="flex-grow-1 rounded-pill shadow-sm" 
                 style="background-color: #FFCC00; color: white; font-weight: bold; padding: 8px 15px; border: 1px solid #e6b800;">
                Gestion des biens
        </marquee>
        <a href="ParametreC.php" class="btn btn-dark ms-2">
            Parmetres⚙️
        </a>
        <a href="HistoriqueReserveCom.php" class="btn btn-primary ms-2">
            Historique des reservations
            </a>
    </div>
<section id="recherche"> 
    <form method="post">
    <input style="width:50%;" type="text" name="rechercher" placeholder="Nom ou type du bien immobilier">
     <button type="submit" class="btn btn-primary ms-2" value="rechercher">🔍</button>
    </form>
    <?php               
         //Récupération de la recherche
        
        $search = isset($_POST['rechercher']) ? $_POST['rechercher'] : '';

        if($search !='')
        {
            $req = $pdo->prepare("SELECT * FROM bien WHERE Nom LIKE ? OR Type LIKE ? ORDER BY Type, Nom");
            $req->execute(['%'.$search.'%','%'.$search.'%']);
        } 
        else
         {
            $req = $pdo->query("SELECT * FROM bien ORDER BY Type, Nom");
        }
           
      
?>
</section>

<section id="formu">
        <form method="post" enctype="multipart/form-data">
            <h2 id="Pro">Ajout d'un bien:</h2>
         <label> Nom :</label>
        <input type="text" name="nom" required><br><br>

        <label>Adresse :</label>
        <input type="text" name="adresse" required><br><br>

         <label>Type :</label>
        <select name="type">
            <option>Appartement</option>
            <option>Villa</option>
        </select>   <br><br>

        <label>Prix:</label>
        <input type="number" name="prix"  min="0"required><br><br>

          <label>Description:</label>
          <input type="text" name="description"><br><br>

            <label>Images:</label>
            <input type="file" name="images[]" multiple><br><br>

         <input type="submit" value="Ajouter" name="envoie">
    </form><br><br><br>
    </section>
    <section>
    <?php

    $req = $pdo->query("SELECT * FROM bien ORDER BY Type, Prix");
    echo "<h2 style='text-align:center;'>TABLEAU DES Biens ajoutés</h2>";
    echo "<table border='3' width='90%' style='margin:auto;'>

     <tr>
         <th>Image</th>
         <th>Nom</th>
         <th>Adresse</th>
         <th>Type</th>
         <th>Description</th>
         <th>Prix</th>
         <th>Actions</th>
     </tr>";
    while($B= $req->fetch())
    {
      $img = $pdo->prepare("SELECT url FROM photoBiens WHERE codeBien=? LIMIT 1");//l'image illustrative
   
      $img->execute([$B['idBien']]);
      $photo = $img->fetch();
    
        echo"<tr>";
       echo"<td>";
       if($photo)
       {
           echo"<a href='detailBienCOM.php?idBien=".$B['idBien']."'><img src='images/".$photo['url']."' width='80'></a>";
           echo"</td>";

           echo"<td>".$B['Nom']."</td>";
           echo"<td>".$B['Adresse_Position']."</td>";
           echo"<td>".$B['Type']."</td>";
           echo"<td>".$B['Description']."</td>";
           echo"<td>".$B['Prix']." FCFA</td>";
       }
          echo"<td>|<a href='detailBienCOM.php?idBien=".$B['idBien']."' class='btn btn-primary ms-1'>Detail</a>
                    |<a href='ModifBien.php?idBien=".$B['idBien']."' class='btn btn-warning ms-1' >Modifier</a>
                    |<a href='SupprimerBien.php?idBien=". $B['idBien']." ?>' 
                          onclick='return confirm('Supprimer ce bien ?');'
                        class='btn btn-danger'> Supprimer</a>
          </td>";
     echo"</tr>";
        
      }
        echo "</table><br><br><br>";
?>
</section>
</body>
    </html>