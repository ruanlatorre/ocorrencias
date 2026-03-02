<?php
// processa_colaborador.php
require_once '../config/conexao.php';
session_start();

if (!isset($_SESSION['user_logado'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conexaoBanco();

    $nome = trim($_POST['nome']);
    $nif = trim($_POST['nif']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $setor = trim($_POST['setor']);
    $permissao = isset($_POST['permissao']) ? $_POST['permissao'] : 'Usuario';
    $status = isset($_POST['status']) ? $_POST['status'] : 'Ativo';

    if (empty($nome) || empty($nif) || empty($email) || empty($senha) || empty($setor)) {
        header('Location: ../public/form_colaborador.php?error=empty');
        exit;
    }

    // Preparando dados
    $nome = $conn->real_escape_string($nome);
    $nif = $conn->real_escape_string($nif);
    $email = $conn->real_escape_string($email);
    $setor = $conn->real_escape_string($setor);
    $permissao = $conn->real_escape_string($permissao);
    $status = $conn->real_escape_string($status);

    // Hash da senha
    $hashed_password = password_hash($senha, PASSWORD_DEFAULT);

    $sql = 'INSERT INTO colaborador (nome, nif, email, senha, setor, status, permissao) VALUES (?, ?, ?, ?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssss', $nome, $nif, $email, $hashed_password, $setor, $status, $permissao);

    if ($stmt->execute()) {
        header('Location: ../public/form_colaborador.php?success=registered');
    } else {
        header('Location: ../public/form_colaborador.php?error=true');
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: ../public/dashboard.php');
}
?>