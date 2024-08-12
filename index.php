<?php

include "model.php";
include "header.php";


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Create a PDO connection
    $pdo = createPDO();

    // Validate the credentials against the database
    $user = Utilisateurs::getUserByCredentials($pdo, $username, $password);

    if ($user) {
        // Store the username in the session
        $_SESSION['username'] = $username;
        // Redirect to the publications page
        header("location: publications.php");
        exit;
    } else {
        // Invalid credentials
        $error = "Invalid username or password";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-indigo.css"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-<?php echo isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'blue'; ?>.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <p>pour tester vous pouver ecrire :  username : Rebecca  et mdp : 1</p>
    <form action="publications.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button class = "w3-theme" type="submit">Login</button>
    </form>

</body>
</html>
