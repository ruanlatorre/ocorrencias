<?php
// tables/api/colaborador.php
require_once '../../config/conexao.php';
header('Content-Type: application/json');

/**
 * Captura o endereço IP real do usuário.
 * Suporta proxies e load balancers.
 */
function getAddressIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Pode conter múltiplos IPs separados por vírgula
        $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ipList[0]);
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$method = $_SERVER['REQUEST_METHOD'];
$conn = conexaoBanco();

switch ($method) {
    case 'GET':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        if ($id) {
            $sql = "SELECT id, nome, nif, email, setor, status, permissao, ip_address, created_at FROM colaborador WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc());
            $stmt->close();
        } else {
            // Excluding password hash for security
            $sql = "SELECT id, nome, nif, email, setor, status, permissao, ip_address, created_at FROM colaborador ORDER BY nome ASC";
            $result = $conn->query($sql);
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['nome'], $input['nif'], $input['email'], $input['senha'], $input['setor'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            exit;
        }

        $nome = $conn->real_escape_string($input['nome']);
        $nif = $conn->real_escape_string($input['nif']);
        $email = $conn->real_escape_string($input['email']);
        $senha = $input['senha']; // Not escaped before hashing
        $setor = $conn->real_escape_string($input['setor']);
        $status = isset($input['status']) ? $conn->real_escape_string($input['status']) : 'Ativo';
        $permissao = isset($input['permissao']) ? $conn->real_escape_string($input['permissao']) : 'Usuario';

        $hashed_password = password_hash($senha, PASSWORD_DEFAULT);
        $ip_address = getAddressIP();

        $sql = "INSERT INTO colaborador (nome, nif, email, senha, setor, status, permissao, ip_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssss', $nome, $nif, $email, $hashed_password, $setor, $status, $permissao, $ip_address);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['success' => true, 'id' => $conn->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to insert']);
        }
        $stmt->close();
        break;

    case 'PUT':
    case 'PATCH':
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID is required for update']);
            exit;
        }

        $id = (int) $input['id'];
        $updates = [];
        $types = '';
        $params = [];

        $allowedFields = ['nome', 'nif', 'email', 'setor', 'status', 'permissao'];
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $input)) {
                $updates[] = "$field = ?";
                $types .= 's';
                $params[] = $input[$field] !== null ? $conn->real_escape_string($input[$field]) : null;
            }
        }

        // Handle password update separately securely
        if (isset($input['senha'])) {
            $updates[] = "senha = ?";
            $types .= 's';
            $params[] = password_hash($input['senha'], PASSWORD_DEFAULT);
        }

        if (empty($updates)) {
            http_response_code(400);
            echo json_encode(['error' => 'No fields to update']);
            exit;
        }

        $sql = "UPDATE colaborador SET " . implode(', ', $updates) . " WHERE id = ?";
        $types .= 'i';
        $params[] = $id;

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'affected_rows' => $stmt->affected_rows]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Update failed: ' . $conn->error]);
        }
        $stmt->close();
        break;

    case 'DELETE':
        $input = json_decode(file_get_contents('php://input'), true);
        $id = isset($_GET['id']) ? (int) $_GET['id'] : (isset($input['id']) ? (int) $input['id'] : null);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID is required for deletion']);
            exit;
        }

        $sql = "DELETE FROM colaborador WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Record not found']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Deletion failed: ' . $conn->error]);
        }
        $stmt->close();
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}

$conn->close();
?>