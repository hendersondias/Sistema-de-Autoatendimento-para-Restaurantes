<?php
session_start();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Títulos e links para os arquivos CSS -->
    <title>Fast Service - Carrinho</title>
    <link rel="stylesheet" href="home.css?v2">
    <link rel="stylesheet" href="carrinho.css?v2">
</head>

<body>
    <section>
        <div class="blur_clr"></div>
        <div class="blur_clr"></div>
        
        <header>
            <a href="#" class="logo"><i class="fa-solid fa-bowl-food"></i></a>
            <h1 class="name">Fast Service</h1>
            <ul class="nav">
                <li><a href="./home.html">Início</a></li>
                <li><a href="cardapio.php">Cardápio</a></li>
                <li><a href="./pedidos.php">Status dos Pedidos</a></li>
                <li><a href="./ContateNos.php">Contato</a></li>
                <li><a href="./SobreNos.html">Sobre Nós</a></li>
                <li><a href="./carrinho.php">Carrinho de Compras</a></li>
            </ul>
        </header>

        <div class="carrinho-container">
            <h2>Seu Carrinho</h2> <br>
            <div class="nome-cliente">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required>
            </div>
            <div class="carrinho-items" id="carrinho-items">
                <!-- Os itens do carrinho serão inseridos aqui via JavaScript -->
            </div>
            <div class="carrinho-total" id="carrinho-total">
                Total: R$ 0,00
            </div>
            <button class="finalizar-compra" id="finalizar-compra">Finalizar Compra</button> 
            <div class="pagamento-container">
               <br> <p class="pagamento-title">PAGUE NO CAIXA COM:</p>
                <div class="pagamento-cards">
                    <div class="pagamento-card">
                        <img src="img/pix.png" alt="Pix">
                        <span>Pix</span>
                    </div>
                    <div class="pagamento-card">
                        <img src="img/visa2.png" alt="Visa">
                        <span>Visa</span>
                    </div>
                    <div class="pagamento-card">
                        <img src="img/mastercard.png" alt="Mastercard">
                        <span>Mastercard</span>
                    </div>
                    <div class="pagamento-card">
                        <img src="img/elo.png" alt="Elo">
                        <span>Elo</span>
                    </div>
                    <div class="pagamento-card">
                        <img src="img/american express.png" alt="Amex">
                        <span>Amex</span>
                    </div>
                    
                </div>
            </div>

            
        </div>
    </section>

    <script src="carrinho.js"></script>
</body>

</html>
