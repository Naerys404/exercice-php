<?php session_start();
    if(!isset($_SESSION['email'])){
        header('Location: ./connexion.php');}
    ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title>Mon profil</title>
</head>
<body>
    
    <main class="container">
        <h1>Mon profil</h1>
    
        <article>
            <h2>Bienvenue <?= $_SESSION['firstname']." ".$_SESSION['lastname'] . "." ?></h2>
            <h3>Email: <?= $_SESSION['email'] ?></h3>
            <a href="deconnexion.php"><button>Se déconnecter</button></a>
        </article>
    </main>
</body>
</html>