<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Agence</title>
  <link rel="stylesheet" href="style2.css"/>
</head>
<body>

<form method="POST" action="traitement.php" enctype="multipart/form-data" autocomplete="off">

 <!-- piège navigateur (anti autofill) -->
        <input type="text" name="fake_user" style="display:none" autocomplete="username">
        <input type="password" name="fake_pass" style="display:none" autocomplete="new-password">
  <h1>Fiche des Commerciaux</h1>

  <div>
    <label for="Nom">Nom:</label>
    <input type="text" id="Nom" name="Nom" required autocomplete="off">
  </div>

  <div>
    <label for="Prenom">Prénom:</label>
    <input type="text" id="Prenom" name="Prenom" required autocomplete="off">
  </div>

  <div>
    <label for="Adresse">Email:</label>
    <input type="email" id="Adresse" name="Adresse" required autocomplete="off">
  </div>

  <div>
    <label for="mot_de_passe">Mot de passe:</label>
    <input type="password" id="mot_de_passe" name="mot_de_passe" required autocomplete="off">
  </div>

  <div>
    <label for="genre">Genre:</label>
    <select id="genre" name="genre" required>
      <option value="Homme">Homme</option>
      <option value="Femme">Femme</option>
    </select>
  </div>

  <div>
    <label for="Telephone">Numéro:</label>
    <input type="text" id="Telephone" name="Telephone" autocomplete="off">
  </div>

  <div>
    <label for="Age">Âge:</label>
    <input type="number" id="Age" name="Age" required>
  </div>

  <div>
    <label for="Profession">Profession:</label>
    <input type="text" id="Profession" name="Profession" autocomplete="off">
  </div>

  <div>
    <label for="photo">Photo:</label>
    <input type="file" name="image" required>
  </div>

  <!-- Champ caché -->
  <input type="hidden" name="choix" value="Commercant">

  <br>
  <button type="submit">S'inscrire🤝</button>
  
  <br>
  
  <a href="Inscription.php">← Retour</a>

</form>

</body>
</html>
