<?php 


session_start();

if(isset($_SESSION['state'])){
    deconnexion();
}
   
function deconnexion(){
    session_destroy();
    header('Location: ./connexion.php');
}
