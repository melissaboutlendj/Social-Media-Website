<?php
include "db_connection.php";
include('model.php');
include "footer.php";
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



// Récupérer la catégorie depuis l'URL
$categoryId = isset($_GET['categorie']) ? $_GET['categorie'] : null;

echo "$categoryId ";

// Vérifier si l'identifiant de la catégorie est défini
if ($categoryId !== null) {
    try {
        // Préparer la requête SQL pour récupérer les publications de la catégorie spécifiée
        $sql = "SELECT id, contenu, auteur, categorie FROM publications WHERE categorie = :categoryId";

        // Préparer la requête SQL
        $stmt = $conn->prepare($sql);

        // Liaison des paramètres
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);

        // Exécuter la requête SQL
        $stmt->execute();

        // Vérifier si des publications ont été trouvées
        if ($stmt->rowCount() > 0) {
            ?>

            <body>
            <h1 class="w3-theme" #f2f2f2>Publications</h1>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Contenu</th>
                    <th>Auteur</th>
                    <th>Catégorie</th>
                </tr>
                <?php
                // Pour chaque ligne de résultat, créer un objet Publication et l'afficher
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $publication = new Publication($conn);
                    $publication->id = $row['id'];
                    $publication->contenu = $row['contenu'];
                    $publication->auteur = $row['auteur'];
                    $publication->categorie = $row['categorie'];
                    echo $publication;
                }
                ?>
            </table>
          <?php

        } else {
            echo "<tr><td colspan='4'>Aucune publication trouvée pour cette catégorie.</td></tr>";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de l'exécution de la requête SQL : " . $e->getMessage();
    }
} else {
    echo "<tr><td colspan='4'>Aucune catégorie spécifiée.</td></tr>";
}
?>
