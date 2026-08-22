/**
 * Validação client-side dos formulários de criar/editar notícia.
 * Complementa a validação do PHP (que é a que realmente protege o banco),
 * dando feedback mais rápido ao usuário antes de enviar o formulário.
 */
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    if (!form) {
        return; // página sem formulário (ex: index.php), não faz nada
    }

    form.addEventListener('submit', function (evento) {
        const titulo = form.querySelector('#titulo');
        const conteudo = form.querySelector('#conteudo');
        const autor = form.querySelector('#autor');
        const dataPublicacao = form.querySelector('#data_publicacao');

        const camposObrigatorios = [titulo, conteudo, autor, dataPublicacao];
        let valido = true;

        camposObrigatorios.forEach(function (campo) {
            if (campo.value.trim() === '') {
                valido = false;
                campo.style.borderColor = '#b30000';
            } else {
                campo.style.borderColor = '';
            }
        });

        if (!valido) {
            evento.preventDefault(); // impede o envio do formulário
            alert('Preencha todos os campos obrigatórios (marcados com *).');
        }
    });
});