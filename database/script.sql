-- Tabela de produtos
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    descricao VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de variações (ex: tamanho, cor etc)
CREATE TABLE variacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    nome VARCHAR(100),
    preco DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);

-- Tabela de estoque (por variação ou produto base)
CREATE TABLE estoque (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    variacao_id INT DEFAULT NULL,
    quantidade INT NOT NULL,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    FOREIGN KEY (variacao_id) REFERENCES variacoes(id) ON DELETE CASCADE
);

-- Tabela de cupons
CREATE TABLE cupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    valor_desconto DECIMAL(10,2) NOT NULL,
    minimo_subtotal DECIMAL(10,2) NOT NULL,
    validade DATE NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subtotal DECIMAL(10,2) NOT NULL,
    frete DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pendente', 'pago', 'cancelado') DEFAULT 'pendente',
    nome_cliente VARCHAR(255),
    email_cliente VARCHAR(255),
    cep VARCHAR(10),
    endereco VARCHAR(255),
    numero VARCHAR(20),
    complemento VARCHAR(255),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(50),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Itens do pedido (com variação ou não)
CREATE TABLE pedido_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    variacao_id INT DEFAULT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    FOREIGN KEY (variacao_id) REFERENCES variacoes(id) ON DELETE SET NULL
);

-- Registro de cupons utilizados nos pedidos
CREATE TABLE pedido_cupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    cupom_id INT NOT NULL,
    valor_aplicado DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (cupom_id) REFERENCES cupons(id) ON DELETE CASCADE
);
