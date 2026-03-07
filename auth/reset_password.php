<?php
// api/reset_password.php
require_once '../config/conexao.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['email'], $input['code'], $input['new_password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$conn = conexaoBanco();
$email = $conn->real_escape_string($input['email']);
$code = $conn->real_escape_string($input['code']);
$new_password = $input['new_password'];

// Verificar token novamente como medida de segurança em requisições concorrentes
$sql = "SELECT id FROM colaborador WHERE email = ? AND reset_token = ? AND reset_expires > NOW() AND status = 'Ativo'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $email, $code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(401);
    echo json_encode(['error' => 'Código inválido ou expirado.']);
    $stmt->close();
    $conn->close();
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Atualizar senha e limpar token
$sql_update = "UPDATE colaborador SET senha = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param('si', $hashed_password, $user['id']);

if ($stmt_update->execute()) {
    echo json_encode(['success' => true, 'message' => 'Senha alterada com sucesso!']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao redefinir a senha.']);
}

$stmt_update->close();
$conn->close();
?>