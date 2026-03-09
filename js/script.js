/* Global Scripts - Ocorrências SENAI */

document.addEventListener('DOMContentLoaded', () => {
    // Modal Access Logic
    const accessModal = document.getElementById('accessModal');
    if (accessModal) {
        window.addEventListener('click', (event) => {
            if (event.target == accessModal) {
                closeAccessModal();
            }
        });
    }

    // Sidebar Toggle Logic
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');

    if (sidebar && toggleBtn) {
        // Load state
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            document.body.classList.add('sidebar-collapsed');
        }

        toggleBtn.addEventListener('click', () => {
            const collapsed = sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed', collapsed);
            localStorage.setItem('sidebarCollapsed', collapsed);

            // Update tooltip
            toggleBtn.title = collapsed ? "Expandir Menu" : "Recolher Menu";
        });
    }

    // Initialize Autocompletes
    initAutocomplete('curso_search', 'curso_results', 'curso', 'curso_id');
    initAutocomplete('colaborador_search', 'colaborador_results', 'colaborador', 'colaborador_id');
    initAutocomplete('turma_search', 'turma_results', 'turma', 'turma_id');
    initAutocomplete('professor_search', 'professor_results', 'colaborador');
    initAutocomplete('nome_aluno_search', 'nome_aluno_results', 'aluno'); // Changed to search in aluno table
    initAutocomplete('setor_search', 'setor_results', 'setor');
});

function openAccessModal() {
    const modal = document.getElementById('accessModal');
    if (!modal) return;

    modal.style.display = 'flex';
    document.getElementById('passwordStep').style.display = 'block';
    document.getElementById('selectionStep').style.display = 'none';
    document.getElementById('viewStep').style.display = 'none';
    document.getElementById('modalTitle').innerText = 'Acesso às Tabelas';
    document.getElementById('accessPassword').value = '';
    document.getElementById('passwordError').style.display = 'none';
    document.body.style.overflow = 'hidden';
}

function closeAccessModal() {
    const modal = document.getElementById('accessModal');
    if (!modal) return;

    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function checkAccessPassword() {
    const password = document.getElementById('accessPassword').value;
    const errorMsg = document.getElementById('passwordError');
    const btn = document.querySelector('#passwordStep .btn-primary');

    if (!password) return;

    // Desabilita botão durante a verificação
    btn.disabled = true;
    btn.innerText = 'Verificando...';

    fetch('../api/verificar_senha_tabelas.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ password: password })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('passwordStep').style.display = 'none';
                document.getElementById('selectionStep').style.display = 'grid';
                document.getElementById('modalTitle').innerText = 'Selecione a Tabela';
            } else {
                errorMsg.innerText = data.error || 'Senha incorreta.';
                errorMsg.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            errorMsg.innerText = 'Erro de conexão com o servidor.';
            errorMsg.style.display = 'block';
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerText = 'Verificar Senha';
        });
}

function viewTable(tableName) {
    const frame = document.getElementById('tableFrame');
    if (!frame) return;

    frame.src = `../tables/${tableName}.php?modal=1`;
    document.getElementById('selectionStep').style.display = 'none';
    document.getElementById('viewStep').style.display = 'block';

    // Capitalize first letter for title
    const nameFormatted = tableName.charAt(0).toUpperCase() + tableName.slice(1);
    document.getElementById('modalTitle').innerText = `Tabela: ${nameFormatted}`;

    // Ajustar width da modal para tabelas maiores
    document.querySelector('.modal-container').style.maxWidth = '1200px';
}

function backToSelection() {
    document.getElementById('viewStep').style.display = 'none';
    document.getElementById('selectionStep').style.display = 'grid';
    document.getElementById('modalTitle').innerText = 'Selecione a Tabela';
    document.querySelector('.modal-container').style.maxWidth = '800px';
}

