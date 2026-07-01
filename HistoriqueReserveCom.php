<?php
session_start();
require("Connect.php");
require("functions.php");

    $codeCli = $_SESSION['codeCli'];
  
    if(!isset($_SESSION["codeCom"]))
    {
        header("Location: Prépare.php");
        exit();
    }



        if(isset($_POST["envoi_reserver"]))
        {

            $codeBien = $_POST["envoi_reserver"];

            // vérifier si déjà dans historique
            $check = $pdo->prepare("SELECT * FROM historique  WHERE codeBien=? AND codeCli=?");
            $check->execute([$codeBien, $codeCli]);

            if($check->rowCount() > 0) 
            {
            // supprimer historique
                $sup = $pdo->prepare("DELETE FROM historique  WHERE codeBien=? AND codeCli=?");
                $sup->execute([$codeBien, $codeCli]);

            }
            else 
            {

                // ajouter historique
                $res = $pdo->prepare(" SELECT id FROM reservation  WHERE codeBien=? AND codeCli=?  ORDER BY id DESC LIMIT 1");
                $res->execute([$codeBien, $codeCli]);
                $reservation = $res->fetch();
    
            if($reservation)
               {
                $ins = $pdo->prepare("INSERT INTO historique(codeBien, codeCli, codeReservation) VALUES(?,?,?)");
                $ins->execute([$codeBien, $codeCli, $reservation['id']]);
              }
                
            }

            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
        
        $req = $pdo->prepare("SELECT h.*, b.Nom, b.Type, b.Prix, b.Adresse_Position,
                             (SELECT url FROM photoclients pc  WHERE pc.codeClient = c.idCli LIMIT 1) AS photoClient,
                                 (SELECT url FROM photobiens pb WHERE pb.codeBien = b.idBien LIMIT 1) 
                             AS photoBien FROM historique h JOIN bien b ON h.codeBien = b.idBien
                              JOIN client c ON h.codeCli = c.idCli WHERE h.codeCli = ? ORDER BY h.date_de_reservation DESC");
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


    function dislikeBien(id)
    {

        fetch("dislike.php?id=" + id)
        
    
        .then(response => response.text())
        

        .then(data => 
        {
            
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
            🔥 DERNIÈRES OPPORTUNITÉS : Villa Dakar Prestige, Villa Ashy et bien d'autres à découvrir dès maintenant !
        </marquee>

             <a href="PageCommercant.php" class="btn btn-dark ms-3">
                Retour
                </a>
            </div>
        </div>
<?php

    echo "<h2 style='text-align:center;'>Historique des réservations</h2>";

    echo "<table border='1' cellpadding='10' cellspacing='0' width='95%' style='margin:auto; text-align:center;'>";

    echo "<tr style='background:#ddd;'>
        <th>Image Client</th>
        <th>Image Bien</th>
        <th>Code Client</th>
        <th>Nom Bien</th>
        <th>Type</th>
        <th>Adresse</th>
        <th>Prix</th>
        <th>Code Reservation</th>
        <th>Date de reservation</th>
        <th>Action</th>
    </tr>";

        while($B = $req->fetch())
        {
            echo "<tr>";

            
            echo "<td>";
            if($B['photoClient'])
            {
                echo "<img src='imageP/".htmlspecialchars($B['photoClient'])."' width='70'>";
            }
            else
            {
                echo "Aucune";
            }
            echo "</td>";

        
            echo "<td>";
            if($B['photoBien'])
            {
                echo "<img src='images/".htmlspecialchars($B['photoBien'])."' width='70'>";
            }
            else
            {
                echo "Aucune";
            }
            echo "</td>";

        
            echo "<td>".$B['codeCli']."</td>";
            echo "<td>".htmlspecialchars($B['Nom'])."</td>";
            echo "<td>".htmlspecialchars($B['Type'])."</td>";
            echo "<td>".htmlspecialchars($B['Adresse_Position'])."</td>";
            echo "<td>".number_format($B['Prix'],0,',',' ')." FCFA</td>";

        
            echo "<td>".($B['codeReservation'] ? $B['codeReservation'] : "NULL")."</td>";

    
            echo "<td>".$B['date_de_reservation']."</td>";


            echo "<td>
                <form method='POST'>
                 <button name='envoi_reserver' value='".$B['codeBien']."'onclick=\"return confirm('Supprimer ?');\">
                        Supprimer
                    </button>
                     </form>
                  </td>";

            echo "</tr>";
        }

        echo "</table>";

?>