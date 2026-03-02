<?php
session_start();
require_once '../config/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conexaoBanco();
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Validar se os campos não estão vazios
    if (empty($username) || empty($password)) {
        header('Location: ../public/form_cadastro.php?error=empty');
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = 'INSERT INTO usuario (user, password) VALUES (?, ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $hashed_password);

    if ($stmt->execute()) {
        header('Location: ../index.php?success=registered');
    } else {
        header('Location: ../public/form_cadastro.php?error=true');
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: ../public/form_cadastro.php');
    exit;
}
?>