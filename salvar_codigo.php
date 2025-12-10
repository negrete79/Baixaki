<?php
header("Content-Type: application/json");
require_once 'config/db_config.php';

 $data = json_decode(file_get_contents("php://input"));

if (isset($data->codigo) && isset($data->produto)) {
    $codigo = $data->codigo;
    $produto = $data->produto;

    try {
        $stmt = $conn->prepare("INSERT INTO codigos_download (codigo, produto, status) VALUES (?, ?, 'pendente')");
        $stmt->bind_param("ss", $codigo, $produto);
        $stmt->execute();
        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Dados incompletos."]);
}

 $conn->close();
?>
