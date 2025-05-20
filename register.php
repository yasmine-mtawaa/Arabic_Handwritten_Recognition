<?php
session_start();
include 'connect.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['signUp'])) {
        $firstName = $_POST['fName'];
        $lastName = $_POST['lName'];
        $email = $_POST['email'];
        $password = md5($_POST['password']);

        $checkEmail = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($checkEmail);
        if ($result->num_rows > 0) {
            $error = "Email address already exists!";
        } else {
            $insertQuery = "INSERT INTO users(firstName, lastName, email, password)
                            VALUES ('$firstName', '$lastName', '$email', '$password')";
            if ($conn->query($insertQuery) === TRUE) {
                header("Location: index.php");
                exit();
            } else {
                $error = "Database error: " . $conn->error;
            }
        }
    }

    if (isset($_POST['signIn'])) {
        $email = $_POST['email'];
        $password = md5($_POST['password']);

        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $_SESSION['email'] = $email;
            header("Location: homepage.php");
            exit();
        } else {
            $error = "Incorrect email or password";
        }
    }
}

// inclure le HTML ici pour afficher le formulaire avec $error
include 'index.php';
?>
