<?php
session_start();
require_once '../config/conexao.php';

if (!isset($_SESSION['user_logado'])) {
    header('Location: ../index.php');
    exit;
}
$username = $_SESSION['username'];
$date = date('d/m/Y');

$conn = conexaoBanco();

$sql = "SELECT * FROM colaborador ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colaboradores Cadastrados - SENAI</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
        }
        td { color: var(--text-primary); }
        th {
            background-color: rgba(255, 255, 255, 0.02);
            font-weight: 500;
        }
        tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }
        .actions-btn {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }
        .btn-icon {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }
        .btn-view { color: #3b82f6; }
        .btn-view:hover { background: rgba(59, 130, 246, 0.1); }
        .btn-edit { color: #10b981; }
        .btn-edit:hover { background: rgba(16, 185, 129, 0.1); }
        .btn-delete { color: #ef4444; }
        .btn-delete:hover { background: rgba(239, 68, 68, 0.1); }
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-header"><span class="sidebar-logo">SENAI</span></div>
        <div class="sidebar-menu">
            <a href="../public/dashboard.php" class="menu-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> Home</a>
            <a href="../public/form_ocorrencia.php" class="menu-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path></svg> Registrar Ocorrência</a>
            <a href="../public/form_curso.php" class="menu-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg> Cursos</a>
            <a href="../public/form_turma.php" class="menu-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg> Turmas</a>
            <a href="../public/form_aluno.php" class="menu-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Alunos</a>
            <a href="../public/form_colaborador.php" class="menu-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle></svg> Colaboradores</a>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="user-info"><span class="greeting">Olá, <span class="name"><?php echo htmlspecialchars($username); ?></span></span></div>
            <div class="date-info"><?php echo $date; ?></div>
        </header>

        <div class="welcome-card" style="border-left: none;">
            <div class="header-actions">
                <h2 style="color: var(--text-primary); font-size: 24px; margin: 0;">Lista de Colaboradores</h2>
                <a href="../public/form_colaborador.php" class="btn-primary" style="text-decoration: none; padding: 10px 16px; font-size: 14px;">+ Novo Colaborador</a>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>NIF</th>
                            <th>Email</th>
                            <th>Setor</th>
                            <th>Permissão</th>
                            <th>Status</th>
                            <th style="text-align: right;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td style="font-weight: 500; font-size: 15px;"><?php echo htmlspecialchars($row['nome']); ?></td>
                                    <td style="font-family: monospace; color: #3b82f6;"><?php echo htmlspecialchars($row['nif']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['setor']); ?></td>
                                    <td><span style="font-size: 13px; font-weight: 600;"><?php echo htmlspecialchars($row['permissao']); ?></span></td>
                                    <td>
                                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; <?php echo $row['status'] == 'Ativo' ? 'background: rgba(16, 185, 129, 0.1); color: #10b981;' : 'background: rgba(239, 68, 68, 0.1); color: #ef4444;'; ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions-btn">
                                            <a href="#" class="btn-icon btn-view" title="Visualizar"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                                            <a href="edit_tables/edita_colaborador.php?id=<?php echo $row['id']; ?>" class="btn-icon btn-edit" title="Editar"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                                            <form action="delete_tables/deleta_colaborador.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este colaborador?');">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="btn-icon btn-delete" title="Excluir"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align: center; color: var(--text-secondary); padding: 20px;">Nenhum colaborador cadastrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
