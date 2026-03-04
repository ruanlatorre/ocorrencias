<?php
session_start();
require_once '../config/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conexaoBanco();
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        header('Location: ../index.php?error=empty');
        exit;
    }

    $username_escaped = $conn->real_escape_string($username);

    // Procura apenas na tabela de colaborador (Professor/Gestor/Admin)
    $sql = 'SELECT * FROM colaborador WHERE email = ? OR nif = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username_escaped, $username_escaped);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verifica a senha
        if (password_verify($password, $user['senha'])) {
            $_SESSION['user_logado'] = true;
            $_SESSION['role'] = 'colaborador';
            $_SESSION['username'] = $user['nome'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['nif'] = $user['nif'];
            $_SESSION['permissao'] = $user['permissao'];

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