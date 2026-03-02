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
    header('Location: ../ocorrencia.php');
    exit;
}

$conn = conexaoBanco();
$id = (int) $_GET['id'];

$sql = "SELECT id, nome_aluno, turma, professor_responsavel, causa, nivel_punicao FROM ocorrencia WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$ocorrencia = $stmt->get_result()->fetch_assoc();

if (!$ocorrencia) {
    header('Location: ../ocorrencia.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_aluno = $conn->real_escape_string(trim($_POST['nome_aluno']));
    $turma = $conn->real_escape_string(trim($_POST['turma']));
    $professor_responsavel = $conn->real_escape_string(trim($_POST['professor_responsavel']));
    $causa = $conn->real_escape_string(trim($_POST['causa']));
    $nivel_punicao = $conn->real_escape_string($_POST['nivel_punicao']);

    if (!empty($nome_aluno) && !empty($turma) && !empty($professor_responsavel) && !empty($causa) && !empty($nivel_punicao)) {
        $update_sql = "UPDATE ocorrencia SET nome_aluno = ?, turma = ?, professor_responsavel = ?, causa = ?, nivel_punicao = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssssi", $nome_aluno, $turma, $professor_responsavel, $causa, $nivel_punicao, $id);

        if ($update_stmt->execute()) {
            header('Location: ../ocorrencia.php?success=edited');
            exit;
        } else {
            $error = "Erro ao atualizar a ocorrência.";
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
    <title>Editar Ocorrência - SENAI</title>
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
            <a href="../../public/form_ocorrencia.php" class="menu-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                </svg> Ocorrências</a>
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
            <a href="../ocorrencia.php" class="btn-back-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Voltar para Lista
            </a>

            <h2 style="color: var(--text-primary); margin-bottom: 24px; font-size: 24px;">Editar Ocorrência</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="nome_aluno">Nome do Aluno</label>
                    <input type="text" class="form-control" id="nome_aluno" name="nome_aluno"
                        value="<?php echo htmlspecialchars($ocorrencia['nome_aluno']); ?>" required>
                </div>

                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="turma">Turma</label>
                        <input type="text" class="form-control" id="turma" name="turma"
                            value="<?php echo htmlspecialchars($ocorrencia['turma']); ?>" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="professor_responsavel">Professor Responsável</label>
                        <input type="text" class="form-control" id="professor_responsavel" name="professor_responsavel"
                            value="<?php echo htmlspecialchars($ocorrencia['professor_responsavel']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="causa">Causa / Descrição</label>
                    <textarea class="form-control" id="causa" name="causa" rows="4" style="resize: vertical;"
                        required><?php echo htmlspecialchars($ocorrencia['causa']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="nivel_punicao">Nível de Punição</label>
                    <select class="form-control" id="nivel_punicao" name="nivel_punicao" required
                        style="appearance: auto;">
                        <option value="Leve" <?php echo $ocorrencia['nivel_punicao'] == 'Leve' ? 'selected' : ''; ?>
                            >Advertência Verbal (Leve)</option>
                        <option value="Media" <?php echo $ocorrencia['nivel_punicao'] == 'Media' ? 'selected' : ''; ?>
                            >Advertência Escrita (Média)</option>
                        <option value="Grave" <?php echo $ocorrencia['nivel_punicao'] == 'Grave' ? 'selected' : ''; ?>
                            >Convocação de Responsáveis (Grave)</option>
                        <option value="Suspensao" <?php echo $ocorrencia['nivel_punicao'] == 'Suspensao' ? 'selected' : ''; ?>>Suspensão</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary" style="margin-top: 10px;">Salvar Alterações</button>
            </form>
        </div>
    </main>
</body>

</html>