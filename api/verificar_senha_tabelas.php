<?php
session_start();
require_once '../config/conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_logado'])) {
    echo json_encode(['success' => false, 'error' => 'Sessão expirada.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $password = $data['password'] ?? '';

    if (empty($password)) {
        echo json_encode(['success' => false, 'error' => 'Senha não fornecida.']);
        exit;
    }

    $conn = conexaoBanco();
    $nif = $_SESSION['nif'];

    $sql = "SELECT senha FROM colaborador WHERE nif = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nif);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['senha'])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Senha incorreta.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Usuário não encontrado.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método inválido.']);
}
?>