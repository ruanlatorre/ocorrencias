<?php
// processa_aluno.php
require_once '../config/conexao.php';
session_start();

if (!isset($_SESSION['user_logado'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conexaoBanco();

    $nome = trim($_POST['nome']);
    $matricula = trim($_POST['matricula']);
    $data_entrada = $_POST['data_entrada'];
    $data_saida = isset($_POST['data_saida']) ? trim($_POST['data_saida']) : '';
    $curso_id = trim($_POST['curso_id']);
    $turma_id = trim($_POST['turma_id']);

    if (empty($nome) || empty($matricula) || empty($data_entrada) || empty($curso_id)) {
        header('Location: ../public/form_aluno.php?error=empty');
        exit;
    }

    $nome = $conn->real_escape_string($nome);
    $matricula = $conn->real_escape_string($matricula);
    $data_entrada = $conn->real_escape_string($data_entrada);

    // Tratativa opcional
    $data_saida = empty($data_saida) ? NULL : $conn->real_escape_string($data_saida);
    $turma_id = empty($turma_id) ? NULL : (int) $turma_id;
    $curso_id = (int) $curso_id;

    $sql = 'INSERT INTO aluno (nome, matricula, data_entrada, data_saida, curso_id, turma_id) VALUES (?, ?, ?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssii', $nome, $matricula, $data_entrada, $data_saida, $curso_id, $turma_id);

    if ($stmt->execute()) {
        header('Location: ../public/form_aluno.php?success=registered');
    } else {
        header('Location: ../public/form_aluno.php?error=true');
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: ../public/dashboard.php');
}
?>