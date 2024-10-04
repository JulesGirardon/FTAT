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

    if (isset($bdd)) {
        //Vérification mail
        $sql = "SELECT COUNT(*) FROM ftat.utilisateurs WHERE mail = :mail";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':mail', $mail);
        $stmt->execute();
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

        $sql = "INSERT INTO ftat.utilisateurs (NomU, PrenomU, MotDePasseU, SpecialiteU, mail, statut) VALUES (:nom, :prenom, :password, :spe, :mail, :statut)";

        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(":nom", $name);
        $stmt->bindParam(":prenom", $firstname);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":spe", $spe);
        $stmt->bindParam(":mail", $mail);
        $stmt->bindParam(":statut", $statut);
        $stmt->execute();
        header("Location: ../index.php");
        exit();
    }
}