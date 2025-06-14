<?php
header('Content-Type: application/json');
session_start();
require_once 'conexao.php';

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Recebe os dados do pedido
$dados = json_decode(file_get_contents('php://input'), true);

// Validação dos dados
if (!$dados || !isset($dados['nome']) || !isset($dados['itens']) || empty($dados['itens'])) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos ou incompletos']);
    exit;
}

try {
    // Inicia a transação
    $pdo->beginTransaction();

    // 1. Buscar ou criar cliente
    $stmt = $pdo->prepare("SELECT id FROM clientes WHERE nome = ?");
    $stmt->execute([$dados['nome']]);
    $cliente = $stmt->fetch();
    if ($cliente) {
        $cliente_id = $cliente['id'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO clientes (nome) VALUES (?)");
        $stmt->execute([$dados['nome']]);
        $cliente_id = $pdo->lastInsertId();
    }

    // 2. Gerar número do pedido
    $numero_pedido = uniqid('PED');

    // 3. Inserir pedido
    $stmt = $pdo->prepare("INSERT INTO pedidos (numero_pedido, cliente_id, status, data) VALUES (?, ?, 'pendente', NOW())");
    $stmt->execute([$numero_pedido, $cliente_id]);
    $pedido_id = $pdo->lastInsertId();

    // 4. Inserir itens do pedido
    $stmt = $pdo->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
    foreach ($dados['itens'] as $item) {
        $stmt->execute([
            $pedido_id,
            $item['id'],
            $item['quantidade'],
            $item['preco']
        ]);
    }

    // Confirma a transação
    $pdo->commit();

    // Retorna sucesso
    echo json_encode([
        'success' => true,
        'message' => 'Pedido realizado com sucesso',
        'pedido_id' => $pedido_id
    ]);

} catch (Exception $e) {
    // Em caso de erro, desfaz a transação
    $pdo->rollBack();
    
    // Log do erro para debug
    error_log("Erro no processamento do pedido: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao processar pedido',
        'debug' => $e->getMessage()
    ]);
}
?> 