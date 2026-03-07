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

            <form onsubmit="submitLoginForm(event)">
                <div class="form-group">
                    <label for="username">E-mail ou NIF</label>
                    <input type="text" class="form-control" id="username" name="username"
                        placeholder="Ex: seu.nome@sp.senai.br ou seu NIF" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Digite sua senha" required>
                </div>
                <button type="submit" class="btn-primary">Entrar no Sistema</button>
            </form>
            <div class="auth-links">
                <a href="#" onclick="openResetModal(); return false;">Esqueci minha senha</a>
                <a href="public/form_cadastro_colaborador.php">Sou Colaborador e não tenho conta</a>
            </div>
        </div>
    </div>

    <!-- Modal de Redefinição de Senha -->
    <div id="resetModal" class="modal-overlay">
        <div class="modal-container" style="max-width: 400px;">
            <div class="modal-header">
                <h3 id="resetModalTitle">Redefinir Senha</h3>
                <span class="modal-close" onclick="closeResetModal()">&times;</span>
            </div>
            <div class="modal-content">
                <!-- Passo 1: Informar E-mail -->
                <div id="resetStep1" class="password-step">
                    <p>Informe seu e-mail para receber um código de 6 dígitos.</p>
                    <div class="form-group">
                        <input type="email" id="resetEmail" class="form-control" placeholder="Seu e-mail cadastrado">
                    </div>
                    <div id="resetMsg1" class="alert" style="display:none; font-size:14px; margin-bottom:15px;"></div>
                    <button class="btn-primary" onclick="requestResetCode()">Enviar Código</button>
                    <div id="devTokenHint"
                        style="margin-top: 15px; font-size: 13px; color: var(--accent-red); display:none;"></div>
                </div>

                <!-- Passo 2: Digitar Código -->
                <div id="resetStep2" class="password-step" style="display: none;">
                    <p>Digite o código de 6 dígitos que você recebeu.</p>
                    <div class="form-group">
                        <input type="text" id="resetCode" class="form-control" placeholder="000000" maxlength="6"
                            style="text-align: center; letter-spacing: 5px; font-size: 20px;">
                    </div>
                    <div id="resetMsg2" class="alert" style="display:none; margin-bottom:15px; font-size:14px;"></div>
                    <button class="btn-primary" onclick="verifyResetCode()">Validar Código</button>
                    <button class="btn-secondary" onclick="backToResetStep1()"
                        style="margin-top: 10px; width: 100%; background: transparent; color: var(--text-secondary); border: none; cursor: pointer;">Voltar</button>
                </div>

                <!-- Passo 3: Nova Senha -->
                <div id="resetStep3" class="password-step" style="display: none;">
                    <p>Crie uma nova senha para o seu acesso.</p>
                    <div class="form-group">
                        <input type="password" id="resetNewPassword" class="form-control" placeholder="Nova senha">
                    </div>
                    <div id="resetMsg3" class="alert" style="display:none; margin-bottom:15px; font-size:14px;"></div>
                    <button class="btn-primary" onclick="resetPassword()">Salvar Nova Senha</button>
                </div>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>

</html>