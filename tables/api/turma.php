<?php
// tables/api/turma.php
require_once '../../config/conexao.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$conn = conexaoBanco();

switch ($method) {
    case 'GET':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        if ($id) {
            $sql = "SELECT * FROM turma WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc());
            $stmt->close();
        } else {
            $sql = "SELECT * FROM turma ORDER BY nome ASC";
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
        if (!isset($input['nome'], $input['periodo'], $input['data_inicio'], $input['data_fim'], $input['curso_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            exit;
        }

        $nome = $conn->real_escape_string($input['nome']);
        $periodo = $conn->real_escape_string($input['periodo']);
        $data_inicio = $conn->real_escape_string($input['data_inicio']);
        $data_fim = $conn->real_escape_string($input['data_fim']);
        $curso_id = (int) $input['curso_id'];
        $colaborador_id = isset($input['colaborador_id']) && !empty($input['colaborador_id']) ? (int) $input['colaborador_id'] : null;

        $sql = "INSERT INTO turma (nome, periodo, data_inicio, data_fim, curso_id, colaborador_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssii', $nome, $periodo, $data_inicio, $data_fim, $curso_id, $colaborador_id);

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

        $allowedFields = ['nome', 'periodo', 'data_inicio', 'data_fim', 'curso_id', 'colaborador_id'];
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $input)) {
                $updates[] = "$field = ?";
                if (in_array($field, ['curso_id', 'colaborador_id'])) {
                    $types .= 'i';
                    $params[] = $input[$field] !== null && $input[$field] !== '' ? (int) $input[$field] : null;
                } else {
                    $types .= 's';
                    $params[] = $input[$field] !== null ? $conn->real_escape_string($input[$field]) : null;
                }
            }
        }

        if (empty($updates)) {
            http_response_code(400);
            echo json_encode(['error' => 'No fields to update']);
            exit;
        }

        $sql = "UPDATE turma SET " . implode(', ', $updates) . " WHERE id = ?";
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

        $sql = "DELETE FROM turma WHERE id = ?";
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