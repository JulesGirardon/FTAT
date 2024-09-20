<?php


$message = "";
$message_class = "";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ftat"; //database nam
try {
    $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $message = "Échec de la connexion :( " . $e->getMessage();
    $message_class = "error";
}
?>