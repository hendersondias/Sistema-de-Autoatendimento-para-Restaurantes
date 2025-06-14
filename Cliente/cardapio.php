<?php
require_once 'conexao.php';

try {
    $stmt = $pdo->query("SELECT p.*, c.nome AS categoria_nome FROM produtos p JOIN categorias c ON p.categoria_id = c.id WHERE p.disponivel = 1 ORDER BY c.id, p.nome");
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao carregar produtos: " . $e->getMessage();
    $produtos = [];
}

// Descobrir a primeira categoria
$primeiraCategoria = count($produtos) > 0 ? $produtos[0]['categoria_nome'] : '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <title>Cardapio</title>
    <link rel="stylesheet" href="./Cardapio.css?v=2">
   
</head>

<header>
    <a href="#" class="logo"><i class="fa-solid fa-bowl-food"></i></a>
    <h1 class="name">Fast Service</h1>
    <ul class="nav">
      <li><a href="./home.html">Início</a></li>
      <li><a href="./Cardapio.html">Cardápio</a></li>
	  <li><a href="./ContateNos.html">Contato</a></li>
      <li><a href="./SobreNos.html">Sobre Nós</a></li>
	  <li><a href="./carrinho.php">Carrinho de Compras</a></li>
    </ul>
    
  </header>

<body>
	<div class="menu">
		<?php if ($primeiraCategoria): ?>
		<div class="heading">
			<h1>Cardápio</h1>
			<h3>&mdash; <?php echo htmlspecialchars($primeiraCategoria); ?> &mdash;</h3>
		</div>
		<?php endif; ?>
		<?php
		$categoriaAtual = $primeiraCategoria;
		$primeira = true;
		foreach ($produtos as $produto) {
			if ($categoriaAtual != $produto['categoria_nome']) {
				$categoriaAtual = $produto['categoria_nome'];
				echo '<div class="heading"><h3>&mdash; ' . htmlspecialchars($categoriaAtual) . ' &mdash;</h3></div>';
			}
		?>
		<div class="food-items">
			<img src="<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
			<div class="details">
				<div class="details-sub">
					<h5><?php echo htmlspecialchars($produto['nome']); ?></h5>
					<h5 class="price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></h5>
				</div>
				<p><?php echo htmlspecialchars($produto['descricao']); ?></p>
				<button class="adicionar-carrinho" data-id="<?php echo $produto['id']; ?>" data-nome="<?php echo htmlspecialchars($produto['nome']); ?>" data-preco="<?php echo $produto['preco']; ?>">Comprar</button>
			</div>
		</div>
		<?php } ?>
	</div>
<a href="carrinho.php">
	<button class="botao-carrinho-flutuante">
        <img src="img/carrinho.png" alt="Carrinho" />
        Carrinho
    </button>
</a>

<script>
    // Função para adicionar item ao carrinho
    function adicionarAoCarrinho(id, nome, preco, imagem) {
        const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
        
        // Verifica se o item já existe no carrinho
        const itemExistente = carrinho.find(item => item.id === id);
        
        if (itemExistente) {
            itemExistente.quantidade += 1;
        } else {
            carrinho.push({
                id: id,
                nome: nome,
                preco: preco,
                imagem: imagem,
                quantidade: 1
            });
        }
        
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        alert('Item adicionado ao carrinho!');
    }

    // Adiciona eventos de clique aos botões de compra
    document.addEventListener('DOMContentLoaded', () => {
        const botoes = document.querySelectorAll('.adicionar-carrinho');
        botoes.forEach(botao => {
            botao.addEventListener('click', () => {
                const id = botao.dataset.id;
                const nome = botao.dataset.nome;
                const preco = parseFloat(botao.dataset.preco);
                const imagem = botao.closest('.food-items').querySelector('img').src;
                adicionarAoCarrinho(id, nome, preco, imagem);
            });
        });
    });
</script>

</body>
</html>