<?php
require_once 'conexao.php';

try {
    $stmt = $pdo->query("SELECT p.*, c.nome AS categoria_nome FROM produtos p JOIN categorias c ON p.categoria_id = c.id ORDER BY c.id, p.nome");
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
      <li><a href="./pedidos.php">Status dos Pedidos</a></li>
	  <li><a href="./ContateNos.php">Contato</a></li>
      <li><a href="./SobreNos.html">Sobre Nós</a></li>
	  <li><a href="./carrinho.php">Carrinho de Compras</a></li>
    </ul>
    
  </header>

<body>
	<div class="menu" id="menu-dinamico">
		<!-- Produtos serão carregados via JS -->
	</div>
<a href="carrinho.php">
	<button class="botao-carrinho-flutuante">
        <img src="img/carrinho.png" alt="Carrinho" />
        Carrinho
    </button>
</a>

<script>
function renderizarCardapio(produtos) {
    const menu = document.getElementById('menu-dinamico');
    menu.innerHTML = '';
    let categoriaAtual = '';
    produtos.forEach(produto => {
        if (categoriaAtual !== produto.categoria_nome) {
            categoriaAtual = produto.categoria_nome;
            if (menu.innerHTML === '') {
                // Primeira categoria: mostra "Cardápio" + categoria
                menu.innerHTML += `<div class='heading'>
                    <h1 style="font-weight:400; font-size:30px; letter-spacing:10px; margin-bottom:10px;">Cardápio</h1>
                    <h3 style="font-weight:600; font-size:22px; letter-spacing:5px;">&mdash; ${categoriaAtual} &mdash;</h3>
                </div>`;
            } else {
                // Outras categorias: só mostra a categoria
                menu.innerHTML += `<div class='heading'>
                    <h3 style="font-weight:600; font-size:22px; letter-spacing:5px;">&mdash; ${categoriaAtual} &mdash;</h3>
                </div>`;
            }
        }
        menu.innerHTML += `
        <div class='food-items'>
            <img src='${produto.imagem_url.replace('./img','img')}' alt='${produto.nome}'>
            <div class='details'>
                <div class='details-sub'>
                    <h5>${produto.nome}</h5>
                    <h5 class='price'>R$ ${parseFloat(produto.preco).toFixed(2).replace('.', ',')}</h5>
                </div>
                <p>${produto.descricao}</p>
                ${produto.disponivel == 1 ?
                    `<button class='adicionar-carrinho' data-id='${produto.id}' data-nome='${produto.nome}' data-preco='${produto.preco}'>Comprar</button>` :
                    `<button class='btn-esgotado' disabled style='background:#e74c3c; color:#fff; cursor:not-allowed;'>Esgotado</button>`
                }
            </div>
        </div>`;
    });
    // Adiciona eventos de clique aos botões de compra
    document.querySelectorAll('.adicionar-carrinho').forEach(botao => {
        botao.addEventListener('click', () => {
            if (botao.disabled) return;
            const id = botao.dataset.id;
            const nome = botao.dataset.nome;
            const preco = parseFloat(botao.dataset.preco);
            const imagem = botao.closest('.food-items').querySelector('img').src;
            adicionarAoCarrinho(id, nome, preco, imagem);
        });
    });
}

function carregarCardapio() {
    fetch('listar_produtos.php')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderizarCardapio(data.produtos);
            }
        });
}

function adicionarAoCarrinho(id, nome, preco, imagem) {
    const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
    const itemExistente = carrinho.find(item => item.id === id);
    if (itemExistente) {
        itemExistente.quantidade += 1;
    } else {
        carrinho.push({ id, nome, preco, imagem, quantidade: 1 });
    }
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    alert('Item adicionado ao carrinho!');
}

// Atualiza o cardápio a cada 2 segundos
setInterval(carregarCardapio, 2000);
carregarCardapio();
</script>

</body>
</html>