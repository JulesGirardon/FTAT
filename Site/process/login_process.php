<?php
session_start();
include "../includes/connexionBDD.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = $_POST["mail"];
    $password = $_POST["password"];
    

    //Vérification mail et mdp
    $sql = "SELECT * FROM utilisateurs WHERE mail = :mail";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([':mail' => $mail]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($user) {
        if (password_verify($password, $user['MotDePasseU'])) {
            $_SESSION['user_id'] = $user['IdU'];
            $_SESSION['is_logged_in'] = true;
            header("Location: ../index.php");

        } else {
            $_SESSION['error'] = "mdp_incorrect";
            header("Location: ../pages/login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "email_incorrect";
        header("Location: ../pages/login.php");
        exit();
    }
}