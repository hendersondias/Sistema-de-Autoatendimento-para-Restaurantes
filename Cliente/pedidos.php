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

function renderPedidos($pedidos, $status) {
    foreach ($pedidos as $pedido) {
        if ($pedido['status'] === $status) {
            echo '<div class="card-pedido" data-id="' . $pedido['id'] . '">';
            echo '<strong>Nº Pedido:</strong> ' . htmlspecialchars($pedido['numero_pedido']) . '<br>';
            echo '<strong>Cliente:</strong> ' . htmlspecialchars($pedido['cliente']);
            echo '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Status do Pedido - Fast Service</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="pedidos.css?=v2">
</head>
<body>
    <header>
        <nav class="nav-bar">
            <div class="nav-left" style="display: flex; align-items: center;">
                <a href="#" class="logo"><i class="fa-solid fa-bowl-food"></i></a>
                <h1 class="name">Fast Service</h1>
            </div>
            <ul class="nav">
                <li><a href="./home.html">Início</a></li>
                <li><a href="./cardapio.php">Cardápio</a></li>
                <li><a href="./pedidos.php">Status dos Pedidos</a></li>
                <li><a href="./ContateNos.php">Contato</a></li>
                <li><a href="./SobreNos.html">Sobre Nós</a></li>
                <li><a href="./carrinho.php">Carrinho de Compras</a></li>
            </ul>
        </nav>
    </header>
    <h1 style="color:#2781d6; text-align:center; margin-top:180px; margin-bottom:0;">Status do Pedido</h1>
    <div class="container-pedidos">
        <div class="coluna-pedidos" id="pendente">
            <h2>Pendentes</h2>
            <?php renderPedidos($pedidos_completos, 'pendente'); ?>
        </div>
        <div class="coluna-pedidos" id="em_preparo">
            <h2>Em preparo</h2>
            <?php renderPedidos($pedidos_completos, 'em_preparo'); ?>
        </div>
        <div class="coluna-pedidos" id="finalizado">
            <h2>Finalizados</h2>
            <?php renderPedidos($pedidos_completos, 'finalizado'); ?>
        </div>
    </div>
    <script src="pedidos.js"></script>
</body>
</html> 