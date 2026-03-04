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
    <title>Dashboard - Ocorrências SENAI</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

    <?php include '../components/nav.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Topbar -->
        <?php include '../components/header.php'; ?>

        <!-- Welcome Card -->
        <div class="welcome-card">
            <h2>Bem-vindo ao Sistema de Ocorrências SENAI</h2>
            <p>Sistema desenvolvido para registrar, monitorar e gerenciar ocorrências disciplinares de forma
                centralizada e eficiente, garantindo um ambiente educacional mais organizado e focado na gestão docente.
            </p>
        </div>

        <!-- Info Cards -->
        <div class="grid-cards">
            <!-- Card 1 -->
            <div class="info-card">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" width="24" height="24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <path d="M9 15l2 2 4-4"></path>
                    </svg>
                </div>
                <h3>Gestão de Ocorrências</h3>
                <p>Registre e acompanhe incidentes de forma rápida. O sistema unifica dados de turmas e colaboradores
                    para um controle disciplinar completo e transparente.</p>
            </div>

            <!-- Card 2 -->
            <div class="info-card">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" width="24" height="24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        <path d="M9 12l2 2 4-4"></path>
                    </svg>
                </div>
                <h3>Controle Educacional</h3>
                <p>Mantenha o histórico disciplinar atualizado, facilite a comunicação com os responsáveis e promova um
                    ambiente de aprendizado seguro e de qualidade.</p>
            </div>
        </div>
    </main>

</body>

</html>