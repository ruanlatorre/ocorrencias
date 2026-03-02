<?php
// deleta_colaborador.php
require_once '../config/conexao.php';
session_start();

if (!isset($_SESSION['user_logado'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $conn = conexaoBanco();
    $id = (int) $_POST['id'];

    $sql = "DELETE FROM colaborador WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: ../tables/colaborador.php?success=deleted');
    } else {
        header('Location: ../tables/colaborador.php?error=delete_failed');
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: ../tables/colaborador.php');
}
?>