// REST API Table Actions
function deleteRecord(type, id) {
    if (!confirm(`Tem certeza que deseja excluir este registro de ${type}?`)) return;

    fetch(`../api/processa_${type}.php`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Registro excluído com sucesso!');
                const frame = document.getElementById('tableFrame');
                if (frame) frame.contentWindow.location.reload();
            } else {
                alert('Erro ao excluir: ' + (data.error || 'Erro desconhecido.'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro de conexão ao tentar excluir.');
        });
}

let currentEditType = '';
let currentEditId = null;

function editRecord(type, id) {
    currentEditType = type;
    currentEditId = id;

    fetch(`../api/processa_${type}.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Erro ao buscar dados: ' + data.error);
                return;
            }

            const fieldsContainer = document.getElementById('editFormFields');
            fieldsContainer.innerHTML = '';

            for (const [key, value] of Object.entries(data)) {
                if (key === 'senha' || key === 'created_at') continue;

                const group = document.createElement('div');
                group.className = 'form-group';

                const label = document.createElement('label');
                label.innerText = key.charAt(0).toUpperCase() + key.slice(1).replace(/_/g, ' ');

                const input = document.createElement('input');
                input.className = 'form-control';
                input.name = key;

                if (key === 'id') {
                    input.type = 'text';
                    input.value = value;
                    input.readOnly = true;
                    group.style.display = 'none';
                } else if (key.includes('data')) {
                    input.type = 'date';
                    if (value) input.value = value.split(' ')[0];
                } else {
                    input.type = 'text';
                    if (value !== null) input.value = value;
                }

                group.appendChild(label);
                group.appendChild(input);
                fieldsContainer.appendChild(group);
            }

            const editModal = document.getElementById('editModal');
            if (editModal) {
                editModal.style.display = 'flex';
                document.getElementById('editModalTitle').innerText = `Editar ${type.charAt(0).toUpperCase() + type.slice(1)}`;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro de conexão ao buscar dados.');
        });
}

function closeEditModal() {
    const editModal = document.getElementById('editModal');
    if (editModal) editModal.style.display = 'none';
}

function submitEditForm(event) {
    event.preventDefault();
    const form = document.getElementById('editForm');
    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => { data[key] = value; });

    fetch(`../api/processa_${currentEditType}.php`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Registro atualizado com sucesso!');
                closeEditModal();
                const frame = document.getElementById('tableFrame');
                if (frame) frame.contentWindow.location.reload();
            } else {
                alert('Erro ao atualizar: ' + (result.error || 'Erro desconhecido.'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro de conexão ao tentar atualizar.');
        });
}

function submitFormPost(event, type) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => { data[key] = value; });

    // Hide previous alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => alert.style.display = 'none');

    // Create or get dynamic alert container
    let alertContainer = document.getElementById('dynamicAlerts');
    if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.id = 'dynamicAlerts';
        form.parentNode.insertBefore(alertContainer, form);
    }

    fetch(`../api/processa_${type}.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(result => {
            if (result.success || result.id) {
                alertContainer.innerHTML = '<div class="alert alert-success">Registro cadastrado com sucesso!</div>';
                form.reset();
            } else {
                alertContainer.innerHTML = `<div class="alert alert-error">Erro ao cadastrar: ${result.error || 'Erro desconhecido.'}</div>`;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alertContainer.innerHTML = '<div class="alert alert-error">Erro de conexão com o servidor.</div>';
        });
}

// Theme Logic - SENAI Ocorrências
function applyTheme() {
    const theme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', theme);
    // Use an interval or wait for DOM to ensure icon exists
    const checkIcon = setInterval(() => {
        const icon = document.getElementById('themeIcon');
        if (icon) {
            updateThemeIcon(theme);
            clearInterval(checkIcon);
        }
    }, 50);
}

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    localStorage.setItem('theme', newTheme);
    document.documentElement.setAttribute('data-theme', newTheme);
    updateThemeIcon(newTheme);
}

function updateThemeIcon(theme) {
    const icon = document.getElementById('themeIcon');
    const btn = document.getElementById('themeToggle');
    if (!icon) return;

    if (theme === 'light') {
        icon.innerHTML = '<path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path>';
        if (btn) btn.title = "Mudar para Modo Escuro";
    } else {
        icon.innerHTML = '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>';
        if (btn) btn.title = "Mudar para Modo Claro";
    }
}

// Profile Modal Logic
function openProfileModal() {
    const modal = document.getElementById('profileModal');
    if (modal) modal.style.display = 'flex';
}

function closeProfileModal() {
    const modal = document.getElementById('profileModal');
    if (modal) modal.style.display = 'none';
}

// Global Modal Closer
window.addEventListener('click', function (event) {
    const profileModal = document.getElementById('profileModal');
    const accessModal = document.getElementById('accessModal');
    const editModal = document.getElementById('editModal');

    if (event.target == profileModal) closeProfileModal();
    if (event.target == accessModal) typeof closeAccessModal === 'function' && closeAccessModal();
    if (event.target == editModal) typeof closeEditModal === 'function' && closeEditModal();
});

// Run immediate theme application
applyTheme();

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('tableSearch');
    const tableBody = document.querySelector('table tbody');

    if (searchInput && tableBody) {
        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            const rows = tableBody.querySelectorAll('tr');

            rows.forEach(row => {
                // Ignore empty state rows (like "Nenhum registro encontrado")
                if (row.cells.length === 1 && row.cells[0].colSpan > 1) return;

                const textContent = row.textContent.toLowerCase();
                if (textContent.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});

function submitFormRegistro(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => { data[key] = value; });

    const alertContainer = document.getElementById('dynamicAlerts');

    // Força o status ativo
    data['status'] = 'Ativo';

    fetch(`../api/processa_colaborador.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alertContainer.innerHTML = `<div class="alert alert-success" style="margin-bottom: 20px;">Cadastro realizado com sucesso! Redirecionando...</div>`;
                form.reset();
                setTimeout(() => {
                    window.location.href = '../index.php';
                }, 2000);
            } else {
                alertContainer.innerHTML = `<div class="alert alert-error" style="margin-bottom: 20px;">Erro ao cadastrar: ${result.error || 'Erro desconhecido'}</div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertContainer.innerHTML = `<div class="alert alert-error" style="margin-bottom: 20px;">Erro de comunicação com o servidor.</div>`;
        });
}

// Autocomplete Logic
function initAutocomplete(inputId, resultsId, type, hiddenId = null) {
    const input = document.getElementById(inputId);
    const resultsContainer = document.getElementById(resultsId);
    const hiddenInput = hiddenId ? document.getElementById(hiddenId) : null;

    if (!input || !resultsContainer) return;

    let debounceTimer;

    input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const query = this.value;

        if (query.length < 2) {
            resultsContainer.classList.remove('active');
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`../api/search.php?type=${type}&q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'autocomplete-item';
                            div.textContent = item.label;
                            div.addEventListener('click', () => {
                                input.value = item.label;
                                if (hiddenInput) hiddenInput.value = item.id;
                                resultsContainer.classList.remove('active');
                            });
                            resultsContainer.appendChild(div);
                        });
                        resultsContainer.classList.add('active');
                    } else {
                        resultsContainer.classList.remove('active');
                    }
                });
        }, 300);
    });

    // Close results when clicking outside
    document.addEventListener('click', function (e) {
        if (e.target !== input && e.target !== resultsContainer) {
            resultsContainer.classList.remove('active');
        }
    });
}

function submitLoginForm(event) {
    event.preventDefault();
    const form = event.target;

    // Hide existing alerts if any
    const existingAlerts = document.querySelectorAll('.auth-card .alert');
    existingAlerts.forEach(alert => alert.remove());

    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => { data[key] = value; });

    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Entrando...';

    fetch('auth/verifica_login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Success, redirecting
                window.location.href = result.redirect;
            } else {
                // Error, show alert
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-error';
                alertDiv.textContent = result.error || 'Erro ao fazer login.';
                form.parentNode.insertBefore(alertDiv, form);
                btn.disabled = false;
                btn.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-error';
            alertDiv.textContent = 'Erro de comunicação com o servidor.';
            form.parentNode.insertBefore(alertDiv, form);
            btn.disabled = false;
            btn.textContent = originalText;
        });
}

// ==========================================
// Forgot Password Logic (index.php)
// ==========================================
function openResetModal() {
    const modal = document.getElementById('resetModal');
    if (!modal) return;

    // Reset inputs and messages
    document.getElementById('resetEmail').value = '';
    document.getElementById('resetCode').value = '';
    document.getElementById('resetNewPassword').value = '';
    document.getElementById('resetMsg1').style.display = 'none';
    document.getElementById('resetMsg2').style.display = 'none';
    document.getElementById('resetMsg3').style.display = 'none';
    document.getElementById('devTokenHint').style.display = 'none';

    // Show only the first step
    document.getElementById('resetStep1').style.display = 'block';
    document.getElementById('resetStep2').style.display = 'none';
    document.getElementById('resetStep3').style.display = 'none';
    document.getElementById('resetModalTitle').innerText = 'Redefinir Senha';

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeResetModal() {
    const modal = document.getElementById('resetModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Adiciona resetModal no fechamento global da janela clicando fora do modal
window.addEventListener('click', function (event) {
    const resetModal = document.getElementById('resetModal');
    if (event.target == resetModal) closeResetModal();
});

function backToResetStep1() {
    document.getElementById('resetStep2').style.display = 'none';
    document.getElementById('resetStep1').style.display = 'block';
}

function requestResetCode() {
    const email = document.getElementById('resetEmail').value.trim();
    const msg = document.getElementById('resetMsg1');
    const btn = document.querySelector('#resetStep1 .btn-primary');

    if (!email) {
        msg.className = 'alert alert-error';
        msg.textContent = 'Digite seu e-mail.';
        msg.style.display = 'block';
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Enviando...';
    msg.style.display = 'none';

    // Em uma aplicação real, você deve ajustar o path considerando a rota atual.
    // Considerando que index.php está na raiz, a API está em 'api/forgot_password.php'
    fetch('api/forgot_password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Avança para o Passo 2
                document.getElementById('resetStep1').style.display = 'none';
                document.getElementById('resetStep2').style.display = 'block';
                document.getElementById('resetMsg2').className = 'alert alert-success';
                document.getElementById('resetMsg2').textContent = 'Código enviado para ' + email;
                document.getElementById('resetMsg2').style.display = 'block';
            } else {
                msg.className = 'alert alert-error';
                msg.textContent = data.error || 'Erro ao solicitar código.';
                msg.style.display = 'block';
            }
        })
        .catch(err => {
            msg.className = 'alert alert-error';
            msg.textContent = 'Erro de conexão com o servidor.';
            msg.style.display = 'block';
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Enviar Código';
        });
}

function verifyResetCode() {
    const email = document.getElementById('resetEmail').value.trim();
    const code = document.getElementById('resetCode').value.trim();
    const msg = document.getElementById('resetMsg2');
    const btn = document.querySelector('#resetStep2 .btn-primary');

    if (!code || code.length !== 6) {
        msg.className = 'alert alert-error';
        msg.textContent = 'Digite o código de 6 dígitos.';
        msg.style.display = 'block';
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Verificando...';

    fetch('api/verify_reset_code.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, code })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Avança para o passo 3
                document.getElementById('resetStep2').style.display = 'none';
                document.getElementById('resetStep3').style.display = 'block';
            } else {
                msg.className = 'alert alert-error';
                msg.textContent = data.error || 'Código inválido.';
                msg.style.display = 'block';
            }
        })
        .catch(err => {
            msg.className = 'alert alert-error';
            msg.textContent = 'Erro de conexão com o servidor.';
            msg.style.display = 'block';
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Validar Código';
        });
}

function resetPassword() {
    const email = document.getElementById('resetEmail').value.trim();
    const code = document.getElementById('resetCode').value.trim();
    const newPassword = document.getElementById('resetNewPassword').value.trim();
    const msg = document.getElementById('resetMsg3');
    const btn = document.querySelector('#resetStep3 .btn-primary');

    if (!newPassword || newPassword.length < 4) {
        msg.className = 'alert alert-error';
        msg.textContent = 'A senha deve ter pelo menos 4 caracteres.';
        msg.style.display = 'block';
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Salvando...';

    fetch('api/reset_password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, code, new_password: newPassword })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                msg.className = 'alert alert-success';
                msg.textContent = 'Senha atualizada com sucesso! Você já pode fazer login.';
                msg.style.display = 'block';
                btn.style.display = 'none'; // ocultar botão

                // Fecha após 3 segundos e auto-preenche o e-mail no login
                setTimeout(() => {
                    closeResetModal();
                    document.getElementById('username').value = email;
                    document.getElementById('password').focus();
                }, 3000);
            } else {
                msg.className = 'alert alert-error';
                msg.textContent = data.error || 'Erro ao salvar nova senha.';
                msg.style.display = 'block';
            }
        })
        .catch(err => {
            msg.className = 'alert alert-error';
            msg.textContent = 'Erro de conexão com o servidor.';
            msg.style.display = 'block';
        })
        .finally(() => {
            if (msg.className !== 'alert alert-success') {
                btn.disabled = false;
                btn.textContent = 'Salvar Nova Senha';
            }
        });
}