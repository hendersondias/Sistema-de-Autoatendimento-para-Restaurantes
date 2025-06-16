<?php
require_once '../Cliente/conexao.php';
$stmt = $pdo->query('SELECT * FROM contatos ORDER BY data_envio DESC');
$contatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Formatar data para d/m/Y H:i
foreach ($contatos as &$c) {
    $c['data_envio'] = date('d/m/Y H:i', strtotime($c['data_envio']));
    $c['nome'] = htmlspecialchars($c['nome']);
    $c['email'] = htmlspecialchars($c['email']);
    $c['assunto'] = htmlspecialchars($c['assunto']);
    $c['mensagem'] = htmlspecialchars($c['mensagem']);
}
header('Content-Type: application/json');
echo json_encode($contatos); 