<?php

include 'tools.php'; 

function get_all_article(){
    $bdd = $bdd = connect_bdd();
    $sql = "SELECT a.id, a.title, a.content, a.created_at, c.name_category
            FROM app.article a
            JOIN app.article_category ac ON ac.id_article = a.id
            JOIN app.category c ON ac.id_category = c.id
            ";
    $req = $bdd->prepare($sql);
    $req->execute();

    return $req->fetchAll(PDO::FETCH_ASSOC);
}

$articles = get_all_article();

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title>Liste des articles</title>
</head>

<body>

    <main class="container-fluid">
        <h1>Liste des articles</h1>
        <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Created_at</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($articles as $article): ?>
            <tr>
                <td><?= $article['id'] ?></td>
                <td><?= $article['title'] ?></td>
                <td><?= $article['content'] ?></td>
                <td><?= $article['created_at'] ?></td>
                <td><?= $article['name_category'] ?></td>
            </tr>
            <?php endforeach; ?>
           
        </tbody>
    </table>
        
    </main>
</body>

</html>