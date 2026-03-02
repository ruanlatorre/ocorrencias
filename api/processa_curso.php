<?php
// processa_curso.php
require_once '../config/conexao.php';
session_start();

if (!isset($_SESSION['user_logado'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conexaoBanco();

    $nome_curso = trim($_POST['nome_curso']);
    $status = isset($_POST['status']) ? $_POST['status'] : 'Ativo';

    if (empty($nome_curso)) {
        header('Location: ../public/form_curso.php?error=empty');
        exit;
    }

    $nome_curso = $conn->real_escape_string($nome_curso);
    $status = $conn->real_escape_string($status);

    $sql = 'INSERT INTO curso (nome, status) VALUES (?, ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $nome_curso, $status);

    if ($stmt->execute()) {
        header('Location: ../public/form_curso.php?success=registered');
    } else {
        header('Location: ../public/form_curso.php?error=true');
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: ../public/dashboard.php');
}
?>