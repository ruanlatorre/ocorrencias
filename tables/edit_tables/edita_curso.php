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
    header('Location: ../curso.php');
    exit;
}

$conn = conexaoBanco();
$id = (int) $_GET['id'];

$sql = "SELECT * FROM curso WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$curso = $result->fetch_assoc();

if (!$curso) {
    header('Location: ../curso.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_curso = trim($_POST['nome_curso']);
    $status = isset($_POST['status']) ? $_POST['status'] : 'Ativo';

    if (!empty($nome_curso)) {
        $nome_curso = $conn->real_escape_string($nome_curso);
        $status = $conn->real_escape_string($status);

        $update_sql = "UPDATE curso SET nome = ?, status = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssi", $nome_curso, $status, $id);

        if ($update_stmt->execute()) {
            header('Location: ../curso.php?success=edited');
            exit;
        } else {
            $error = "Erro ao atualizar curso.";
        }
    } else {
        $error = "O nome é obrigatório.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso - SENAI</title>
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
            <a href="../../public/form_curso.php" class="menu-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg> Cursos</a>
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
            <a href="../curso.php" class="btn-back-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Voltar para Lista
            </a>

            <h2 style="color: var(--text-primary); margin-bottom: 24px; font-size: 24px;">Editar Curso</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="nome_curso">Nome do Curso</label>
                    <input type="text" class="form-control" id="nome_curso" name="nome_curso"
                        value="<?php echo htmlspecialchars($curso['nome']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required style="appearance: auto;">
                        <option value="Ativo" <?php echo $curso['status'] == 'Ativo' ? 'selected' : ''; ?>>Ativo</option>
                        <option value="Inativo" <?php echo $curso['status'] == 'Inativo' ? 'selected' : ''; ?>>Inativo
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn-primary" style="margin-top: 10px;">Salvar Alterações</button>
            </form>
        </div>
    </main>
</body>

</html>