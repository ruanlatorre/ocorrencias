<?php
session_start();
require_once '../config/conexao.php';

if (!isset($_SESSION['user_logado'])) {
    header('Location: ../index.php');
    exit;
}

$conn = conexaoBanco();
$is_modal = isset($_GET['modal']);

$sql = "SELECT * FROM colaborador ORDER BY nome ASC";
$result = $conn->query($sql);
?>
<head>
    <meta charset="UTF-8">
    <title>Tabela de Colaboradores</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="<?php echo $is_modal ? 'is-modal' : ''; ?>">
    <div class="search-container">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="text" id="tableSearch" placeholder="Pesquisar colaborador por nome, nif, setor ou email...">
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>NIF</th>
                    <th>Email</th>
                    <th>Setor</th>
                    <th>Permissão</th>
                    <th>Status</th>
                    <th>IP</th>
                    <th style="text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['nome']); ?></td>
                            <td><?php echo htmlspecialchars($row['nif']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['setor']); ?></td>
                            <td><span class="badge badge-<?php echo $row['permissao']; ?>"><?php echo $row['permissao']; ?></span></td>
                            <td><span class="status-<?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
                            <td><?php echo htmlspecialchars($row['ip_address'] ?? '—'); ?></td>
                            <td style="text-align: center; white-space: nowrap;">
                                <button onclick="window.parent.editRecord('colaborador', <?php echo $row['id']; ?>)" class="btn-icon" title="Editar">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                </button>
                                <button onclick="window.parent.deleteRecord('colaborador', <?php echo $row['id']; ?>)" class="btn-icon btn-icon-delete" title="Excluir">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9" style="text-align: center;">Nenhum colaborador cadastrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="../js/script.js"></script>
</body>
</html>
