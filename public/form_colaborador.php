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
    <title>Gerenciar Colaboradores - SENAI</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/header.css">
</head>

<body>
    <?php include '../components/nav.php'; ?>

    <main class="main-content">
        <?php include '../components/header.php'; ?>

        <div class="welcome-card" style="border-left: none;">
            <div class="header-actions" style="text-align: center; margin-bottom: 24px;">
                <h2 style="color: var(--text-primary); font-size: 24px; margin: 0;">Cadastrar Novo Colaborador</h2>
            </div>

            <div id="dynamicAlerts"></div>

            <form onsubmit="submitFormPost(event, 'colaborador')" style="max-width: 600px; margin: 0 auto;">

                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do colaborador"
                        required>
                </div>

                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="nif">NIF</label>
                        <input type="text" class="form-control" id="nif" name="nif"
                            placeholder="Número de Identificação" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="setor">Setor</label>
                        <input type="text" class="form-control" id="setor" name="setor"
                            placeholder="Ex: Coordenação, T.I" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="email@senai.br"
                        required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha de Acesso</label>
                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Crie uma senha"
                        required>
                </div>

                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="permissao">Permissão</label>
                        <select class="form-control" id="permissao" name="permissao" required style="appearance: auto;">
                            <option value="Usuario" selected>Usuário Comum</option>
                            <option value="Professor">Professor</option>
                            <option value="Admin">Administrador</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required style="appearance: auto;">
                            <option value="Ativo" selected>Ativo</option>
                            <option value="Inativo">Inativo</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 20px;">
                    <button type="submit" class="btn-primary" style="margin-top: 0;">Salvar Colaborador</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>