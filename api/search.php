<?php
session_start();
require_once '../config/conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_logado'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado']);
    exit;
}

$conn = conexaoBanco();

$type = $_GET['type'] ?? '';
$query = $_GET['q'] ?? '';

if (empty($type) || empty($query)) {
    echo json_encode([]);
    exit;
}

$query = "%" . $conn->real_escape_string($query) . "%";

switch ($type) {
    case 'curso':
        $sql = "SELECT id, nome as label FROM curso WHERE nome LIKE ? AND status = 'Ativo' LIMIT 10";
        break;
    case 'colaborador':
        $sql = "SELECT id, nome as label FROM colaborador WHERE nome LIKE ? AND status = 'Ativo' LIMIT 10";
        break;
    case 'turma':
        $sql = "SELECT id, nome as label FROM turma WHERE nome LIKE ? LIMIT 10";
        break;
    case 'setor':
        $sql = "SELECT DISTINCT setor as label FROM colaborador WHERE setor LIKE ? LIMIT 10";
        break;
    case 'envolvido':
        // Busca nomes de pessoas já envolvidas em ocorrências
        $sql = "SELECT DISTINCT nome_aluno as label FROM ocorrencia WHERE nome_aluno LIKE ? LIMIT 10";
        break;
    default:
        echo json_encode([]);
        exit;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $query);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>