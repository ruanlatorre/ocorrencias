<?php
session_start();
require_once '../config/conexao.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);

    // If not JSON, try $_POST
    $username = trim($input['username'] ?? $_POST['username'] ?? '');
    $password = $input['password'] ?? $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        http_response_code(400);
        echo json_encode(['error' => 'Preencha todos os campos.']);
        exit;
    }

    $conn = conexaoBanco();
    $username_escaped = $conn->real_escape_string($username);

    // Procura apenas na tabela de colaborador (Professor/Gestor/Admin)
    $sql = 'SELECT * FROM colaborador WHERE email = ? OR nif = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username_escaped, $username_escaped);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verifica a senha
        if (password_verify($password, $user['senha'])) {
            $_SESSION['user_logado'] = true;
            $_SESSION['role'] = 'colaborador';
            $_SESSION['username'] = $user['nome'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['nif'] = $user['nif'];
            $_SESSION['permissao'] = $user['permissao'];

            echo json_encode(['success' => true, 'redirect' => 'public/dashboard.php']);
        } else {
            // Senha incorreta
            http_response_code(401);
            echo json_encode(['error' => 'Usuário ou senha incorretos.']);
        }
    } else {
        // Usuário não encontrado
        http_response_code(401);
        echo json_encode(['error' => 'Usuário ou senha incorretos.']);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>