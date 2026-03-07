<?php
session_start();
require_once '../config/conexao.php';
$is_modal = isset($_GET['modal']) && $_GET['modal'] == '1';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela de Alunos - SENAI</title>
    <link rel="stylesheet" href="../css/style.css">
    <?php if (!$is_modal): ?>
        <link rel="stylesheet" href="../css/header.css">
    <?php else: ?>
        <style>
            body {
                background-color: var(--bg-primary);
                padding: 20px;
                display: block;
                height: 100vh;
                margin: 0;
            }

            .main-content {
                padding: 0;
            }

            .table-container {
                box-shadow: none;
                border: 1px solid var(--border-color);
            }
        </style>
    <?php endif; ?>
</head>

<body>
    <?php if (!$is_modal)
        include '../components/nav.php'; ?>

    <main class="<?php echo $is_modal ? '' : 'main-content'; ?>">
        <?php if (!$is_modal)
            include '../components/header.php'; ?>

        <div class="table-container">
            <div class="table-header">
                <h3>Lista de Alunos</h3>
                <div class="search-bar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" width="18" height="18">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="tableSearch" placeholder="Pesquisar aluno...">
                </div>
            </div>

            <table class="senai-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Matrícula</th>
                        <th>Data Entrada</th>
                        <th>Data Saída</th>
                        <th>Curso</th>
                        <th>Turma</th>
                        <th style="min-width: 80px; text-align: right;">Ações</th>
                    </tr>
                </thead>
                <tbody id="alunoTableBody">
                    <tr>
                        <td colspan="7" style="text-align: center;">Carregando dados...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal de Edição Genérico Injetado pelo JS se necessário -->

    <script src="../js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetch('../api/processa_aluno.php')
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('alunoTableBody');
                    tbody.innerHTML = '';

                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">Nenhum aluno cadastrado.</td></tr>';
                        return;
                    }

                    data.forEach(aluno => {
                        const tr = document.createElement('tr');
                        const dataEntrada = aluno.data_entrada.split('-').reverse().join('/');
                        const dataSaida = aluno.data_saida ? aluno.data_saida.split('-').reverse().join('/') : '-';

                        tr.innerHTML = `
                            <td>${aluno.nome}</td>
                            <td>${aluno.matricula}</td>
                            <td>${dataEntrada}</td>
                            <td>${dataSaida}</td>
                            <td>${aluno.curso_nome || '-'}</td>
                            <td>${aluno.turma_nome || '-'}</td>
                            <td class="action-buttons">
                                <button class="btn-icon" onclick="editRecord('aluno', ${aluno.id})" title="Editar">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
                                <button class="btn-icon delete" onclick="deleteRecord('aluno', ${aluno.id})" title="Excluir">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(err => {
                    document.getElementById('alunoTableBody').innerHTML = '<tr><td colspan="7" style="text-align: center; color: red;">Erro ao carregar dados.</td></tr>';
                    console.error(err);
                });
        });
    </script>
</body>

</html>