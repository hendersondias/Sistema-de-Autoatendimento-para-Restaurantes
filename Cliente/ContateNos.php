<?php
$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $assunto = $_POST['assunto'] ?? '';
    $mensagem_contato = $_POST['mensagem'] ?? '';
    if ($nome && $email && $assunto && $mensagem_contato) {
        require_once 'conexao.php';
        $stmt = $pdo->prepare('INSERT INTO contatos (nome, email, assunto, mensagem) VALUES (?, ?, ?, ?)');
        $stmt->execute([$nome, $email, $assunto, $mensagem_contato]);
        $mensagem = 'Mensagem enviada com sucesso!';
    } else {
        $mensagem = 'Preencha todos os campos!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contate-nos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="ContateNos.css?v=2">
  </head>
  <body>
      <header>
        <nav class="nav-bar">
          <a href="#" class="logo"><i class="fa-solid fa-bowl-food"></i></a>
          <h1 class="name">Fast Service</h1>
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
    <section class="contact">
      <div class="container">
        <div class="contact-form">
          <h1>Contate<span> Nos</span></h1>
          <p>Queremos ouvir de você! Seja para tirar dúvidas, compartilhar uma experiência doce ou fazer uma encomenda especial, estamos aqui para tornar sua jornada com os Donuts ainda mais memorável.</p>
          <?php if ($mensagem): ?>
            <div style="color: #2781d6; margin-bottom: 10px; font-weight: bold;"> <?php echo $mensagem; ?> </div>
          <?php endif; ?>
          <form method="POST">
            <input type="text" name="nome" placeholder="Seu Nome" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="text" name="assunto" placeholder="Escreva um assunto" required>
            <textarea name="mensagem" cols="30" rows="10" placeholder="Sua Mensagem" required></textarea>
            <input type="submit" value="Enviar!" class="btn">
          </form>
        </div>
        <div class="contact-img">
          <img src="./img/donutsPrinc.png">
        </div>
      </div>
    </section>
  </body>
</html> 