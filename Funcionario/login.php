<?php
session_start();
require_once '../Cliente/conexao.php';

// Inicializa variáveis de mensagem
$mensagem = '';
$tipo_mensagem = '';
$origem_mensagem = '';

// Processa cadastro
if (isset($_POST['acao']) && $_POST['acao'] === 'cadastro') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($nome && $email && $senha) {
        // Verifica se o email já existe
        $stmt = $pdo->prepare('SELECT id FROM funcionarios WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $mensagem = 'E-mail já cadastrado!';
            $tipo_mensagem = 'erro';
            $origem_mensagem = 'cadastro';
        } else {
            // Cadastra novo funcionário
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO funcionarios (nome, email, senha) VALUES (?, ?, ?)');
            if ($stmt->execute([$nome, $email, $hash])) {
                $mensagem = 'Cadastro realizado com sucesso! Faça login para continuar.';
                $tipo_mensagem = 'sucesso';
                $origem_mensagem = 'cadastro';
            } else {
                $mensagem = 'Erro ao cadastrar. Tente novamente.';
                $tipo_mensagem = 'erro';
                $origem_mensagem = 'cadastro';
            }
        }
    } else {
        $mensagem = 'Preencha todos os campos do cadastro!';
        $tipo_mensagem = 'erro';
        $origem_mensagem = 'cadastro';
    }
}

// Processa login
if (isset($_POST['acao']) && $_POST['acao'] === 'login') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    if ($email && $senha) {
        $stmt = $pdo->prepare('SELECT id, nome, senha FROM funcionarios WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['funcionario_id'] = $user['id'];
            $_SESSION['funcionario_nome'] = $user['nome'];
            $mensagem = 'Login realizado com sucesso! Redirecionando...';
            $tipo_mensagem = 'sucesso';
            $origem_mensagem = 'login';
            header('Location: painel.php');
            exit;
        } else {
            $mensagem = 'E-mail ou senha incorretos!';
            $tipo_mensagem = 'erro';
            $origem_mensagem = 'login';
        }
    } else {
        $mensagem = 'Preencha todos os campos do login!';
        $tipo_mensagem = 'erro';
        $origem_mensagem = 'login';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fast Service - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Login.css?=v2">
</head>
<body>
    <header>
        <nav class="nav-bar">
            <a href="#" class="logo"><i class="fa-solid fa-bowl-food"></i></a>
            <h1 class="name">Fast Service</h1>
          
            <a href="./login.php" class="btn">Login / Cadastre-se</a>
        </nav>
    </header>
    <div class="container" id="container" data-mensagem-origem="<?php echo $origem_mensagem; ?>">
<?php if ($mensagem): ?>
    <div class="erro-mensagem <?php echo $tipo_mensagem; ?>">
        <?php echo htmlspecialchars($mensagem); ?>
    </div>
<?php endif; ?>
        <!-- Formulário de Cadastro -->
        <div class="form-container sign-up-container">
            <form action="" method="POST">
                <h1>Crie uma conta</h1>
                <span>Use seu email para registrar</span>
                <input type="text" name="nome" placeholder="Nome" required />
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="senha" placeholder="Senha" required />
                <input type="hidden" name="acao" value="cadastro">
                <button type="submit">Cadastre-se</button>
            </form>
        </div>
        <!-- Formulário de Login -->
        <div class="form-container log-in-container">
            <form action="" method="POST">
                <h1>Login</h1>
                <span>Entre em sua conta</span>
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="senha" placeholder="Senha" required />
                <input type="hidden" name="acao" value="login">
                <button type="submit">Entrar</button>
            </form>
        </div>
        <!-- Overlay de troca entre Login e Cadastro -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Bem Vindo ao Fast Service!</h1>
                    <p>Já tem uma conta? Faça Login</p>
                    <button class="ghost" id="logIn" type="button">Login</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Bem Vindo ao Fast Service!</h1>
                    <p>Não tem uma conta? Crie grátis</p>
                    <button class="ghost" id="signUp" type="button">Cadastre-se</button>
                </div>
            </div>
        </div>
    </div>
    <script src="app.js"></script>
</body>
</html> 