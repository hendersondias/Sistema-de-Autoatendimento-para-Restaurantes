document.addEventListener('DOMContentLoaded', function() {
    // Evento para transformar o card em formulário
    document.querySelectorAll('.add-product-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Evita abrir o formulário se já estiver aberto
            if (card.querySelector('form')) return;
            e.stopPropagation();
            const categoriaId = card.getAttribute('data-categoria-id');
            card.innerHTML = `<form class="form-add-produto" enctype="multipart/form-data" style="width:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                <input type="hidden" name="categoria_id" value="${categoriaId}">
                <input type="text" name="nome" placeholder="Nome do produto" required style="margin-bottom:8px;width:90%"><br>
                <textarea name="descricao" placeholder="Descrição" required style="margin-bottom:8px;width:90%"></textarea><br>
                <input type="number" name="preco" placeholder="Preço" step="0.01" min="0" required style="margin-bottom:8px;width:90%"><br>
                <input type="file" name="imagem" accept="image/*" required style="margin-bottom:8px;width:90%"><br>
                <div style='display:flex;gap:10px;'>
                  <button type="submit" style="background:#2781d6;color:#fff;border:none;padding:8px 20px;border-radius:8px;">Salvar</button>
                  <button type="button" class="cancelar-add" style="background:#e74c3c;color:#fff;border:none;padding:8px 20px;border-radius:8px;">Cancelar</button>
                </div>
            </form>`;
            card.querySelector('.cancelar-add').onclick = function(ev) {
                ev.stopPropagation();
                card.innerHTML = `<div class=\"details\" style=\"text-align:center; width:100%; cursor:pointer; display:flex; flex-direction:column; align-items:center; justify-content:center;\">
                    <span style=\"font-size:4em; color:#2781d6; display:block;\">+</span>
                    <span style=\"font-size:1.2em; color:#222; margin-top:10px;\">Adicionar Produto</span>
                </div>`;
            };
            card.querySelector('form').onsubmit = function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch('cadastrar_produto.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erro ao cadastrar produto: ' + (data.message || ''));
                    }
                })
                .catch(() => alert('Erro ao cadastrar produto.'));
            };
        });
    });

    // Evento para esgotar/disponibilizar produto
    document.querySelectorAll('.btn-esgotar').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = btn.getAttribute('data-id');
            fetch('esgotar_produto.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + encodeURIComponent(id)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erro ao atualizar disponibilidade: ' + (data.message || ''));
                }
            })
            .catch(() => alert('Erro ao atualizar disponibilidade.'));
        });
    });

    // Evento para remover produto
    document.querySelectorAll('.btn-remover').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = btn.getAttribute('data-id');
            if (!confirm('Tem certeza que deseja remover este produto do cardápio?')) return;
            fetch('remover_produto.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + encodeURIComponent(id)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erro ao remover produto: ' + (data.message || ''));
                }
            })
            .catch(() => alert('Erro ao remover produto.'));
        });
    });
}); 