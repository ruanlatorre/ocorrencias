<?php
session_start();
require_once '../config/conexao.php';

if (!isset($_SESSION['user_logado'])) {
    header('Location: ../index.php');
    exit;
}

$conn = conexaoBanco();
$is_modal = isset($_GET['modal']);

$sql = "SELECT * FROM ocorrencia ORDER BY data_registro DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Tabela de Ocorrências</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background: var(--bg-color);
            padding:
                <?php echo $is_modal ? '0' : '20px'; ?>
            ;
            color: var(--text-primary);
        }

        .table-container {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background: rgba(255, 255, 255, 0.03);
            color: var(--accent-red);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-Leve {
            background: rgba(51, 153, 255, 0.1);
            color: #3399ff;
        }

        .badge-Media {
            background: rgba(255, 153, 51, 0.1);
            color: #ff9933;
        }

        .badge-Grave {
            background: rgba(255, 51, 51, 0.1);
            color: var(--accent-red);
        }

        .badge-Suspensao {
            background: #ff3333;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Aluno</th>
                    <th>Turma</th>
                    <th>Professor</th>
                    <th>Causa</th>
                    <th>Punição</th>
                    <th>Data</th>
                    <th style="text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#
                                <?php echo $row['id']; ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['nome_aluno']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['turma']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['professor_responsavel']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['causa']); ?>
                            </td>
                            <td><span class="badge badge-<?php echo $row['nivel_punicao']; ?>">
                                    <?php echo $row['nivel_punicao']; ?>
                                </span></td>
                            <td>
                                <?php echo date('d/m/Y H:i', strtotime($row['data_registro'])); ?>
                            </td>
                            <td style="text-align: center; white-space: nowrap;">
                                <button onclick="window.parent.editRecord('ocorrencia', <?php echo $row['id']; ?>)"
                                    class="btn-icon" title="Editar">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                    </svg>
                                </button>
                                <button onclick="window.parent.deleteRecord('ocorrencia', <?php echo $row['id']; ?>)"
                                    class="btn-icon btn-icon-delete" title="Excluir">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                        </path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">Nenhuma ocorrência registrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>