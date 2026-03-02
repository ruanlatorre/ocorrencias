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
    header('Location: ../colaborador.php');
    exit;
}

$conn = conexaoBanco();
$id = (int) $_GET['id'];

$sql = "SELECT id, nome, nif, email, setor, status, permissao FROM colaborador WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$colab = $stmt->get_result()->fetch_assoc();

if (!$colab) {
    header('Location: ../colaborador.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conn->real_escape_string(trim($_POST['nome']));
    $nif = $conn->real_escape_string(trim($_POST['nif']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $setor = $conn->real_escape_string(trim($_POST['setor']));
    $status = $conn->real_escape_string($_POST['status']);
    $permissao = $conn->real_escape_string($_POST['permissao']);

    if (!empty($nome) && !empty($nif) && !empty($email) && !empty($setor) && !empty($status) && !empty($permissao)) {
        // Se a senha for preenchida, atualiza também a senha
        if (!empty($_POST['senha'])) {
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            $update_sql = "UPDATE colaborador SET nome = ?, nif = ?, email = ?, senha = ?, setor = ?, status = ?, permissao = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssssssi", $nome, $nif, $email, $senha, $setor, $status, $permissao, $id);
        } else {
            $update_sql = "UPDATE colaborador SET nome = ?, nif = ?, email = ?, setor = ?, status = ?, permissao = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssssssi", $nome, $nif, $email, $setor, $status, $permissao, $id);
        }

        if ($update_stmt->execute()) {
            header('Location: ../colaborador.php?success=edited');
            exit;
        } else {
            $error = "Erro ao atualizar o colaborador.";
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
    <title>Editar Colaborador - SENAI</title>
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
            <a href="../../public/form_colaborador.php" class="menu-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                </svg> Colaboradores</a>
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
            <a href="../colaborador.php" class="btn-back-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Voltar para Lista
            </a>

            <h2 style="color: var(--text-primary); margin-bottom: 24px; font-size: 24px;">Editar Colaborador</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="nome">Nome do Colaborador</label>
                    <input type="text" class="form-control" id="nome" name="nome"
                        value="<?php echo htmlspecialchars($colab['nome']); ?>" required>
                </div>

                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="nif">NIF</label>
                        <input type="text" class="form-control" id="nif" name="nif"
                            value="<?php echo htmlspecialchars($colab['nif']); ?>" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo htmlspecialchars($colab['email']); ?>" required>
                    </div>
                </div>

                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="senha">Nova Senha (deixe em branco para não alterar)</label>
                        <input type="password" class="form-control" id="senha" name="senha">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="setor">Setor</label>
                        <input type="text" class="form-control" id="setor" name="setor"
                            value="<?php echo htmlspecialchars($colab['setor']); ?>" required>
                    </div>
                </div>

                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required style="appearance: auto;">
                            <option value="Ativo" <?php echo $colab['status'] == 'Ativo' ? 'selected' : ''; ?>>Ativo
                            </option>
                            <option value="Inativo" <?php echo $colab['status'] == 'Inativo' ? 'selected' : ''; ?>
                                >Inativo</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="permissao">Permissão</label>
                        <select class="form-control" id="permissao" name="permissao" required style="appearance: auto;">
                            <option value="Administrador" <?php echo $colab['permissao'] == 'Administrador' ? 'selected' : ''; ?>>Administrador</option>
                            <option value="Professor" <?php echo $colab['permissao'] == 'Professor' ? 'selected' : ''; ?>
                                >Professor</option>
                            <option value="Assistente" <?php echo $colab['permissao'] == 'Assistente' ? 'selected' : ''; ?>>Assistente</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-primary" style="margin-top: 10px;">Salvar Alterações</button>
            </form>
        </div>
    </main>
</body>

</html>