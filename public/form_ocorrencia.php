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

$sql_turmas = "SELECT id, nome FROM turma ORDER BY nome ASC";
$result_turmas = $conn->query($sql_turmas);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Ocorrência - SENAI</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/header.css">
</head>

<body>

    <?php include '../components/nav.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Topbar -->
        <?php include '../components/header.php'; ?>

        <!-- Formulário de Ocorrência -->
        <div class="welcome-card" style="border-left: none;">
            <div class="header-actions" style="text-align: center; margin-bottom: 24px;">
                <h2 style="color: var(--text-primary); font-size: 24px; margin: 0;">Lançamento de Ocorrência Disciplinar
                </h2>
            </div>

            <div id="dynamicAlerts"></div>

            <form onsubmit="submitFormPost(event, 'ocorrencia')" style="max-width: 600px; margin: 0 auto;">
                <div class="form-group">
                    <label for="nome_aluno_search">Pessoa Envolvida</label>
                    <div class="autocomplete-wrapper">
                        <input type="text" class="form-control" id="nome_aluno_search" name="nome_aluno"
                            placeholder="Nome de quem cometeu a ocorrência" required autocomplete="off">
                        <div id="nome_aluno_results" class="autocomplete-results"></div>
                    </div>
                </div>

                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="turma_search">Turma</label>
                        <div class="autocomplete-wrapper">
                            <input type="text" class="form-control" id="turma_search" name="turma"
                                placeholder="Digite a turma..." required autocomplete="off">
                            <div id="turma_results" class="autocomplete-results"></div>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="professor_search">Professor Responsável</label>
                        <div class="autocomplete-wrapper">
                            <input type="text" class="form-control" id="professor_search" name="professor_responsavel"
                                placeholder="Nome do Professor" required autocomplete="off">
                            <div id="professor_results" class="autocomplete-results"></div>
                        </div>
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

                <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 20px;">
                    <button type="submit" class="btn-primary" style="margin-top: 0;">Salvar Ocorrência</button>
                </div>
            </form>
        </div>

    </main>
</body>

</html>