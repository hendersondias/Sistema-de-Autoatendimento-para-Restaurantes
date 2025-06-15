let dragged = null;

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
        card.setAttribute('draggable', 'true');
        card.setAttribute('data-id', pedido.id);
        // Exibir cada item em uma linha
        let itensFormatados = pedido.descricao.split(',').map(item => item.trim()).map(item => `<div>${item}</div>`).join('');
        card.innerHTML = `<strong>Nº Pedido:</strong> ${pedido.id}<br><strong>Cliente:</strong> ${pedido.cliente}<br><strong>Pedido:</strong><br>${itensFormatados}<strong>Valor:</strong> R$ ${pedido.valor}`;
        if (pedido.status === 'finalizado') {
            const btn = document.createElement('button');
            btn.textContent = 'Finalizar';
            btn.className = 'btn-remover-pedido';
            btn.style.background = '#e74c3c';
            btn.style.color = '#fff';
            btn.style.border = 'none';
            btn.style.borderRadius = '5px';
            btn.style.padding = '6px 16px';
            btn.style.marginTop = '10px';
            btn.style.cursor = 'pointer';
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                // AJAX para ocultar o pedido
                fetch('ocultar_pedido.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + encodeURIComponent(pedido.id)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        card.remove();
                    } else {
                        alert('Erro ao ocultar pedido: ' + (data.message || ''));
                    }
                })
                .catch(() => {
                    alert('Erro de comunicação com o servidor.');
                });
            });
            card.appendChild(document.createElement('br'));
            card.appendChild(btn);
        }
        colunas[pedido.status].appendChild(card);
    });
    // Reaplica drag and drop
    document.querySelectorAll('.card-pedido').forEach(card => {
        card.addEventListener('dragstart', function(e) {
            dragged = this;
            setTimeout(() => this.classList.add('dragging'), 0);
        });
        card.addEventListener('dragend', function(e) {
            this.classList.remove('dragging');
            dragged = null;
        });
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

setInterval(fetchPedidos, 5000); // Atualiza a cada 5 segundos
fetchPedidos(); // Primeira chamada

function allowDrop(ev) {
    ev.preventDefault();
    ev.currentTarget.classList.add('over');
}
function drop(ev, status) {
    ev.preventDefault();
    ev.currentTarget.classList.remove('over');
    if (dragged) {
        ev.currentTarget.appendChild(dragged);
        // AJAX para atualizar status
        let pedidoId = dragged.getAttribute('data-id');
        let formData = new FormData();
        formData.append('id', pedidoId);
        formData.append('status', status);
        fetch('atualizar_status.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Erro ao atualizar status: ' + (data.message || ''));
            }
        })
        .catch(() => {
            alert('Erro de comunicação com o servidor.');
        });
    }
}
document.querySelectorAll('.coluna-pedidos').forEach(col => {
    col.addEventListener('dragleave', function(e) {
        this.classList.remove('over');
    });
}); 