<?php
session_start();
include "../includes/connexionBDD.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = $_POST["name"];
    $firstname = $_POST["firstname"];
    $mail = $_POST["mail"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $spe = $_POST["specialite"];
    $statut = $_POST["statut"];


    //Vérification mail
    $sql = "SELECT COUNT(*) FROM utilisateurs WHERE mail = :mail";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([":mail" => $mail]);
    $email_exists = $stmt->fetchColumn();

    if ($email_exists > 0) {
        $_SESSION['error'] = 'email_use';
        header("Location: ../pages/signin.php");
        exit();
    }
    //Vérification des password
    if ($password != $confirm_password){
        $_SESSION['error'] = 'mdp_not_same';
        header("Location: ../pages/signin.php");
        exit();
    }
    //Si bon : on hache le password
    else{
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    //Ajout de l'utilisateur 

    $sql = "INSERT INTO utilisateurs (NomU, PrenomU, MotDePasseU, SpecialiteU, mail, statut) VALUES (:nom, :prenom, :password, :spe, :mail, :statut)";

    $stmt = $bdd->prepare($sql);
    $stmt->execute([
    ':nom' => $name,
    ':prenom' => $firstname,
    ':password' => $hashed_password,
    ':spe' => $spe,
    ':mail' => $mail,
    ':statut' => $statut]);
    header("Location: ../index.php");
    exit();
}