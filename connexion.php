<?php

include 'tools.php';

if (isset($_POST["submit"])) {

    if (
        !empty($_POST["email"]) &&
        !empty($_POST["password"])) {
        sanitize_array($_POST);
   
        $message = connexion($_POST);
    } else {
        $message = "Veuillez remplir les champs du formulaire";
    }
}


function connexion(array $user)
{
   
    try  {
    
        $bdd = connect_bdd();
      
        $sql = "SELECT u.id, u.email, u.firstname , u.lastname, u.password FROM app.users AS u WHERE u.email = ?";

        $req = $bdd->prepare($sql);
        $req->bindValue(1, $user["email"], PDO::PARAM_STR);
        $req->execute();

        $userSelected = $req->fetch(PDO::FETCH_ASSOC);

    } catch(Exception $e)
    {
        return "Le compte n'existe pas.";
    }

    if(password_verify($user['password'], $userSelected['password'])){
        
        session_start();
        $_SESSION['state'] = 'connnected';
        $_SESSION['firstname'] = $userSelected['firstname'];
        $_SESSION['lastname'] = $userSelected['lastname'];
        $_SESSION['email'] = $userSelected['email'];
        $_SESSION['id'] = $userSelected['id'];

        header('Location: ./profile.php');

    } else {
        return "Le mot de passe ou le compte est invalide.";
    }
    
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title>Connexion</title>
</head>
<body>
    
    <main class="container">
        <h1>Connexion</h1>
        <form action="" method="post">
            <fieldset>
                <input type="email" name="email" placeholder="Saisir votre email">
                <input type="password" name="password" placeholder="Saisir votre mot de passe">
            </fieldset>
            <input type="submit" value="Se connecter" name="submit">
        </form>
        <p><?= $message ?? ""?></p>
    </main>
</body>
</html>