<?php
require_once 'config/db_config.php';

 $codigo = $_GET['codigo'];

 $stmt = $conn->prepare("SELECT produto, status FROM codigos_download WHERE codigo = ?");
 $stmt->bind_param("s", $codigo);
 $stmt->execute();
 $result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['status'] == 'ativo') {
        $produto = $row['produto'];
        $caminhoArquivo = '';

        switch ($produto) {
            case 'Unitvfree':
                $caminhoArquivo = 'arquivos/unitvfree.apk';
                break;
            case 'YouTube':
                $caminhoArquivo = 'arquivos/youtube_mod.apk';
                break;
            case 'X-plore':
                $caminhoArquivo = 'arquivos/x-plore.apk';
                break;
            default:
                die("Produto não encontrado.");
        }
        
        if (file_exists($caminhoArquivo)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($caminhoArquivo).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($caminhoArquivo));
            readfile($caminhoArquivo);
            exit;
        } else {
            echo "Arquivo do produto não encontrado no servidor.";
        }
    } else {
        echo "Este código ainda não foi ativado. Verifique o pagamento.";
    }
} else {
    echo "Código de download inválido.";
}

 $stmt->close();
 $conn->close();
?>
