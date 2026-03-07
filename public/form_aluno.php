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

$sql_cursos = "SELECT id, nome FROM curso WHERE status = 'Ativo' ORDER BY nome ASC";
$result_cursos = $conn->query($sql_cursos);

$sql_turmas = "SELECT id, nome FROM turma ORDER BY nome ASC";
$result_turmas = $conn->query($sql_turmas);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Alunos - SENAI</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/header.css">
</head>

<body>
    <?php include '../components/nav.php'; ?>

    <main class="main-content">
        <?php include '../components/header.php'; ?>

        <div class="welcome-card" style="border-left: none;">
            <div class="header-actions" style="text-align: center; margin-bottom: 24px;">
                <h2 style="color: var(--text-primary); font-size: 24px; margin: 0;">Cadastrar Novo Aluno</h2>
            </div>

            <div id="dynamicAlerts"></div>

            <form onsubmit="submitFormPost(event, 'aluno')" style="max-width: 600px; margin: 0 auto;">
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome completo do aluno"
                        required>
                </div>

                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="matricula">Matrícula (RM)</label>
                        <input type="text" class="form-control" id="matricula" name="matricula" placeholder="Ex: 55734"
                            required>
                    </div>
                </div>

                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="data_entrada">Data de Entrada</label>
                        <input type="date" class="form-control" id="data_entrada" name="data_entrada" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="data_saida">Data de Saída (Opcional)</label>
                        <input type="date" class="form-control" id="data_saida" name="data_saida">
                    </div>
                </div>

                <div class="form-group">
                    <label for="curso_search">Curso</label>
                    <div class="autocomplete-wrapper">
                        <input type="text" class="form-control" id="curso_search"
                            placeholder="Digite o nome do curso..." required autocomplete="off">
                        <input type="hidden" id="curso_id" name="curso_id">
                        <div id="curso_results" class="autocomplete-results"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="turma_search">Turma</label>
                    <div class="autocomplete-wrapper">
                        <input type="text" class="form-control" id="turma_search" placeholder="Digite a turma..."
                            autocomplete="off">
                        <input type="hidden" id="turma_id" name="turma_id">
                        <div id="turma_results" class="autocomplete-results"></div>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 20px;">
                    <button type="submit" class="btn-primary" style="margin-top: 0;">Salvar Aluno</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>