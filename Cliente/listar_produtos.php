<?php
require_once 'conexao.php';
header('Content-Type: application/json');
try {
    $stmt = $pdo->query("SELECT p.*, c.nome AS categoria_nome FROM produtos p JOIN categorias c ON p.categoria_id = c.id ORDER BY c.id, p.nome");
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'produtos' => $produtos]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao carregar produtos', 'debug' => $e->getMessage()]);
} 