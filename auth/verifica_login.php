<?php
session_start();
require_once '../config/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conexaoBanco();
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        header('Location: ../index.php?error=empty');
        exit;
    }

    $sql = 'SELECT * FROM usuario WHERE user = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_logado'] = true;
            $_SESSION['username'] = $user['user'];
            header('Location: ../public/dashboard.php');
            exit;
        } else {
            // Senha incorreta
            header('Location: ../index.php?error=invalid');
            exit;
        }
    } else {
        // Usuário não encontrado
        header('Location: ../index.php?error=invalid');
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: ../index.php');
    exit;
}
?>