<?php

use Dotenv\Dotenv;

include 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function sanitize(string $str): string
{
    //Supprimer les espaces devant
    $str = trim($str);
    //Supprimer les balises html
    $str = strip_tags($str);
    //supprimer des caractères
    $str = htmlspecialchars($str, ENT_NOQUOTES);
    return $str;
}

function connect_bdd(): PDO
{
    return new PDO(
        'mysql:host=' . $_ENV['DB_HOST'] .
        ';dbname=' . $_ENV['DB_NAME'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
}

function sanitize_array(array &$data): void 
{
    foreach ($data as $key => $value) {
        //Test si la colonne n'est pas un tableau
        if (gettype($value) != 'array')
        {
            $data[$key] =  sanitize($value);
        } 
    }
}

function getFileExtension($file)
{
    return substr(strrchr($file, '.'), 1);
}

function uploadFile(array $file, string $username)  {

        $name = $file["name"];
        $temp = $file["tmp_name"];
    
        $ext =  strtolower(getFileExtension($name));
 
        if ($ext == "png" || $ext == "jpg" || $ext == "jpeg") {

            if ($file["size"] > (1024*1204)) {
                echo "le fichier est trop grand";
            } else {
                $new_name = uniqid("fichier", true);
                $new_name = "public/" . $username . "." . $ext;
                move_uploaded_file($temp, $new_name);
                return $username . "." . $ext;
            }
        } else {
            echo "Le format est invalide";
        }
}

