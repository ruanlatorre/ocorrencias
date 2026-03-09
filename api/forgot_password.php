<?php
// api/forgot_password.php
require_once '../config/conexao.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['email'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Email is required']);
    exit;
}

$conn = conexaoBanco();
$email = $conn->real_escape_string($input['email']);

// Verifica se existe o usuario e está ativo
$sql = "SELECT id, nome FROM colaborador WHERE email = ? AND status = 'Ativo'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'E-mail não encontrado ou inativo.']);
    $stmt->close();
    $conn->close();
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

// Gera um token numerico de 6 digitos
$token = sprintf("%06d", mt_rand(1, 999999));

// Atualiza o token no banco com validade de 15 minutos
$sql_update = "UPDATE colaborador SET reset_token = ?, reset_expires = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param('ss', $token, $email);

if ($stmt_update->execute()) {
    // Para ambientes de dev sem envio de e-mail, enviamos o código de simulação na resposta de sucesso
    echo json_encode([
        'success' => true,
        'message' => 'Código gerado com sucesso.'
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao processar solicitação.']);
}

$stmt_update->close();
$conn->close();
?>