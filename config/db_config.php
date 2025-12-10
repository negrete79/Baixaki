<?php
// Configurações de conexão com o banco de dados
// Mude estes valores quando colocar no seu servidor de hospedagem
 $servidor = "localhost";
 $usuario = "root";
 $senha = "";
 $banco = "sistema_downloads";

// Ativa o relatório de erros do MySQLi para facilitar a depuração
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Cria a conexão
 $conn = new mysqli($servidor, $usuario, $senha, $banco);

// Define o charset para utf8mb4
 $conn->set_charset("utf8mb4");
?>
