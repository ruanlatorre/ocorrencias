<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Colaborador</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="auth-container">
        <div class="auth-card" style="max-width: 500px; padding: 2.5rem;">
            <div class="auth-header">
                <span class="senai-logo">SENAI</span>
                <p style="color: var(--text-secondary); margin-top: 10px; font-size: 14px;">Solicitação de Acesso -
                    Colaborador</p>
            </div>

            <div id="dynamicAlerts"></div>

            <form onsubmit="submitFormRegistro(event)" id="formRegistro">
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite seu nome completo"
                        required>
                </div>
                <div class="grid-cards" style="margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="nif">NIF</label>
                        <input type="text" class="form-control" id="nif" name="nif" placeholder="Seu NIF" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="setor_search">Setor</label>
                        <div class="autocomplete-wrapper">
                            <input type="text" class="form-control" id="setor_search" name="setor"
                                placeholder="Ex: Coordenação" required autocomplete="off">
                            <div id="setor_results" class="autocomplete-results"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">E-mail Corporativo</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="nome@sp.senai.br"
                        required>
                </div>
                <div class="form-group">
                    <label for="permissao">Perfil / Cargo</label>
                    <select class="form-control" id="permissao" name="permissao" required>
                        <option value="" disabled selected>Selecione seu cargo</option>
                        <option value="Professor">Professor</option>
                        <option value="Gestor">Gestor</option>
                        <option value="Admin">Administrador</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Crie uma senha"
                        required>
                </div>

                <button type="submit" class="btn-primary" style="margin-top: 10px;">Cadastrar</button>
            </form>
            <div class="auth-links" style="margin-top: 20px;">
                <a href="../index.php">Já tem uma conta? Fazer Login</a>
            </div>
        </div>
    </div>

    <script src="../js/script.js"></script>
</body>

</html>