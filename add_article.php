<?php

include 'tools.php';

session_start();

if(isset($_SESSION['id'])){

    $user = getUser($_SESSION['id']);
    
    if (isset($_POST["submit"])) {
        if (
            !empty($_POST["title"]) &&
            !empty($_POST["content"])
        ) {
            sanitize_array($_POST);
            $message = add_article($_POST, $user);
        } else {
            $message = "Veuillez remplir les champs du formulaire";
        }
    }
} else {
    header('Location: ./connexion.php');
    exit();
}


function add_article(array $article, array $user)
{
    try {

        $bdd = connect_bdd();

        $sql = "INSERT INTO article(title, content, created_at, id_users) VALUE(?,?,?,?)";

        $req = $bdd->prepare($sql);

        $req->bindValue(1, $article["title"], PDO::PARAM_STR);
        $req->bindValue(2, $article["content"], PDO::PARAM_STR);
        $req->bindValue(3, $article['created_at'], PDO::PARAM_STR);
        $req->bindValue(4, $user['id'], PDO::PARAM_INT);

        $req->execute();
    } catch (PDOException $e) {
        return "L'article n'a pas pu être ajouté correctement.";
    }
    return "L'article a été ajouté.";
}
?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title>Ajouter un article</title>
</head>

<body>

    <main class="container">
        <h1>Ajouter un article</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <fieldset>
                <input type="text" name="title" placeholder="Saisir le titre">
                <textarea  name="content" placeholder="Saisir le contenu"></textarea>
                <input type="datetime-local" name="created_at">
            </fieldset>
            <input type="submit" value="Ajouter" name="submit">
        </form>
        <p><?= $message ?? "" ?></p>
    </main>
</body>

</html>