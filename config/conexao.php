<?php
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "ocorrencias");
define("DB_PORT", "3308");

function conexaoBanco()
{
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if (!$conn) {
        die("Erro de conexão: " . mysqli_connect_error());
    }
    return $conn;
}
?>