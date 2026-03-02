<?php
// processa_ocorrencia.php
require_once '../config/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conexaoBanco();

    // Sanitização de dados
    $nome_aluno = $conn->real_escape_string($_POST['nome_aluno']);
    $turma = $conn->real_escape_string($_POST['turma']);
    $professor_responsavel = $conn->real_escape_string($_POST['professor_responsavel']);
    $causa = $conn->real_escape_string($_POST['causa']);
    $nivel_punicao = $conn->real_escape_string($_POST['nivel_punicao']);

    if (empty($nome_aluno) || empty($turma) || empty($professor_responsavel) || empty($causa) || empty($nivel_punicao)) {
        header('Location: ../public/form_ocorrencia.php?error=empty');
        exit;
    }

    $sql = 'INSERT INTO ocorrencia (nome_aluno, turma, professor_responsavel, causa, nivel_punicao) VALUES (?, ?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $nome_aluno, $turma, $professor_responsavel, $causa, $nivel_punicao);

    if ($stmt->execute()) {
        header('Location: ../public/dashboard.php?success=ocorrencia_registrada');
    } else {
        header('Location: ../public/form_ocorrencia.php?error=true');
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: ../public/dashboard.php');
}
?>