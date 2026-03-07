<?php
require_once '../config/conexao.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$conn = conexaoBanco();

switch ($method) {
    case 'GET':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        if ($id) {
            $sql = "SELECT * FROM aluno WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc());
            $stmt->close();
        } else {
            $sql = "SELECT a.*, c.nome as curso_nome, t.nome as turma_nome FROM aluno a LEFT JOIN curso c ON a.curso_id = c.id LEFT JOIN turma t ON a.turma_id = t.id ORDER BY a.nome ASC";
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
        if (!isset($input['nome'], $input['matricula'], $input['data_entrada'], $input['curso_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            exit;
        }

        $nome = $conn->real_escape_string($input['nome']);
        $matricula = $conn->real_escape_string($input['matricula']);
        $data_entrada = $conn->real_escape_string($input['data_entrada']);
        $data_saida = isset($input['data_saida']) && !empty($input['data_saida']) ? $conn->real_escape_string($input['data_saida']) : null;
        $curso_id = (int) $input['curso_id'];
        $turma_id = isset($input['turma_id']) && !empty($input['turma_id']) ? (int) $input['turma_id'] : null;

        $sql = "INSERT INTO aluno (nome, matricula, data_entrada, data_saida, curso_id, turma_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssii', $nome, $matricula, $data_entrada, $data_saida, $curso_id, $turma_id);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['success' => true, 'id' => $conn->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to insert. Matricula might exist already.']);
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

        $allowedFields = ['nome', 'matricula', 'data_entrada', 'data_saida', 'curso_id', 'turma_id'];
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $input)) {
                $updates[] = "$field = ?";
                if (in_array($field, ['curso_id', 'turma_id'])) {
                    $types .= 'i';
                    $params[] = $input[$field] !== null && $input[$field] !== '' ? (int) $input[$field] : null;
                } else {
                    $types .= 's';
                    $params[] = $input[$field] !== null && $input[$field] !== '' ? $conn->real_escape_string($input[$field]) : null;
                }
            }
        }

        if (empty($updates)) {
            http_response_code(400);
            echo json_encode(['error' => 'No fields to update']);
            exit;
        }

        $sql = "UPDATE aluno SET " . implode(', ', $updates) . " WHERE id = ?";
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

        $sql = "DELETE FROM aluno WHERE id = ?";
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