<?php

include "db_connection.php";
include "model.php";
include "header.php";

$servername = "localhost";
$username = "uapv2300382";
$password = "wdMl44";

try {
    // Establish the database connection
    $conn = new PDO("pgsql:host=$servername;dbname=etd", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optionally, you can output a message indicating successful connection
    // echo "Connected successfully";
} catch(PDOException $e) {
    // Handle connection error
    echo "Connection failed: " . $e->getMessage();
}


$userId = isset($_GET['id']) ? $_GET['id'] : null;
echo $userId;

//$sql = "SELECT id , contenu, auteur , categorie FROM utilisateurs as u natural join publications as p where  u.id = :userId ORDER BY categorie ";

$userId = isset($_GET['id']) ? $_GET['id'] : null;

$sql = "SELECT p.id, p.contenu, p.auteur, p.categorie, COUNT(DISTINCT v.utilisateur) AS reaction
FROM publications AS p
INNER JOIN utilisateurs AS u ON p.auteur = u.id
LEFT JOIN votes AS v ON p.id = v.publication
WHERE u.id = :userId
GROUP BY p.id, p.contenu, p.auteur, p.categorie
ORDER BY p.categorie ASC, reaction DESC";

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

// Bind the user ID parameter
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

// Execute the query
$stmt->execute();




?>




<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-indigo.css"> 
<title>Tableau de publication </title>
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
  </style>
</head>
<body>
    <h1 class="w3-theme">Publications</h1>
    <table>
  <tr>
    <th>ID</th>
    <th>Conetu</th>
    <th>auteur</th>
    <th>categorie</th>
    <th class="addCol">Reaction</th>
    <th>Liker</th>
  </tr>
 

    <?php


  if ($stmt->rowCount() > 0) {
      // output data of each row
  
      // Pour chaque ligne de résultat, afficher les détails de la publication
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $publication2 = new Publication($conn);
          $publication2->id = $row['id'];
          $publication2->contenu = $row['contenu'];
          $publication2->auteur = $row['auteur'];
          $publication2->categorie = $row['categorie'];
  
          // Afficher les détails de la publication dans une seule ligne de tableau
          echo "<tr class='zoom-effect'>";
          echo "<td>{$publication2->id}</td>";
          echo "<td>{$publication2->contenu}</td>";
          echo "<td>{$publication2->auteur}</td>";
          echo "<td>{$publication2->categorie}</td>";
          echo "<td class='addCol'>{$row["reaction"]}</td>"; // Ajout de la colonne "Reaction"
          echo "<td>";
          echo "<form action='profil.php' method='post'>";
          echo "<input type='hidden' name='publicationId' value='{$publication2->id}'>";
          echo "<input type='hidden' name='auteurId' value='{$publication2->auteur}'>";
          echo "<button type='submit'>Like</button>";
          echo "</form>";
          echo "</td>";
          echo "</tr>";
      }
  }




  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // creat an instance of the class 
    $pdo =  createPDO() ;
   // Check if user_id and publication_id are set in $_POST
   if (isset($_POST['auteurId']) && isset($_POST['publicationId'])) {
       $auteur = $_POST['auteurId'];
       $pub = $_POST['publicationId'];
       $vote = new Votes($pdo);

       if ($vote->addVote($auteur, $pub)) {
           echo "Vous avez liké la pub avec l'ID : $pub";
       } else {
           echo "Échec de l'ajout du like";
       }
   } else {
       echo "Missing parameters.";
   }
}





  
  ?>
  


</table>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $(".button").click(function(){
    $(".addCol").toggle();
  });
});
</script>
</head>
<body>
<br>
<br>
<br>
<button class="w3-theme">Show reactions </button>

<button id="btn-back" class="w3-theme"> back to user Table </button>
  <br>
<br>
<br>
  <script> 

  
   var btnBack = document.getElementById("btn-back");
   //add click envent listener to the button 
   btnBack.addEventListener('click',function(){window.location.href="utilisateur.php";});
   </script>
   <style>
    .zoom-effect {
        transition: transform 0.2s; /* Ajoute une transition fluide */
    }

    .zoom-effect:hover {
        transform: scale(1.1); /* Applique un effet de zoom au survol */
    }
</style>

</body>
</html>