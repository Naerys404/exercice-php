<?php 


session_start();

if(isset($_SESSION['email'])){
    deconnexion();
}
   
function deconnexion(){
    session_destroy();
    header('Location: ./connexion.php');
}
