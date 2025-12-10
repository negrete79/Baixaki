<?php
header("Content-Type: application/json");
require_once 'config/db_config.php';

 $data = json_decode(file_get_contents("php://input"));

if (isset($data->codigo) && isset($data->produto)) {
    $codigo = $data->codigo;
    $produto = $data->produto;

    $stmt = $conn->prepare("INSERT INTO codigos_download (codigo, produto, status) VALUES (?, ?, 'pendente')");
    $stmt->bind_param("ss", $codigo, $produto);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao salvar no BD."]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Dados incompletos."]);
}

 $conn->close();
?>
