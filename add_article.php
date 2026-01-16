<?php

include 'tools.php';

session_start();

if(isset($_SESSION['id'])){

    $user = getUser($_SESSION['id']);
    $categories = getAllCategories();
    
    
    if (isset($_POST["submit"])) {
        if (
            !empty($_POST["title"]) &&
            !empty($_POST["content"]) &&
            !empty($_POST["category"])
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

        $sql = "INSERT INTO app.article(title, content, created_at, id_users) VALUE(?,?,?,?)";

        $req = $bdd->prepare($sql);

        $req->bindValue(1, $article["title"], PDO::PARAM_STR);
        $req->bindValue(2, $article["content"], PDO::PARAM_STR);
        $req->bindValue(3, $article['created_at'], PDO::PARAM_STR);
        $req->bindValue(4, $user['id'], PDO::PARAM_INT);

        $req->execute();

        $id_article = $bdd->lastInsertId(); 
        $cat = intval($article['category']);

       add_category_to_article($id_article, $cat);
            
    } catch (PDOException $e) {
        return "L'article n'a pas pu être ajouté correctement.";
    }
    return "L'article a été ajouté.";
}

function add_category_to_article(int $id_article, int $id_cat){
    try{
        $bdd = connect_bdd();
        $sql = "INSERT INTO article_category(id_article, id_category) VALUES (? , ?)";
        $req = $bdd->prepare($sql);
    
        $req->bindValue(1, $id_article, PDO::PARAM_INT);
        $req->bindValue(2, $id_cat, PDO::PARAM_INT);
    
        $req->execute();
    } catch (PDOException $e){
        dump($e->getMessage());
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
    <title>Ajouter un article</title>
</head>

<body>

    <main class="container">
        <h1>Ajouter un article</h1>
        <form action="" method="post">
            <fieldset>
                <select name="category">
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id']?>" ><?= $cat['name_category'] ?></option>
                    <?php endforeach; ?>
                </select>
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