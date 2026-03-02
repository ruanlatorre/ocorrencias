<?php
session_start();
require_once '../../config/conexao.php';

if (!isset($_SESSION['user_logado'])) {
    header('Location: ../../index.php');
    exit;
}
$username = $_SESSION['username'];
$date = date('d/m/Y');

if (!isset($_GET['id'])) {
    header('Location: ../aluno.php');
    exit;
}

$conn = conexaoBanco();
$id = (int) $_GET['id'];

// Busca dados do aluno
$sql_aluno = "SELECT * FROM aluno WHERE id = ?";
$stmt = $conn->prepare($sql_aluno);
$stmt->bind_param("i", $id);
$stmt->execute();
$aluno = $stmt->get_result()->fetch_assoc();

if (!$aluno) {
    header('Location: ../aluno.php');
    exit;
}

// Busca cursos e turmas
$sql_cursos = "SELECT id, nome FROM curso WHERE status = 'Ativo'";
$cursos = $conn->query($sql_cursos);

$sql_turmas = "SELECT id, nome FROM turma";
$turmas = $conn->query($sql_turmas);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_aluno = $conn->real_escape_string(trim($_POST['nome_aluno']));
    $matricula = $conn->real_escape_string(trim($_POST['matricula']));
    $data_entrada = $conn->real_escape_string($_POST['data_entrada']);
    $data_saida = !empty($_POST['data_saida']) ? $conn->real_escape_string($_POST['data_saida']) : NULL;
    $curso_id = (int) $_POST['curso_id'];
    $turma_id = (int) $_POST['turma_id'];

    if (!empty($nome_aluno) && !empty($matricula) && !empty($data_entrada) && !empty($curso_id) && !empty($turma_id)) {

        if ($data_saida) {
            $update_sql = "UPDATE aluno SET nome = ?, matricula = ?, data_entrada = ?, data_saida = ?, curso_id = ?, turma_id = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssssiii", $nome_aluno, $matricula, $data_entrada, $data_saida, $curso_id, $turma_id, $id);
        } else {
            $update_sql = "UPDATE aluno SET nome = ?, matricula = ?, data_entrada = ?, data_saida = NULL, curso_id = ?, turma_id = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssiii", $nome_aluno, $matricula, $data_entrada, $curso_id, $turma_id, $id);
        }

        if ($update_stmt->execute()) {
            header('Location: ../aluno.php?success=edited');
            exit;
        } else {
            $error = "Erro ao atualizar o aluno.";
        }
    } else {
        $error = "Preencha todos os campos obrigatórios.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aluno - SENAI</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .btn-back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 20px;
            transition: 0.2s;
        }

        .btn-back-link:hover {
            color: var(--text-primary);
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-header"><span class="sidebar-logo">SENAI</span></div>
        <div class="sidebar-menu">
            <a href="../../public/dashboard.php" class="menu-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg> Home</a>
            <a href="../../public/form_aluno.php" class="menu-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg> Alunos</a>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="user-info"><span class="greeting">Olá, <span class="name">
                        <?php echo htmlspecialchars($username); ?>
                    </span></span></div>
            <div class="date-info">
                <?php echo $date; ?>
            </div>
        </header>

        <div class="welcome-card" style="border-left: none; max-width: 600px;">
            <a href="../aluno.php" class="btn-back-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Voltar para Lista
            </a>

            <h2 style="color: var(--text-primary); margin-bottom: 24px; font-size: 24px;">Editar Aluno</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="nome_aluno">Nome do Aluno</label>
                    <input type="text" class="form-control" id="nome_aluno" name="nome_aluno"
                        value="<?php echo htmlspecialchars($aluno['nome']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="matricula">Matrícula</label>
                    <input type="text" class="form-control" id="matricula" name="matricula"
                        value="<?php echo htmlspecialchars($aluno['matricula']); ?>" required>
                </div>

                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="data_entrada">Data de Entrada</label>
                        <input type="date" class="form-control" id="data_entrada" name="data_entrada"
                            value="<?php echo $aluno['data_entrada']; ?>" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="data_saida">Data de Saída (Opcional)</label>
                        <input type="date" class="form-control" id="data_saida" name="data_saida"
                            value="<?php echo $aluno['data_saida']; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="curso_id">Curso</label>
                    <select class="form-control" id="curso_id" name="curso_id" required style="appearance: auto;">
                        <?php while ($c = $cursos->fetch_assoc()): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo $aluno['curso_id'] == $c['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['nome']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="turma_id">Turma</label>
                    <select class="form-control" id="turma_id" name="turma_id" required style="appearance: auto;">
                        <?php while ($t = $turmas->fetch_assoc()): ?>
                            <option value="<?php echo $t['id']; ?>" <?php echo $aluno['turma_id'] == $t['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($t['nome']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn-primary" style="margin-top: 10px;">Salvar Alterações</button>
            </form>
        </div>
    </main>
</body>

</html>