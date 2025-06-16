<?php
session_start();
if (!isset($_SESSION['funcionario_id'])) {
    header('Location: login.php');
    exit;
}
$nome = $_SESSION['funcionario_nome'] ?? 'Funcionário';
require_once '../Cliente/conexao.php';
// Buscar mensagens de contato
$stmt2 = $pdo->query('SELECT * FROM contatos ORDER BY data_envio DESC');
$contatos = $stmt2->fetchAll();
$cont1 = [];
$cont2 = [];
foreach ($contatos as $i => $c) {
    if ($i % 2 == 0) $cont1[] = $c;
    else $cont2[] = $c;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Feedback dos Clientes - Fast Service</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Login.css?=v2">
    <link rel="stylesheet" href="painel.css?=v2">
    <link rel="stylesheet" href="pedidos.css?=v2">
    <style>
        .coluna-pedidos {
            width: 500px;
            max-width: 95vw;
        }
        .card-avaliacao {
            background: #fff;
            border-radius: 8px;
            padding: 18px;
            margin-bottom: 18px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }
        .card-avaliacao strong { color: #2781d6; }
        .card-avaliacao .data { color: #888; font-size: 0.95em; float: right; }
        .card-avaliacao .mensagem { margin: 10px 0 0 0; color: #222; }
    </style>
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
                <li><a href="./cardapio.php">Cardápio</a></li>
                <li><a href="./avaliacoes.php">Feedbacks</a></li>
            </ul>
            <div class="nav-right" style="display: flex; gap: 10px; align-items: center;">
                <span class="btn" style="background:#222; color:#fff; cursor:default;">Olá, <?php echo htmlspecialchars($nome); ?></span>
                <a href="logout.php" class="btn" style="background:#222; color:#fff;">Sair</a>
            </div>
        </nav>
    </header>
    <h1 style="color:#2781d6; text-align:center; margin-top:30px;">Feedback dos Clientes</h1>
    <div class="container-pedidos">
        <div class="coluna-pedidos" id="coluna-contatos">
            <div id="lista-contatos"></div>
        </div>
    </div>
    <script>
    function atualizarContatos() {
        fetch('listar_contatos.php')
            .then(res => res.json())
            .then(data => {
                const lista = document.getElementById('lista-contatos');
                lista.innerHTML = '';
                data.forEach(c => {
                    lista.innerHTML += `
                        <div class=\"card-avaliacao\">
                            <span class=\"data\">${c.data_envio}</span>
                            <strong>${c.nome}</strong> <br>
                            <span style=\"color:#555;\">${c.email}</span><br>
                            <span style=\"color:#2781d6; font-weight:bold;\">Assunto: ${c.assunto}</span>
                            <div class=\"mensagem\">${c.mensagem.replace(/\n/g, '<br>')}</div>
                        </div>
                    `;
                });
            });
    }
    setInterval(atualizarContatos, 5000);
    atualizarContatos();
    </script>
</body>
</html> 