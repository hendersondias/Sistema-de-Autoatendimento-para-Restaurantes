document.addEventListener('DOMContentLoaded', function() {
    const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
    const carrinhoContador = document.getElementById('carrinho-contador');
    const carrinhoFlutuante = document.getElementById('carrinho-flutuante');

    // Atualizar contador do carrinho
    function atualizarContador() {
        const totalItens = carrinho.reduce((total, item) => total + item.quantidade, 0);
        carrinhoContador.textContent = totalItens;
    }

    // Adicionar item ao carrinho
    document.querySelectorAll('.adicionar-carrinho').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const nome = this.dataset.nome;
            const preco = parseFloat(this.dataset.preco);

            const itemExistente = carrinho.find(item => item.id === id);
            if (itemExistente) {
                itemExistente.quantidade++;
            } else {
                carrinho.push({ id, nome, preco, quantidade: 1 });
            }

            localStorage.setItem('carrinho', JSON.stringify(carrinho));
            atualizarContador();
            alert('Item adicionado ao carrinho!');
        });
    });

    // Redirecionar para o carrinho ao clicar no botão flutuante
    carrinhoFlutuante.addEventListener('click', function() {
        window.location.href = 'carrinho.php';
    });

    // Inicializar contador
    atualizarContador();
}); 