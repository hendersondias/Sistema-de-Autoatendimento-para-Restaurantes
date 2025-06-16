<?php
session_start();
if (!isset($_SESSION['funcionario_id'])) {
    header('Location: login.php');
    exit;
}
$nome = $_SESSION['funcionario_nome'] ?? 'Funcionário';

require_once '../Cliente/conexao.php';

// Buscar todos os pedidos com nome do cliente
echo "<!-- debug: conectou ao banco -->";
$sql = "SELECT p.id, p.numero_pedido, p.status, c.nome AS cliente_nome
        FROM pedidos p
        JOIN clientes c ON p.cliente_id = c.id
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
            echo '<div class="card-pedido" draggable="true" data-id="' . $pedido['id'] . '">';
            echo '<strong>Nº Pedido:</strong> ' . htmlspecialchars($pedido['numero_pedido']) . '<br>';
            echo '<strong>Cliente:</strong> ' . htmlspecialchars($pedido['cliente']) . '<br>';
            echo '<strong>Pedido:</strong> ' . htmlspecialchars($pedido['descricao']) . '<br>';
            echo '<strong>Valor:</strong> R$ ' . htmlspecialchars($pedido['valor']);
            echo '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedidos - Fast Service</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Login.css?=v2">
    <link rel="stylesheet" href="painel.css?=v2">
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
                <li><a href="./painel.php">Início</a></li>
                <li><a href="./pedidos.php">Pedidos</a></li>
                <li><a href="./Cardapio.php">Cardápio</a></li>
                <li><a href="./avaliacoes.php">Feedbacks</a></li>
            </ul>
            <div class="nav-right" style="display: flex; gap: 10px; align-items: center;">
                <span class="btn" style="background:#222; color:#fff; cursor:default;">Olá, <?php echo htmlspecialchars($nome); ?></span>
                <a href="logout.php" class="btn" style="background:#222; color:#fff;">Sair</a>
            </div>
        </nav>
    </header>
    <h1 style="color:#2781d6; text-align:center; margin-top:30px;">Painel de Pedidos</h1>
    <div class="container-pedidos">
        <div class="coluna-pedidos" id="pendente" ondragover="allowDrop(event)" ondrop="drop(event, 'pendente')">
            <h2>Pendentes</h2>
            <?php renderPedidos($pedidos_completos, 'pendente'); ?>
        </div>
        <div class="coluna-pedidos" id="em_preparo" ondragover="allowDrop(event)" ondrop="drop(event, 'em_preparo')">
            <h2>Em preparo</h2>
            <?php renderPedidos($pedidos_completos, 'em_preparo'); ?>
        </div>
        <div class="coluna-pedidos" id="finalizado" ondragover="allowDrop(event)" ondrop="drop(event, 'finalizado')">
            <h2>Finalizados</h2>
            <?php renderPedidos($pedidos_completos, 'finalizado'); ?>
        </div>
    </div>
    <script src="pedidos.js"></script>
</body>
</html> 