document.addEventListener('DOMContentLoaded', function() {

    const gerarBotoes = document.querySelectorAll('.gerar-btn');
    const verificarBtn = document.getElementById('verificar-btn');
    const linkDownloadDiv = document.getElementById('link-download');
    const downloadLink = document.getElementById('download-link');

    // Função para gerar um código aleatório
    function gerarCodigo() {
        const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let codigo = '';
        for (let i = 0; i < 12; i++) {
            codigo += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        }
        return codigo;
    }

    // Adiciona o evento de clique a TODOS os botões "Gerar"
    gerarBotoes.forEach(botao => {
        botao.addEventListener('click', function() {
            const nomeApp = this.getAttribute('data-app');
            const novoCodigo = gerarCodigo();

            // Encontra a div de resultado correspondente a este botão
            const card = this.closest('.app-card');
            const resultadoDiv = card.querySelector('.resultado-codigo');
            const codigoSpan = card.querySelector('.codigo-gerado');
            const nomeAppSpan = card.querySelector('.nome-app');

            // Mostra o resultado na tela
            nomeAppSpan.textContent = nomeApp;
            codigoSpan.textContent = novoCodigo;
            resultadoDiv.style.display = 'block';

            // Envia o código e o nome do app para o backend salvar no BD
            fetch('salvar_codigo.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ codigo: novoCodigo, produto: nomeApp })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Ocorreu um erro ao gerar o código. Tente novamente.');
                    console.error(data.message);
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                alert('Erro de comunicação com o servidor.');
            });
        });
    });

    // Evento de clique no botão "Verificar Código"
    verificarBtn.addEventListener('click', function() {
        const codigoDigitado = document.getElementById('input-codigo').value;

        if (!codigoDigitado) {
            alert('Por favor, digite um código.');
            return;
        }

        // Redireciona para o script de download, que fará a verificação no servidor
        downloadLink.href = 'download_arquivo.php?codigo=' + encodeURIComponent(codigoDigitado);
        linkDownloadDiv.style.display = 'block';
    });
});
