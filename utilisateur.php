<?php
//etablire la connection a la base de donnees
include "db_connection.php";
include "header.php";
include "footer.php";


$dbname = "etd";
$sql = "SELECT id , pseudo , naissance FROM utilisateurs  ";
$result = $conn->query($sql);




?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-indigo.css"> 
<title>Tableau des utilisateurs</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    h1 {
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      border: 1px solid #ddd;
    }
    th, td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #f2f2f2;
    }
    tr:nth-child(even) {
      background-color: #f2f2f2;
    }
    tr:hover {
        background-color: #ddd;
    }
    /* Définir le style pour l'effet de survol */
    .user-hover:hover td:nth-child(2) 

    {  color: blue; }
      
  </style>
</head>
<body>
    <h1 class="w3-theme">Tableau des utilisatuers </h1>
    
    <table>
  <tr>
    <th>ID</th>
    <th>Pseudo</th>
    <th>naissance</th>
    <th>Profile</th>
  </tr>
 

    <?php

    if ($result->rowCount() > 0) {
    // output data of each row
        while ($row = $result->fetch(PDO::FETCH_ASSOC))
     { ?>

    <tr class="user-hover">
    <td> <?php echo $row["id"]?> </td>
    <td> <?php echo $row["pseudo"] ?></td>
    <td> <?php echo $row["naissance"] ?></td>
    <td><a href='profil.php?id=<?php echo $row["id"]; ?>' class='btn-profile w3-theme'>voir profile</a>;</td>
    <?php
     }
  } else {
    echo "0 results";
  }
 
  ?>

<button class="w3-theme" onclick="window.location.href='publications.php'">revenir au publications </button>
  
  <script>
// Get all elements with the class 'btn-profile'
var btnProfiles = document.querySelectorAll(".btn-profile");

// Add click event listeners to each button
btnProfiles.forEach(function(btn) {
    btn.addEventListener('click', function() {
        // Get the user ID from the 'data-userid' attribute of the clicked button
        var userId = this.getAttribute('data-userid');
        
        // Redirect to the profile page with the user ID as a URL parameter
        window.location.href = "profile.php?id=" + userId;
    });
});


/*requets:
1- l'ensemble des publications avec leur catégorie et le pseudo de leur auteur
select auteur, categorie, pseudo from publications natural join utilisateurs;


2-l'ensemble des publications classées par leur popularité (nombre de votes) 
/* je pense cel la n'est pas correct 
select publication from votes as v natural join publications as p where p.id= v.id ORDER BY v.publication ;
 publication /?

2-select publication ,count(utilisateur) from votes GROUP BY publication ORDER BY count(utilisateur) ASC;

3-l'ensemble des utilisateurs ainsi que leurs publications
select id, pseudo   from utilisateurs natural join publications where utilisateurs.id=publications.id;

4-l'ensemble des utilisateurs ainsi que leurs catégories de publication
select id , categorie from utilisateurs as u natural join publications as p where u.id=p.id;
5-l'ensemble des utilisateurs classés par leur popularité (nombre de votes sur l'ensemble de leurs publications)
SELECT u.id, u.pseudo, COUNT(v.id) AS total_reactions
FROM utilisateurs u
LEFT JOIN publications p ON u.id = p.auteur
LEFT JOIN votes v ON p.id = v.publication
GROUP BY u.id, u.pseudo
ORDER BY total_reactions DESC;

6-l'ensemble des catégories avec les pseudo des utilisateurs publiant dans chaque catégorie
SELECT c.categorie, u.pseudo
FROM categories c
LEFT JOIN publications p ON c.id = p.categorie
LEFT JOIN utilisateurs u ON p.auteur = u.id
ORDER BY c.categorie;

*/
















</script>


  </table>

  <button class="w3-theme" onclick="window.location.href='publications.php'">revenir au publications </button>
  <br>
  <br>
  <br>
  <br>
  <br>

</body>
</html>

