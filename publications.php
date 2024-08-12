<?php


include "model.php";
include "header.php";


$color = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'blue';


  $sql = "SELECT id, contenu, auteur, categorie FROM publications ORDER BY categorie";
  $stmt = $conn->query($sql);
  // Affichage du tableau HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-indigo.css"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-<?php echo isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'blue'; ?>.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<head>
  <title>Tableau de publication</title>
  <!-- Styles CSS ici -->

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
  .addCol {
    display: none;
  }
 </style>




</head>


<body>
  <h1 class="w3-theme">Publications</h1>



   
    <button class="w3-theme" onclick="window.location.href='utilisateur.php'">Voir les utilisatuers</button>
    <br> <br>  <br> 
    <br>
  <table>
      <tr>
          <th>ID</th>
          <th>Contenu</th>
          <th>Auteur</th>
          <th>Catégorie</th>
          <th>Reactions</th>
        
      </tr>
      <?php
      // Pour chaque ligne de résultat, créer un objet Publication et l'afficher
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $publication2 = new Publication( $conn );
          $publication2->id = $row['id'];
          $publication2->contenu = $row['contenu'];
          $publication2->auteur = $row['auteur'];
          $publication2->categorie = $row['categorie'];
          echo $publication2;
      }
      ?>
  </table>



  <?php
// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"]) && isset($_GET["content"]) && isset($_GET["author"]) && isset($_GET["category"])) {
    // Retrieve form data
    $ID = $_GET["id"];
    $content = $_GET["content"];
    $author = $_GET["author"];
    $category = $_GET["category"];


    // Create Publication instance
    $publication = new Publication($pdo);

    // Call addModifyPub method
    if ($publication->addModifyPub($ID, $content, $author, $category)) {
        echo "Publication ajoutée ou modifiée avec succès.";
    } else {
        echo "Échec de l'ajout ou de la modification de la publication.";
    }
} else {
    // Form not submitted or missing data, display the form
?>
</br>
<h3 class="w3-theme">Add or Modify Publication</h3>
</br>
</br>
<form action="publications.php" method="get">
    <label for="id">Publication ID:</label><br>
    <input type="number" id="id" name="id" required><br><br>

    <label for="content">Content:</label><br>
    <textarea id="content" name="content" rows="4" cols="50" required></textarea><br><br>

    <label for="author">Author:</label><br>
    <input type="text" id="author" name="author" required><br><br>

    <label for="category">Category:</label><br>
    <input type="text" id="category" name="category" required><br><br>
    <input class="w3-theme" type="submit" value="Submit">
</form>
<?php
}
?>


    <?php








  


// gerer les likes 

           

                
if ($_SERVER["REQUEST_METHOD"] == "POST") {
               // creat an instance of the class 
          
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

<br>
<br>
<br>


<button class="w3-theme" onclick="window.location.href='index.php'">Deconnexion</button>

<br>
<br>
<br>

<br>
<br>
<br>

<br>
<br>
<br>

<script> 
  
var btnBack = document.getElementById("btn-dec");
//add click envent listener to the button 
btnBack.addEventListener('click',function(){window.location.href="login.php";});
</script>

</body>
</html>
