const signUpButton = document.getElementById('signUp');
const logInButton = document.getElementById('logIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
    container.classList.add("right-panel-active");
});

logInButton.addEventListener('click', () => {
    container.classList.remove("right-panel-active");
});

window.addEventListener('DOMContentLoaded', function() {
    const origem = container.getAttribute('data-mensagem-origem');
    if (origem === 'cadastro') {
        container.classList.add('right-panel-active');
    }
    if (origem === 'login') {
        container.classList.remove('right-panel-active');
    }
});