<?php

include 'tools.php';
include 'vendor/autoload.php';


if (isset($_POST["submit"])) {

    if (!empty($_POST["category"])) {
        sanitize_array($_POST);
   
        $message = add_category($_POST);
    } else {
        $message = "Veuillez remplir les champs du formulaire";
    }
}


function add_category(array $cat)
{
    try  {
    
        $bdd = connect_bdd();

        $checkSQL = "SELECT c.name_category FROM app.category AS c WHERE c.name_category = ?";
        $checkReq =  $req = $bdd->prepare($checkSQL);
        $checkReq->bindValue(1, $cat["category"], PDO::PARAM_STR);
        $checkReq->execute();
        $check = $checkReq->fetch(PDO::FETCH_ASSOC);


        if(empty($check)){
            $sql = "INSERT INTO category(name_category) VALUE(?)";
            
            $req = $bdd->prepare($sql);

            $req->bindValue(1, $cat["category"], PDO::PARAM_STR);
            $req->execute();

            return "La catégorie a bien été ajoutée";
        } else {
            return "La catégorie existe déjà.";
        }
    
    } catch(Exception $e)
    {
        return $e."Erreur lors de l'ajout de la catégorie.";
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
    <title>Ajouter une catégorie</title>
</head>
<body>
    
    <main class="container">
        <h1>Ajouter une catégorie</h1>
        <form action="" method="post">
            <fieldset>
                <input type="text" name="category" placeholder="Nom de la catégorie">
            </fieldset>
            <input type="submit" value="Ajouter" name="submit">
        </form>
        <p><?= $message ?? ""?></p>
    </main>
</body>
</html>