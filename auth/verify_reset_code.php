<?php
// api/verify_reset_code.php
require_once '../config/conexao.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['email'], $input['code'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$conn = conexaoBanco();
$email = $conn->real_escape_string($input['email']);
$code = $conn->real_escape_string($input['code']);

$sql = "SELECT id FROM colaborador WHERE email = ? AND reset_token = ? AND reset_expires > NOW() AND status = 'Ativo'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $email, $code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Código inválido ou expirado. Tente gerar um novo.']);
}

$stmt->close();
$conn->close();
?>