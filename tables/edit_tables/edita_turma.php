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
    header('Location: ../turma.php');
    exit;
}

$conn = conexaoBanco();
$id = (int) $_GET['id'];

// Busca dados da turma
$sql_turma = "SELECT * FROM turma WHERE id = ?";
$stmt = $conn->prepare($sql_turma);
$stmt->bind_param("i", $id);
$stmt->execute();
$turma = $stmt->get_result()->fetch_assoc();

if (!$turma) {
    header('Location: ../turma.php');
    exit;
}

// Busca cursos e colaboradores para os selects
$sql_cursos = "SELECT id, nome FROM curso WHERE status = 'Ativo'";
$cursos = $conn->query($sql_cursos);

$sql_colabs = "SELECT id, nome FROM colaborador WHERE status = 'Ativo'";
$colaboradores = $conn->query($sql_colabs);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_turma = $conn->real_escape_string(trim($_POST['nome_turma']));
    $periodo = $conn->real_escape_string($_POST['periodo']);
    $data_inicio = $conn->real_escape_string($_POST['data_inicio']);
    $data_fim = $conn->real_escape_string($_POST['data_fim']);
    $curso_id = (int) $_POST['curso_id'];
    $colaborador_id = (int) $_POST['colaborador_id'];

    if (!empty($nome_turma) && !empty($periodo) && !empty($data_inicio) && !empty($data_fim) && !empty($curso_id) && !empty($colaborador_id)) {
        $update_sql = "UPDATE turma SET nome = ?, periodo = ?, data_inicio = ?, data_fim = ?, curso_id = ?, colaborador_id = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssiii", $nome_turma, $periodo, $data_inicio, $data_fim, $curso_id, $colaborador_id, $id);

        if ($update_stmt->execute()) {
            header('Location: ../turma.php?success=edited');
            exit;
        } else {
            $error = "Erro ao atualizar a turma.";
        }
    } else {
        $error = "Todos os campos são obrigatórios.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Turma - SENAI</title>
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
            <a href="../../public/form_turma.php" class="menu-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                </svg> Turmas</a>
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
            <a href="../turma.php" class="btn-back-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Voltar para Lista
            </a>

            <h2 style="color: var(--text-primary); margin-bottom: 24px; font-size: 24px;">Editar Turma</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="nome_turma">Turma</label>
                    <input type="text" class="form-control" id="nome_turma" name="nome_turma"
                        value="<?php echo htmlspecialchars($turma['nome']); ?>" required>
                </div>

                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="periodo">Período</label>
                        <select class="form-control" id="periodo" name="periodo" required style="appearance: auto;">
                            <option value="Matutino" <?php echo $turma['periodo'] == 'Matutino' ? 'selected' : ''; ?>
                                >Matutino</option>
                            <option value="Vespertino" <?php echo $turma['periodo'] == 'Vespertino' ? 'selected' : ''; ?>
                                >Vespertino</option>
                            <option value="Noturno" <?php echo $turma['periodo'] == 'Noturno' ? 'selected' : ''; ?>
                                >Noturno</option>
                            <option value="Integral" <?php echo $turma['periodo'] == 'Integral' ? 'selected' : ''; ?>
                                >Integral</option>
                        </select>
                    </div>
                </div>

                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="data_inicio">Início</label>
                        <input type="date" class="form-control" id="data_inicio" name="data_inicio"
                            value="<?php echo $turma['data_inicio']; ?>" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="data_fim">Fim</label>
                        <input type="date" class="form-control" id="data_fim" name="data_fim"
                            value="<?php echo $turma['data_fim']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="curso_id">Curso</label>
                    <select class="form-control" id="curso_id" name="curso_id" required style="appearance: auto;">
                        <?php while ($c = $cursos->fetch_assoc()): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo $turma['curso_id'] == $c['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['nome']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="colaborador_id">Colaborador Responsável</label>
                    <select class="form-control" id="colaborador_id" name="colaborador_id" required
                        style="appearance: auto;">
                        <?php while ($colab = $colaboradores->fetch_assoc()): ?>
                            <option value="<?php echo $colab['id']; ?>" <?php echo $turma['colaborador_id'] == $colab['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($colab['nome']); ?>
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