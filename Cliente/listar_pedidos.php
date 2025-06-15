<?php
require_once 'conexao.php';

// Buscar todos os pedidos com nome do cliente
$sql = "SELECT p.id, p.numero_pedido, p.status, c.nome AS cliente_nome
        FROM pedidos p
        JOIN clientes c ON p.cliente_id = c.id
        WHERE p.oculto = 0
        ORDER BY p.data DESC, p.id DESC";
$stmt = $pdo->query($sql);
$pedidos = $stmt->fetchAll();

// Buscar itens de cada pedido
$pedidos_completos = [];
foreach ($pedidos as $pedido) {
    $sqlItens = "SELECT ip.quantidade, pr.nome AS produto_nome, ip.preco_unitario
                 FROM itens_pedido ip
                 JOIN produtos pr ON ip.produto_id = pr.id
                 WHERE ip.pedido_id = ?";
    $stmtItens = $pdo->prepare($sqlItens);
    $stmtItens->execute([$pedido['id']]);
    $itens = $stmtItens->fetchAll();
    $descricao = [];
    $valor_total = 0;
    foreach ($itens as $item) {
        $descricao[] = $item['quantidade'] . 'x ' . $item['produto_nome'];
        $valor_total += $item['quantidade'] * $item['preco_unitario'];
    }
    $pedidos_completos[] = [
        'id' => $pedido['id'],
        'numero_pedido' => $pedido['numero_pedido'],
        'cliente' => $pedido['cliente_nome'],
        'descricao' => implode(', ', $descricao),
        'valor' => number_format($valor_total, 2, ',', '.'),
        'status' => $pedido['status']
    ];
}

header('Content-Type: application/json');
echo json_encode(['success' => true, 'pedidos' => $pedidos_completos]); 