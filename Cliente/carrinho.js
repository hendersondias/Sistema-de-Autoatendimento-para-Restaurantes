// Função para carregar os itens do carrinho do localStorage
function carregarCarrinho() {
    const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
    const carrinhoItems = document.getElementById('carrinho-items');
    const carrinhoTotal = document.getElementById('carrinho-total');
    
    if (carrinho.length === 0) {
        carrinhoItems.innerHTML = '<div class="carrinho-vazio">Seu carrinho está vazio</div>';
        carrinhoTotal.textContent = 'Total: R$ 0,00';
        document.getElementById('finalizar-compra').style.display = 'none';
        return;
    } else {
        document.getElementById('finalizar-compra').style.display = 'inline-block';
    }

    let total = 0;
    carrinhoItems.innerHTML = '';

    carrinho.forEach((item, index) => {
        const itemElement = document.createElement('div');
        itemElement.className = 'carrinho-item';
        itemElement.innerHTML = `
            <img src="${item.imagem}" alt="${item.nome}">
            <div class="item-details">
                <h3>${item.nome}</h3>
                <p>R$ ${item.preco.toFixed(2)}</p>
                <div class="item-quantity">
                    <button class="quantity-btn" onclick="alterarQuantidade(${index}, -1)">-</button>
                    <span>${item.quantidade}</span>
                    <button class="quantity-btn" onclick="alterarQuantidade(${index}, 1)">+</button>
                </div>
            </div>
            <button class="remove-btn" onclick="removerItem(${index})">Remover</button>
        `;
        carrinhoItems.appendChild(itemElement);
        total += item.preco * item.quantidade;
    });

    carrinhoTotal.textContent = `Total: R$ ${total.toFixed(2)}`;
}

// Função para alterar a quantidade de um item
function alterarQuantidade(index, delta) {
    const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
    carrinho[index].quantidade = Math.max(1, carrinho[index].quantidade + delta);
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    carregarCarrinho();
}

// Função para remover um item do carrinho
function removerItem(index) {
    const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
    carrinho.splice(index, 1);
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    carregarCarrinho();
}

// Função para finalizar a compra
function finalizarCompra() {
    const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
    const nomeCliente = document.getElementById('nome').value.trim();

    if (!nomeCliente) {
        alert('Por favor, preencha o nome completo!');
        return;
    }

    if (carrinho.length === 0) {
        alert('Seu carrinho está vazio!');
        return;
    }

    // Desabilita o botão durante o processamento
    const finalizarBtn = document.getElementById('finalizar-compra');
    finalizarBtn.disabled = true;
    finalizarBtn.textContent = 'Processando...';

    // Prepara os dados para envio
    const dadosPedido = {
        nome: nomeCliente,
        itens: carrinho.map(item => ({
            id: parseInt(item.id),
            quantidade: parseInt(item.quantidade),
            preco: parseFloat(item.preco)
        }))
    };

    // Envia o pedido via AJAX
    fetch('processar_pedido.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dadosPedido)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Pedido realizado com sucesso! Número do pedido: ' + data.pedido_id);
            localStorage.removeItem('carrinho');
            window.location.href = 'home.html';
        } else {
            alert('Erro ao processar pedido: ' + data.message + '\nDebug: ' + (data.debug || ''));
            throw new Error(data.message || 'Erro ao processar pedido');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao processar pedido: ' + error.message);
        finalizarBtn.disabled = false;
        finalizarBtn.textContent = 'Finalizar Compra';
    });
}

// Inicializa o carrinho quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    carregarCarrinho();
    
    // Adiciona evento de clique ao botão de finalizar compra
    document.getElementById('finalizar-compra').addEventListener('click', finalizarCompra);
});

