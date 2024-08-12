<?php

session_start();
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



    // Create PDO instance
    $pdo = createPDO();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//utilite function 

function createPDO() {
    $servername = "localhost";
    $username = "uapv2300382";
    $password = "wdMl44";
    $dbname = "etd";
    $pdo = new PDO("pgsql:host=$servername;dbname=$dbname", $username, $password);
  
    return $pdo;
  }
  

class Utilisateurs{
    public $id;
    public $pseudo;
    public $naissance ;
    public $con;


    public function __construct(PDO $conn) {
        $this->con = $conn;
    }


    public function __toString() {
        // Formater la publication comme une ligne de tableau HTML
        return "<tr>
                    <td>{$this->id}</td>
                    <td>{$this->pseudo}</td>
                    <td>{$this->naissance}</td>
                

                </tr>";
    }



    public static function getUserByCredentials(PDO $conn, $username, $password) {
        // Prepare a secure SQL statement to retrieve user information based on username and password
        $stmt = $conn->prepare('SELECT * FROM utilisateurs WHERE pseudo = :username AND id = :password');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Fetch the user from the database
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}


  

  class Publication{
    public $id;
    public $auteur;
    public $categorie;
    public $contenu ;
  
    public  $con ;//connection atrebute 
  
    ///constructeur de la connection 
        public function __construct(PDO $conn) {
            $this->con = $conn;
        }
    
    
        public function __toString() {
            // Format the publication as a table row in HTML
            return "<form action='publications.php' method='post'>
                        <tr>
                            <td>{$this->id}</td>
                            <td>{$this->contenu}</td>
                            <td>{$this->auteur}</td>
                            <td><a href='publicationsCategories.php?categorie={$this->categorie}'>{$this->categorie}</a></td>
                            <td>
                                <input type='hidden' name='publicationId' value='{$this->id}'>
                                <input type='hidden' name='auteurId' value='{$this->auteur}'>
                                <button type='submit'>Like</button>
                            </td>
                        </tr>
                    </form>";
        }
        






        public function addModifyPub( $ID, $content,  $author,  $category) {
           // con doit etre initialiser direction pour connecter a ma base donc il n'est  pas null 
            // Vérifier si la publication existe déjà
            $stmt = $this->con->prepare('SELECT * FROM publications WHERE id = :ID');
            $stmt->execute(['ID' => $ID]);
            $publication = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($publication) {
                // Mettre à jour la publication existante
                $stmt = $this->con->prepare('UPDATE publications SET contenu = :content, auteur = :author, categorie = :category WHERE id = :ID');
            } else {
                // Créer une nouvelle publication
                $stmt = $this->con->prepare('INSERT INTO publications (id, contenu, auteur, categorie) VALUES (:ID, :content, :author, :category)');
            }
    
            // Exécuter la requête avec les valeurs fournies
            $stmt->bindParam(':ID', $ID, PDO::PARAM_INT);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':author', $author, PDO::PARAM_STR);
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
            $stmt->execute();
    
            // Vérifier si la requête a réussi
            if ($stmt->rowCount() > 0) {
                echo "you made it ";
                return true; // Succès
            } else {
                echo " try again ";
                return false; // Échec
            }
        }
    }
  
    



class Categoreis {
public $id;
public $categorie;


public function __toString() {
    // Formater la Categorie comme une ligne de tableau HTML
    return "<tr>
                <td>{$this->id}</td>
                <td>{$this->categorie}</td>
            </tr>";
}

public function __construct()
{
$this->categorie = "{$this->id} this categorie  is : {$this->categorie}";
}
}




    class Votes extends Publication{// la conncetion de la publication va etre herite par le vote 
    
            public $idv;
            
            public function __construct(PDO $conn) {
                parent::__construct($conn);
            }
            
            
            public function __toString() {
                // Formater la publication comme une ligne de tableau HTML
                //le premier id c'est l'id de la pub le deuxieme c'est id de vote 
                return "<tr><td>{$this->id}</td> 
                            <td>{$this->idv}</td>
                            <td>{$this->auteur}</td>
                        </tr>";}
         

            public function addVote($user, $pub) {
                try {
                    // Check if the user has already voted for this publication
                    $stmt = $this->con->prepare('SELECT COUNT(*) FROM votes WHERE utilisateur = :user AND publication = :pub');
                    $stmt->bindParam(':user', $user, PDO::PARAM_STR);
                    $stmt->bindParam(':pub', $pub, PDO::PARAM_STR);
                    $stmt->execute();
                    $existingVotes = $stmt->fetchColumn();
            
                    if ($existingVotes > 0) {
                        // User has already voted for this publication
                        echo "You have already voted for this publication.";
                        return false; // Failure
                    } else {
                        // Prepare the SQL statement to add the vote
                        $stmt = $this->con->prepare('INSERT INTO votes(utilisateur, publication) VALUES (:user, :pub)');
                        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
                        $stmt->bindParam(':pub', $pub, PDO::PARAM_STR);
                        $stmt->execute();
            
                        // Check if the row was inserted successfully
                        if ($stmt->rowCount() > 0) {
                            echo "You made it! A new vote is added.";
                            return true; // Success
                        } else {
                            echo "Try again.";
                            return false; // Failure
                        }
                    }
                } catch(PDOException $e) {
                    // Handle PDO exceptions
                    echo "Error adding vote: " . $e->getMessage();
                    return false;
                }
            }
            
                        
        

        }





?>
