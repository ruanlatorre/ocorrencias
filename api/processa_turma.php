<?php
// processa_turma.php
require_once '../config/conexao.php';
session_start();

if (!isset($_SESSION['user_logado'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conexaoBanco();

    $nome_turma = trim($_POST['nome_turma']);
    $periodo = isset($_POST['periodo']) ? $_POST['periodo'] : '';
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $curso_id = trim($_POST['curso_id']);
    $colaborador_id = trim($_POST['colaborador_id']);

    if (empty($nome_turma) || empty($periodo) || empty($data_inicio) || empty($data_fim) || empty($curso_id)) {
        header('Location: ../public/form_turma.php?error=empty');
        exit;
    }

    $nome_turma = $conn->real_escape_string($nome_turma);
    $periodo = $conn->real_escape_string($periodo);
    $data_inicio = $conn->real_escape_string($data_inicio);
    $data_fim = $conn->real_escape_string($data_fim);
    $curso_id = (int) $curso_id;

    // Tratativa para colaborador nulo
    $colaborador_id = empty($colaborador_id) ? NULL : (int) $colaborador_id;

    $sql = 'INSERT INTO turma (nome, periodo, data_inicio, data_fim, curso_id, colaborador_id) VALUES (?, ?, ?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);

    // Se o colaborador_id for null, é preciso amarrar de forma diferente ou deixar o BD tratar.
    // bind_param não suporta variáveis passadas com null nativo tão facilmente se forem ints diretos sem cuidado,
    // mas o `i` aceitará a tipagem se passarmos a variável com null.
    $stmt->bind_param('ssssii', $nome_turma, $periodo, $data_inicio, $data_fim, $curso_id, $colaborador_id);

    if ($stmt->execute()) {
        header('Location: ../public/form_turma.php?success=registered');
    } else {
        header('Location: ../public/form_turma.php?error=true');
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: ../public/dashboard.php');
}
?>