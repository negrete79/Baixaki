<?php
// Inicia o buffer de saída para capturar qualquer erro antes de enviar o arquivo
ob_start();

// Inclui o arquivo de configuração
require_once 'config/db_config.php';

// Pega o código da URL e limpa para evitar problemas
 $codigo = isset($_GET['codigo']) ? trim($_GET['codigo']) : '';

if (empty($codigo)) {
    die("Erro: Nenhum código de acesso fornecido.");
}

try {
    // Prepara a consulta para evitar SQL Injection
    $stmt = $conn->prepare("SELECT produto, status FROM codigos_download WHERE codigo = ?");
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Erro: Código de download inválido ou não encontrado.");
    }

    $row = $result->fetch_assoc();

    // VERIFICAÇÃO DE SEGURANÇA CHAVE
    if ($row['status'] !== 'ativo') {
        die("Erro: Este código ainda não foi ativado. Verifique o pagamento e contate o suporte.");
    }

    // Se chegou aqui, o código é válido e está ATIVO!
    $produto = $row['produto'];
    $caminhoArquivo = '';

    // Mapeia o produto ao arquivo correspondente
    // __DIR__ garante que o caminho seja sempre relativo a este arquivo PHP
    switch ($produto) {
        case 'Unitvfree':
            $caminhoArquivo = __DIR__ . '/arquivos/unitvfree.apk';
            break;
        case 'YouTube':
            $caminhoArquivo = __DIR__ . '/arquivos/youtube_mod.apk';
            break;
        case 'X-plore':
            $caminhoArquivo = __DIR__ . '/arquivos/x-plore.apk';
            break;
        default:
            die("Erro: Produto associado ao código não encontrado.");
    }

    // Verifica se o arquivo existe fisicamente no servidor
    if (!file_exists($caminhoArquivo)) {
        die("Erro: O arquivo para download não foi encontrado no servidor.");
    }

    // LIMPA O BUFFER ANTES DE ENVIAR O ARQUIVO
    // Se houve algum aviso, ele será descartado aqui.
    ob_end_clean();

    // Envia os cabeçalhos para forçar o download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($caminhoArquivo) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($caminhoArquivo));

    // Lê o arquivo e o envia para o navegador
    readfile($caminhoArquivo);
    exit;

} catch (Exception $e) {
    // Em caso de qualquer erro com o banco de dados, exibe a mensagem
    die("Ocorreu um erro no servidor: " . $e->getMessage());
} finally {
    // Fecha a conexão com o banco de dados
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?>
