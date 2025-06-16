<?php
$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $mensagem_avaliacao = $_POST['mensagem'] ?? '';
    if ($nome && $email && $mensagem_avaliacao) {
        require_once 'conexao.php';
        $stmt = $pdo->prepare('INSERT INTO avaliacoes (nome, email, mensagem) VALUES (?, ?, ?)');
        $stmt->execute([$nome, $email, $mensagem_avaliacao]);
        $mensagem = 'Avaliação enviada com sucesso!';
    } else {
        $mensagem = 'Preencha todos os campos!';
    }
}
?>
<div class="container">
  <img src="./img/donutsSob.jpg" alt="">
  <div class="about_details">
    <span>Sobre Nós</span>
    <h2>A Magia Está nos Detalhes!</h2>
    <p>Acreditamos que a magia está nos detalhes, desde a escolha dos melhores ingredientes até a apresentação irresistível de nossos donuts. Cada peça é uma obra-prima artesanal, feita com carinho e dedicação para garantir não apenas uma explosão de sabor, mas também um deleite visual.</p>
    <br>
    <p>Somos apaixonados por donuts e acreditamos que essas deliciosas criações redondas podem transformar qualquer dia comum em algo extraordinário. No coração de nossa loja virtual, buscamos oferecer uma experiência única, onde cada mordida seja uma viagem sensorial repleta de sabor e alegria.</p>
    <a href="./ContateNos.html"><button class="btn2">Contate-nos</button></a>
    <hr style="margin:30px 0;">
    <h3>Deixe sua avaliação</h3>
    <?php if ($mensagem): ?>
      <div style="color: #2781d6; margin-bottom: 10px; font-weight: bold;"> <?php echo $mensagem; ?> </div>
    <?php endif; ?>
    <form method="POST" style="display:flex;flex-direction:column;gap:10px;max-width:400px;">
      <input type="text" name="nome" placeholder="Seu nome" required>
      <input type="email" name="email" placeholder="Seu email" required>
      <textarea name="mensagem" placeholder="Sua mensagem" required></textarea>
      <button type="submit" class="btn2">Enviar Avaliação</button>
    </form>
  </div>
</div> 