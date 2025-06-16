<?php
session_start();
if (!isset($_SESSION['funcionario_id'])) {
    header('Location: login.php');
    exit;
}
$nome = $_SESSION['funcionario_nome'] ?? 'Funcionário';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Funcionário - Fast Service</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Login.css">
    <link rel="stylesheet" href="painel.css">
</head>
<body>
    <header>
        <nav class="nav-bar">
            <a href="#" class="logo"><i class="fa-solid fa-bowl-food"></i></a>
            <h1 class="name">Fast Service</h1>
            <ul class="nav">
                <li><a href="./painel.php">Início</a></li>
                <li><a href="./Pedidos.php">Pedidos</a></li>
                <li><a href="./Cardapio.php">Cardápio</a></li>
                <li><a href="./avaliacoes.php">Feedbacks</a></li>
            </ul>
            <span class="btn" style="background:#222; color:#fff; cursor:default;">Olá, <?php echo htmlspecialchars($nome); ?></span>
        </nav>
    </header>
    <div class="painel-box">
        <h2>Bem-vindo, <?php echo htmlspecialchars($nome); ?>!</h2>
        <p>Este é o painel do funcionário. Aqui você poderá acessar funcionalidades internas do sistema.</p>
        <div class="painel-btns">
            <a href="Pedidos.php" class="painel-btn">Pedidos</a>
            <a href="Cardapio.php" class="painel-btn">Cardápio</a>
            <a href="avaliacoes.php" class="painel-btn">Feedbacks</a>
        </div>
        <a href="logout.php" class="logout">Sair</a>
    </div>
</body>
</html> 