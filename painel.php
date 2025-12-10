<?php
require_once 'config/db_config.php';

// Lógica para ATIVAR um código
if (isset($_GET['ativar'])) {
    $id_codigo = $_GET['ativar'];
    $stmt = $conn->prepare("UPDATE codigos_download SET status = 'ativo' WHERE id = ?");
    $stmt->bind_param("i", $id_codigo);
    $stmt->execute();
    header("Location: painel.php"); // Recarrega a página para ver a mudança
    exit();
}

// Lógica para LISTAR os códigos
 $result = $conn->query("SELECT id, produto, codigo, status, data_criacao FROM codigos_download ORDER BY data_criacao DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel de Controle</title>
    <style>body{font-family:sans-serif;max-width:900px;margin:20px auto;}table{width:100%;border-collapse:collapse;}th,td{border:1px solid #ddd;padding:8px;text-align:left;}th{background-color:#f2f2f2;}</style>
</head>
<body>
    <h1>Painel de Controle - Liberação de Downloads</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Produto</th>
            <th>Código</th>
            <th>Status</th>
            <th>Data de Criação</th>
            <th>Ação</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['produto']); ?></td>
            <td><?php echo htmlspecialchars($row['codigo']); ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['data_criacao']; ?></td>
            <td>
                <?php if ($row['status'] == 'pendente'): ?>
                    <a href="painel.php?ativar=<?php echo $row['id']; ?>">Ativar Download</a>
                <?php else: ?>
                    <span style="color:green;">✅ Ativo</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
