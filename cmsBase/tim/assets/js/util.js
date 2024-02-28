$(window).on('load', function() {
    // Oculte o overlay de carregamento
    $('#loading-overlay').css('opacity', 0);
    // Mostre o conteúdo da página com fadeIn
    $('#loadingContainer').fadeIn(1250, function() {
        // Após a conclusão do fadeIn, oculte o overlay
        $('#loading-overlay').css('display', 'none');
    });
});

$(document).ready(function(){
    $('.nav-link').click(function(){
        $('.seta-img').css('visibility', 'hidden'); // Oculta todas as setas
        $(this).closest('li').find('.seta-img').css('visibility', 'visible'); // Exibe a seta dentro do botão clicado
    });
});

function formatCurrency(number) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(number);
}