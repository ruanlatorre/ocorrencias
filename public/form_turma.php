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

$sql_colaboradores = "SELECT id, nome FROM colaborador WHERE status = 'Ativo' ORDER BY nome ASC";
$result_colaboradores = $conn->query($sql_colaboradores);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Turmas - SENAI</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/header.css">
</head>

<body>
    <?php include '../components/nav.php'; ?>

    <main class="main-content">
        <?php include '../components/header.php'; ?>

        <div class="welcome-card" style="border-left: none;">
            <div class="header-actions" style="text-align: center; margin-bottom: 24px;">
                <h2 style="color: var(--text-primary); font-size: 24px; margin: 0;">Cadastrar Nova Turma</h2>
            </div>

            <div id="dynamicAlerts"></div>

            <form onsubmit="submitFormPost(event, 'turma')" style="max-width: 600px; margin: 0 auto;">
                <div class="form-group">
                    <label for="nome_turma">Nome da Turma</label>
                    <input type="text" class="form-control" id="nome_turma" name="nome"
                        placeholder="Ex: Téc. Desenv. Sistemas 1A" required>
                </div>

                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="periodo">Período</label>
                        <select class="form-control" id="periodo" name="periodo" required style="appearance: auto;">
                            <option value="" disabled selected>Selecione...</option>
                            <option value="Manha">Manhã</option>
                            <option value="Tarde">Tarde</option>
                            <option value="Noite">Noite</option>
                            <option value="Integral">Integral</option>
                        </select>
                    </div>
                </div>

                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="data_inicio">Data de Início</label>
                        <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="data_fim">Data de Fim</label>
                        <input type="date" class="form-control" id="data_fim" name="data_fim" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="curso_search">Curso</label>
                    <div class="autocomplete-wrapper">
                        <input type="text" class="form-control" id="curso_search" placeholder="Digite o nome do curso..." required autocomplete="off">
                        <input type="hidden" id="curso_id" name="curso_id">
                        <div id="curso_results" class="autocomplete-results"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="colaborador_search">Colaborador (Responsável)</label>
                    <div class="autocomplete-wrapper">
                        <input type="text" class="form-control" id="colaborador_search" placeholder="Digite o nome do responsável..." autocomplete="off">
                        <input type="hidden" id="colaborador_id" name="colaborador_id">
                        <div id="colaborador_results" class="autocomplete-results"></div>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 20px;">
                    <button type="submit" class="btn-primary" style="margin-top: 0;">Salvar Turma</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
