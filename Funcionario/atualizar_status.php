<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}
if (!isset($_POST['id']) || !isset($_POST['status'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}
$id = (int)$_POST['id'];
$status = $_POST['status'];
$permitidos = ['pendente', 'em_preparo', 'finalizado'];
if (!in_array($status, $permitidos)) {
    echo json_encode(['success' => false, 'message' => 'Status inválido']);
    exit;
}
require_once '../Cliente/conexao.php';
try {
    $stmt = $pdo->prepare('UPDATE pedidos SET status = ? WHERE id = ?');
    $stmt->execute([$status, $id]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar status', 'debug' => $e->getMessage()]);
} 