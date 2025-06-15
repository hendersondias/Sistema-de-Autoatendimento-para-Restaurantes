let pedidosOcultos = JSON.parse(localStorage.getItem('pedidosOcultos') || '[]');

function salvarPedidosOcultos() {
    localStorage.setItem('pedidosOcultos', JSON.stringify(pedidosOcultos));
}

function renderPedidos(pedidos) {
    // Ordena por id crescente
    pedidos = pedidos.slice().sort((a, b) => a.id - b.id);
    const colunas = {
        pendente: document.getElementById('pendente'),
        em_preparo: document.getElementById('em_preparo'),
        finalizado: document.getElementById('finalizado')
    };
    // Limpa as colunas (mantendo o h2)
    Object.values(colunas).forEach(col => {
        col.querySelectorAll('.card-pedido').forEach(card => card.remove());
    });
    // Adiciona os cards
    pedidos.forEach(pedido => {
        const card = document.createElement('div');
        card.className = 'card-pedido';
        card.setAttribute('data-id', pedido.id);
        // Mostrar apenas o número sequencial do pedido
        card.innerHTML = `<strong>Nº Pedido:</strong> ${pedido.id}<br><strong>Cliente:</strong> ${pedido.cliente}`;
        colunas[pedido.status].appendChild(card);
    });
}

function fetchPedidos() {
    fetch('listar_pedidos.php')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderPedidos(data.pedidos);
            }
        });
}

// Atualiza a cada 2 segundos
setInterval(fetchPedidos, 2000);
// Primeira chamada
fetchPedidos(); 