<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Ocorrências SENAI</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <span class="senai-logo">SENAI</span>
                <p style="color: var(--text-secondary); margin-top: 10px; font-size: 14px;">Criar Conta de Acesso</p>
            </div>

            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'empty') {
                    echo '<div class="alert alert-error">Por favor, preencha todos os campos.</div>';
                } elseif ($_GET['error'] == 'true') {
                    echo '<div class="alert alert-error">Erro ao cadastrar usuário. Tente novamente ou use outro nome de usuário.</div>';
                }
            }
            ?>

            <form action="../auth/processa_cadastro.php" method="post">
                <div class="form-group">
                    <label for="username">Nome de Usuário</label>
                    <input type="text" class="form-control" id="username" name="username"
                        placeholder="Escolha um usuário" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Crie uma senha" required>
                </div>
                <button type="submit" class="btn-primary">Confirmar Cadastro</button>
            </form>
            <div class="auth-links">
                <a href="../index.php">&larr; Voltar para o Login</a>
            </div>
        </div>
    </div>
</body>

</html>