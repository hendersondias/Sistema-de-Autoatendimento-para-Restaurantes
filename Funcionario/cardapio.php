<?php
require_once '../Cliente/conexao.php';

try {
    $stmt = $pdo->query("SELECT p.*, c.nome AS categoria_nome FROM produtos p JOIN categorias c ON p.categoria_id = c.id ORDER BY c.id, p.nome");
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $categoriasStmt = $pdo->query("SELECT * FROM categorias ORDER BY id");
    $categorias = $categoriasStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao carregar produtos: " . $e->getMessage();
    $produtos = [];
    $categorias = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <title>Cardápio Funcionário</title>
    <link rel="stylesheet" href="../Cliente/Cardapio.css?v=2">
</head>
<body>
<header>
    <a href="#" class="logo"><i class="fa-solid fa-bowl-food"></i></a>
    <h1 class="name">FAST SERVICE</h1>
    <ul class="nav">
      <li><a href="./painel.php">Início</a></li>
      <li><a href="./pedidos.php">Pedidos</a></li>
      <li><a href="./cardapio.php">Cardápio</a></li>
      <li><a href="./avaliacoes.php">Feedbacks</a></li>
    </ul>
    <?php session_start(); if (!isset($_SESSION['funcionario_id'])) { header('Location: login.php'); exit; } $nome = $_SESSION['funcionario_nome'] ?? 'Funcionário'; ?>
    <div style="display: flex; gap: 10px; align-items: center;">
        <span class="btn" style="display:flex; align-items:center; gap:6px; cursor:default;">
            <i class="fa-solid fa-user"></i> Olá, <?php echo htmlspecialchars($nome); ?>
        </span>
        <a href="logout.php" class="btn">Sair</a>
    </div>
</header>
<div class="menu">
    <?php foreach ($categorias as $categoria): ?>
        <div class="heading">
            <h1 style="font-weight:400; font-size:30px; letter-spacing:10px; margin-bottom:10px;">Cardápio</h1>
            <h3 style="font-weight:600; font-size:22px; letter-spacing:5px;">&mdash; <?php echo htmlspecialchars($categoria['nome']); ?> &mdash;</h3>
        </div>
        <!-- Card de adicionar produto -->
        <div class="food-items add-product-card" data-categoria-id="<?php echo $categoria['id']; ?>" style="display:flex;align-items:center;justify-content:center;min-height:320px;">
            <div class="details" style="text-align:center; width:100%; cursor:pointer; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                <span style="font-size:4em; color:#2781d6; display:block;">+</span>
                <span style="font-size:1.2em; color:#fff; margin-top:10px;">Adicionar Produto</span>
            </div>
        </div>
        <?php foreach ($produtos as $produto): if ($produto['categoria_id'] != $categoria['id']) continue; ?>
        <div class="food-items" data-produto-id="<?php echo $produto['id']; ?>">
            <img src="<?php echo '../Cliente' . substr($produto['imagem_url'], 1); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
            <div class="details">
                <div class="details-sub">
                    <h5><?php echo htmlspecialchars($produto['nome']); ?></h5>
                    <h5 class="price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></h5>
                </div>
                <p><?php echo htmlspecialchars($produto['descricao']); ?></p>
                <div style="display:flex; gap:10px;">
                    <button class="btn-esgotar" data-id="<?php echo $produto['id']; ?>">
                        <?php echo $produto['disponivel'] ? 'Esgotar' : 'Disponibilizar'; ?>
                    </button>
                    <button class="btn-esgotar btn-remover" data-id="<?php echo $produto['id']; ?>" style="background:#e74c3c;">Remover</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>
<script src="cardapio_funcionario.js"></script>
</body>
</html> 