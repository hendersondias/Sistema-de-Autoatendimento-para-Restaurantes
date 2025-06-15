<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}
if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID não informado']);
    exit;
}
$id = (int)$_POST['id'];
require_once '../Cliente/conexao.php';
try {
    $stmt = $pdo->prepare('UPDATE pedidos SET oculto = 1 WHERE id = ?');
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao ocultar pedido', 'debug' => $e->getMessage()]);
} 