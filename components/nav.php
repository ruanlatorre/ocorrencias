<?php
// Define a página atual para destacar o menu
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<aside class="sidebar">
    <script>
        // Inicialização crítica para evitar FOUC (pulo visual)
        (function () {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                document.querySelector('.sidebar').classList.add('collapsed');
                document.body.classList.add('sidebar-collapsed');
            }
        })();
    </script>
    <div class="sidebar-header">
        <span class="sidebar-logo">SENAI</span>
        <button id="toggleSidebar" class="btn-toggle-sidebar" title="Recolher Menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" width="20" height="20">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
    </div>

    <div class="sidebar-menu">
        <a href="dashboard.php" class="menu-item <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span>Home / Dashboard</span>
        </a>
        <a href="form_ocorrencia.php"
            class="menu-item <?php echo ($current_page == 'form_ocorrencia.php') ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            <span>Registrar Ocorrência</span>
        </a>
        <a href="form_curso.php" class="menu-item <?php echo ($current_page == 'form_curso.php') ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
            <span>Cursos</span>
        </a>
        <a href="form_turma.php" class="menu-item <?php echo ($current_page == 'form_turma.php') ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span>Turmas</span>
        </a>

        <a href="form_colaborador.php"
            class="menu-item <?php echo ($current_page == 'form_colaborador.php') ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <polyline points="17 11 19 13 23 9"></polyline>
            </svg>
            <span>Colaboradores</span>
        </a>

        <a href="form_aluno.php" class="menu-item <?php echo ($current_page == 'form_aluno.php') ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span>Alunos</span>
        </a>

        <!-- Botão de Tabelas (Novo) -->
        <a href="javascript:void(0)" class="menu-item special" onclick="openAccessModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="3" y1="9" x2="21" y2="9"></line>
                <line x1="9" y1="21" x2="9" y2="9"></line>
            </svg>
            <span>Visualizar Tabelas</span>
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="footer-actions">
            <button title="Alternar Tema" id="themeToggle" onclick="toggleTheme()">
                <svg id="themeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" width="20" height="20">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </button>
            <button title="Suporte">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" width="20" height="20">
                    <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                    <path
                        d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z">
                    </path>
                </svg>
            </button>
            <button title="Perfil" onclick="openProfileModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" width="20" height="20">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </button>
        </div>
        <form action="../auth/logout.php" method="post" style="width: 100%;">
            <button class="btn-logout" type="submit">
                <span>Sair</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" width="18" height="18">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </button>
        </form>
    </div>
</aside>

<!-- Modal de Acesso Único -->
<div id="accessModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="modalTitle">Acesso às Tabelas</h3>
            <span class="modal-close" onclick="closeAccessModal()">&times;</span>
        </div>
        <div class="modal-content">
            <div id="passwordStep" class="password-step">
                <p>Digite a senha de administrador para acessar os dados.</p>
                <div class="form-group">
                    <input type="password" id="accessPassword" class="form-control" placeholder="Senha de acesso"
                        onkeyup="if(event.key === 'Enter') checkAccessPassword()">
                </div>
                <button class="btn-primary" onclick="checkAccessPassword()">Verificar Senha</button>
                <div id="passwordError" class="alert alert-error" style="display: none; margin-top: 20px;">
                    Senha incorreta.
                </div>
            </div>

            <div id="selectionStep" class="table-selection-step" style="display: none;">
                <button class="table-btn" onclick="viewTable('ocorrencia')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Ocorrências</span>
                </button>

                <button class="table-btn" onclick="viewTable('curso')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    <span>Cursos</span>
                </button>
                <button class="table-btn" onclick="viewTable('turma')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span>Turmas</span>
                </button>
                <button class="table-btn" onclick="viewTable('colaborador')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <polyline points="17 11 19 13 23 9"></polyline>
                    </svg>
                    <span>Colaboradores</span>
                </button>
                <button class="table-btn" onclick="viewTable('aluno')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span>Alunos</span>
                </button>
            </div>

            <div id="viewStep" class="table-view-container" style="display: none;">
                <iframe id="tableFrame" src=""></iframe>
                <button class="btn-primary" onclick="backToSelection()" style="margin-top: 20px;">Voltar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição Genérico -->
<div id="editModal" class="modal-overlay" style="z-index: 2500;">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 id="editModalTitle">Editar Registro</h3>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>
        <div class="modal-content">
            <form id="editForm" onsubmit="submitEditForm(event)">
                <div id="editFormFields"
                    style="max-height: 50vh; overflow-y: auto; padding-right: 10px; margin-bottom: 20px;"></div>
                <button type="submit" class="btn-primary" style="width: 100%;">Salvar Alterações</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Perfil do Usuário -->
<div id="profileModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 450px;">
        <div class="modal-header">
            <h3>Informações do Perfil</h3>
            <span class="modal-close" onclick="closeProfileModal()">&times;</span>
        </div>
        <div class="modal-content">
            <div class="profile-info-grid">
                <div class="info-item">
                    <label>Nome Completo</label>
                    <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                </div>
                <div class="info-item">
                    <label>NIF / Identificação</label>
                    <p><?php echo htmlspecialchars($_SESSION['nif'] ?? 'N/A'); ?></p>
                </div>
                <div class="info-item">
                    <label>E-mail Corporativo</label>
                    <p><?php echo htmlspecialchars($_SESSION['user_email'] ?? 'N/A'); ?></p>
                </div>
                <div class="info-item">
                    <label>Cargo / Permissão</label>
                    <p><?php echo htmlspecialchars($_SESSION['role'] === 'aluno' ? 'Aluno' : ($_SESSION['permissao'] ?? 'Colaborador')); ?>
                    </p>
                </div>
            </div>
            <button class="btn-primary" onclick="closeProfileModal()"
                style="width: 100%; margin-top: 24px;">Fechar</button>
        </div>
    </div>
</div>

<!-- Scripts Globais -->
<script src="../js/script.js"></script>