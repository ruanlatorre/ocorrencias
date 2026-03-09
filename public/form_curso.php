<?php
session_start();
require_once '../config/conexao.php';
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
    <title>Gerenciar Cursos - SENAI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="../css/main.css">
</head>

<body>
    <?php include '../components/nav.php'; ?>

    <main class="main-content">
        <?php include '../components/header.php'; ?>

        <div class="welcome-card" style="border-left: none;">
            <div class="header-actions" style="text-align: center; margin-bottom: 24px;">
                <h2 style="color: var(--text-primary); font-size: 24px; margin: 0;">Cadastrar Novo Curso</h2>
            </div>

            <div id="dynamicAlerts"></div>

            <form onsubmit="submitFormPost(event, 'curso')" style="max-width: 600px; margin: 0 auto;">
                <div class="form-group">
                    <label for="nome_curso">Nome do Curso</label>
                    <input type="text" class="form-control" id="nome_curso" name="nome_curso"
                        placeholder="Nome completo do curso" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required style="appearance: auto;">
                        <option value="Ativo" selected>Ativo</option>
                        <option value="Inativo">Inativo</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 20px;">
                    <button type="submit" class="btn-primary" style="margin-top: 0;">Salvar Curso</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>