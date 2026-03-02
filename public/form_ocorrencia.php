<?php
session_start();
if (!isset($_SESSION['user_logado'])) {
    header('Location: ../index.php');
    exit;
}
$username = $_SESSION['username'];
$date = date('d/m/Y');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Ocorrência - SENAI</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <!-- Sidebar Reutilizada (Pode ser separada em um include no futuro) -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <span class="sidebar-logo">SENAI</span>
        </div>

        <div class="sidebar-menu">
            <a href="dashboard.php" class="menu-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Home / Dashboard
            </a>
            <a href="#" class="menu-item active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Registrar Ocorrência
            </a>
            <a href="form_curso.php" class="menu-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                Cursos
            </a>
            <a href="form_turma.php" class="menu-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                Turmas
            </a>
            <a href="form_aluno.php" class="menu-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Alunos
            </a>
            <a href="form_colaborador.php" class="menu-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <polyline points="17 11 19 13 23 9"></polyline>
                </svg>
                Colaboradores
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="user-info">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent-red)" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" width="20" height="20">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span class="greeting">Olá, <span class="name">
                        <?php echo htmlspecialchars($username); ?>
                    </span></span>
            </div>

            <div class="date-info">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent-red)" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" width="18" height="18" style="margin-right: 8px;">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <?php echo $date; ?>
            </div>
        </header>

        <!-- Formulário de Ocorrência -->
        <div class="welcome-card" style="border-left: none;">
            <div class="header-actions"
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h2 style="color: var(--text-primary); font-size: 24px; margin: 0;">Lançamento de Ocorrência Disciplinar
                </h2>
                <a href="../tables/ocorrencia.php" class="btn-primary"
                    style="text-decoration: none; padding: 10px 16px; font-size: 14px; background: rgba(255, 255, 255, 0.1); border: 1px solid var(--border-color);">Ver
                    Histórico</a>
            </div>

            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'empty') {
                    echo '<div class="alert alert-error">Por favor, preencha todos os campos.</div>';
                } elseif ($_GET['error'] == 'true') {
                    echo '<div class="alert alert-error">Erro ao registrar ocorrência. Tente novamente.</div>';
                }
            }
            ?>

            <form action="../api/processa_ocorrencia.php" method="post" style="max-width: 600px;">
                <div class="form-group">
                    <label for="nome_aluno">Nome do Aluno</label>
                    <input type="text" class="form-control" id="nome_aluno" name="nome_aluno"
                        placeholder="Digite o nome completo do aluno" required>
                </div>

                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="turma">Turma</label>
                        <input type="text" class="form-control" id="turma" name="turma"
                            placeholder="Ex: Técnico em Desenvolvimento 1A" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="professor_responsavel">Professor Responsável</label>
                        <input type="text" class="form-control" id="professor_responsavel" name="professor_responsavel"
                            placeholder="Nome do Professor" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="causa">Causa / Descrição da Ocorrência</label>
                    <textarea class="form-control" id="causa" name="causa" rows="5"
                        placeholder="Descreva o que ocorreu..." required style="resize: vertical;"></textarea>
                </div>

                <div class="form-group">
                    <label for="nivel_punicao">Nível de Punição</label>
                    <select class="form-control" id="nivel_punicao" name="nivel_punicao" required
                        style="appearance: auto;">
                        <option value="" disabled selected>Selecione um nível...</option>
                        <option value="Leve">Advertência Verbal (Leve)</option>
                        <option value="Media">Advertência Escrita (Média)</option>
                        <option value="Grave">Convocação de Responsáveis (Grave)</option>
                        <option value="Suspensao">Suspensão</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary" style="margin-top: 20px;">Salvar Ocorrência</button>
            </form>
        </div>

    </main>
</body>

</html>