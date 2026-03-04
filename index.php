<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ocorrências SENAI</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <span class="senai-logo">SENAI</span>
                <p style="color: var(--text-secondary); margin-top: 10px; font-size: 14px;">Acesso ao Sistema de
                    Ocorrências</p>
            </div>

            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'empty') {
                    echo '<div class="alert alert-error">Preencha todos os campos.</div>';
                } elseif ($_GET['error'] == 'invalid') {
                    echo '<div class="alert alert-error">Usuário ou senha incorretos.</div>';
                }
            }
            if (isset($_GET['success']) && $_GET['success'] == 'registered') {
                echo '<div class="alert alert-success">Cadastro realizado com sucesso! Faça login.</div>';
            }
            ?>

            <form action="auth/verifica_login.php" method="post">
                <div class="form-group">
                    <label for="username">Usuário</label>
                    <input type="text" class="form-control" id="username" name="username"
                        placeholder="Digite seu usuário" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Digite sua senha" required>
                </div>
                <button type="submit" class="btn-primary">Entrar no Sistema</button>
            </form>
            <div class="auth-links">
                <a href="public/form_cadastro.php">Não tem uma conta? Solicitar Acesso</a>
            </div>
        </div>
    </div>
</body>

</html>