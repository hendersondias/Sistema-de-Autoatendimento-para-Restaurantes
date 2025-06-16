-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS fastserviceDB;
USE fastserviceDB;

-- Tabela de categorias
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

-- Tabela de clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

-- Tabela de produtos
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    imagem_url VARCHAR(255),
    categoria_id INT,
    disponivel BOOLEAN DEFAULT TRUE,
    tempo_preparo INT,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Tabela de pedidos (compatível com processar_pedido.php)
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_pedido VARCHAR(20) NOT NULL,
    cliente_id INT NOT NULL,
    status ENUM('pendente', 'em_preparo', 'finalizado') DEFAULT 'pendente',
    data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    oculto BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

-- Comando para atualizar bancos já existentes
ALTER TABLE pedidos ADD COLUMN IF NOT EXISTS oculto BOOLEAN DEFAULT FALSE;

-- Tabela de itens do pedido (compatível com processar_pedido.php)
CREATE TABLE IF NOT EXISTS itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

-- Tabela de funcionários
CREATE TABLE IF NOT EXISTS funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Tabela de contatos
CREATE TABLE IF NOT EXISTS contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    assunto VARCHAR(200) NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserir categorias
INSERT INTO categorias (nome) VALUES 
('Donuts'),
('Doces'),
('Bebidas');

-- Inserir produtos - Donuts
INSERT INTO produtos (nome, descricao, preco, categoria_id, imagem_url, tempo_preparo) VALUES
('Dream Happy Donut', 'Uma explosão celestial de sabor açucarado que vai te transportar para um mundo de doçura inigualável.', 16.00, 1, './img/donuts2.jpg', 10),

('Lemon Chocolate Donut', 'O donut de limão com chocolate é a combinação ideal entre o cítrico do limão e a indulgência do chocolate.', 15.00, 1, './img/Donuts-Limao-com-Chocolate.jpg', 10),
('Camembert Donut', 'Delicie-se com nosso donut de queijo Camembert, onde a cremosidade do queijo encontra a suavidade do donut.', 18.00, 1, './img/donuts.com_croaca_queijo.png', 12),
('Maple Bacon Donut', 'A combinação perfeita entre a crocância do bacon e a maciez do donut, mergulhada em um creme irresistível.', 14.00, 1, './img/Maple-Bacon-Donuts_WEB.jpg', 12),
('Chicken Donut', 'Apresentamos o donut de frango com doce de leite, onde a suculência do frango se encontra com a doçura irresistível do doce de leite.', 13.00, 1, './img/donuts_de_frango.jpg', 15)
('Strawberry Frosted Donut', 'Experimente a perfeição vermelha! Nosso donut de morango é uma celebração da fruta mais irresistível.', 15.00, 1, './img/Strawberry-Frosted-Donuts.jpg', 10);

-- Inserir produtos - Doces
INSERT INTO produtos (nome, descricao, preco, categoria_id, imagem_url, tempo_preparo) VALUES
('Brigadeiro Gourmet', 'Um clássico brasileiro com toque sofisticado. Cada mordida é pura explosão de chocolate e cremosidade.', 8.00, 2, './img/brigadeiro2.JPG', 5),
('Brownie', 'Brownie intenso e macio, com muito chocolate derretido para adoçar seu dia com sabor e indulgência.', 16.00, 2, './img/brownie2.JPG', 8),
('Strawberry Cheesecake', 'Camadas perfeitas de cheesecake, calda de morango e base crocante. Doce, fresco e irresistível.', 18.00, 2, './img/cheesecake2.JPG', 10);

-- Inserir produtos - Bebidas
INSERT INTO produtos (nome, descricao, preco, categoria_id, imagem_url, tempo_preparo) VALUES
('Água Mineral com Gás', 'Refrescância pura com leveza. Ideal para acompanhar qualquer refeição e manter-se hidratado.', 5.00, 3, './img/aguacg.jpg', 1),
('Água Mineral sem gás', 'Pura, leve e essencial. Hidratação natural para todos os momentos.', 5.00, 3, './img/aguasg.png', 1),
('Monster Energy Drink', 'Energia e sabor em cada gole. Perfeito para quem precisa manter o ritmo acelerado.', 8.00, 3, './img/monster.jpg', 1),
('Guaraná Antarctica 350ml', 'Sabor brasileiro de verdade. Refrescante, doce na medida certa e amado por todos.', 8.00, 3, './img/guarana2.jpg', 1),
('Coca-Cola KS 290ml', 'O sabor clássico que todo mundo conhece. Gaseificação na medida para realçar sua refeição.', 10.00, 3, './img/cocaks2.jpg', 1),
('Pepsi 350ml', 'Refrescância intensa com um toque adocicado. Acompanha bem doces e lanches com equilíbrio.', 8.00, 3, './img/pepsi2.jpg', 1);

-- Inserir funcionário de teste
INSERT INTO funcionarios (nome, email, senha) VALUES 
('Funcionário Teste', 'funcionario@teste.com', 'senha_hash_exemplo');