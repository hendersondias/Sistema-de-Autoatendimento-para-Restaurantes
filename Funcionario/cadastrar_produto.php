<?php
header('Content-Type: application/json');
require_once '../Cliente/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$nome = $_POST['nome'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$preco = $_POST['preco'] ?? '';
$categoria_id = $_POST['categoria_id'] ?? '';

if (!$nome || !$descricao || !$preco || !$categoria_id || !isset($_FILES['imagem'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

// Upload da imagem
$imagem = $_FILES['imagem'];
$ext = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
$permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
if (!in_array($ext, $permitidas)) {
    echo json_encode(['success' => false, 'message' => 'Formato de imagem não permitido']);
    exit;
}
$nome_arquivo = uniqid('produto_', true) . '.' . $ext;
$caminho = '../Cliente/img/' . $nome_arquivo;
if (!move_uploaded_file($imagem['tmp_name'], $caminho)) {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar imagem']);
    exit;
}
$imagem_url = './img/' . $nome_arquivo;

try {
    $stmt = $pdo->prepare('INSERT INTO produtos (nome, descricao, preco, imagem_url, categoria_id, disponivel) VALUES (?, ?, ?, ?, ?, 1)');
    $stmt->execute([$nome, $descricao, $preco, $imagem_url, $categoria_id]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar produto', 'debug' => $e->getMessage()]);
} 