<?php

include 'tools.php';

if (isset($_POST["submit"])) {

    if (
        !empty($_POST["firstname"]) && 
        !empty($_POST["lastname"]) && 
        !empty($_POST["email"]) &&
        !empty($_POST["password"])) {
        sanitize_array($_POST);
   
        $_POST["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $message = add_user($_POST);
    } else {
        $message = "Veuillez remplir les champs du formulaire";
    }
}


function add_user(array $user)
{
    $role = "utilisateur";
    try  {
    
        $bdd = connect_bdd();
      
        $sql = "INSERT INTO users(firstname, lastname, email,`password`, roles) VALUE(?,?,?,?,?)";
 
        $req = $bdd->prepare($sql);

        $req->bindValue(1, $user["firstname"], PDO::PARAM_STR);
        $req->bindValue(2, $user["lastname"], PDO::PARAM_STR);
        $req->bindValue(3, $user["email"], PDO::PARAM_STR);
        $req->bindValue(4, $user["password"], PDO::PARAM_STR);
        $req->bindValue(5, $role, PDO::PARAM_STR).
        $req->execute();
    } catch(Exception $e)
    {
        return "Le compte existe déja";
    }
    return "Le compte à été ajouté en BDD";
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title>Ajouter un compte</title>
</head>
<body>
    
    <main class="container">
        <h1>Ajouter un compte</h1>
        <form action="" method="post">
            <fieldset>
                <input type="text" name="firstname" placeholder="Saisir votre prénom">
                <input type="text" name="lastname" placeholder="Saisir votre nom">
                <input type="email" name="email" placeholder="Saisir votre email">
                <input type="password" name="password" placeholder="Saisir votre mot de passe">
            </fieldset>
            <input type="submit" value="Ajouter" name="submit">
        </form>
        <p><?= $message ?? ""?></p>
    </main>
</body>
</html>