<?php

include 'tools.php';

if (isset($_POST["submit"])) {

    if (
        !empty($_POST["firstname"]) &&
        !empty($_POST["lastname"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["password"]) 
        
    ) {
        sanitize_array($_POST);

        $_POST["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);

        if (isset($_FILES["fichier"]) && !empty($_FILES["fichier"]["tmp_name"])) {
           $file = uploadFile($_FILES['fichier'], $_POST['firstname']);
        } else {
           $file = "default.webp";
        }

        $message = add_user($_POST, $file);
    } else {
        $message = "Veuillez remplir les champs du formulaire";
    }
}


function add_user(array $user, string $file)
{
    $role = "utilisateur";
    try {

        $bdd = connect_bdd();

        $sql = "INSERT INTO users(firstname, lastname, email,`password`, roles, img) VALUE(?,?,?,?,?,?)";

        $req = $bdd->prepare($sql);

        $req->bindValue(1, $user["firstname"], PDO::PARAM_STR);
        $req->bindValue(2, $user["lastname"], PDO::PARAM_STR);
        $req->bindValue(3, $user["email"], PDO::PARAM_STR);
        $req->bindValue(4, $user["password"], PDO::PARAM_STR);
        if (isset($user['roles']) && $user['roles'] === 'on') {
            $req->bindValue(5, 'ROLE_ADMIN', PDO::PARAM_STR);
        } else {
            $req->bindValue(5, 'ROLE_USER', PDO::PARAM_STR);
        }
        $req->bindValue(6, $file, PDO::PARAM_STR);
        $req->execute();
    } catch (Exception $e) {
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
        <form action="" method="post" enctype="multipart/form-data">
            <fieldset>
                <input type="text" name="firstname" placeholder="Saisir votre prénom">
                <input type="text" name="lastname" placeholder="Saisir votre nom">
                <input type="email" name="email" placeholder="Saisir votre email">
                <input type="password" name="password" placeholder="Saisir votre mot de passe">
                <input type="file" name="fichier">

                <label for="roles">
                    Cocher pour rôle admin
                    <input type="checkbox" id="roles" name="roles" aria-label="Rôle administrateur">
                </label>
            </fieldset>
            <input type="submit" value="Ajouter" name="submit">
        </form>
        <p><?= $message ?? "" ?></p>
    </main>
</body>

</html>