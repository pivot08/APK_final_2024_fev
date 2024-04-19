$(window).on('load', function () {
    // Oculte o overlay de carregamento
    $('#loading-overlay').css('opacity', 0);
    // Mostre o conteúdo da página com fadeIn
    $('#loadingContainer').fadeIn(1250, function () {
        // Após a conclusão do fadeIn, oculte o overlay
        $('#loading-overlay').css('display', 'none');
    });
});

function playNextVideo(index) {
    var videos = $(".video-sequence").toArray();

    if (videos != undefined && videos != null && videos.length > 0) {
        if (index < videos.length) {
            var video = videos[index];
            var overlay = $(video).prev('.video-overlay');

            // Inicia o vídeo
            video.play();

            // Define um atraso antes de esconder a overlay
            setTimeout(function () {
                overlay.hide();
            }, 2000); // Atraso de 1000 milissegundos (1 segundo)

            // Quando o vídeo atual termina, chama a função para o próximo vídeo
            $(video).on('ended', function () {
                // Se ainda há vídeos na sequência, toca o próximo
                if (index + 1 < videos.length) {
                    playNextVideo(index + 1);
                } else {
                    // Se é o último vídeo, reinicia a sequência
                    playNextVideo(0);
                }
            });
        }
    }
}

const urlOrigin = new URL(window.location.href);
const paramsOrigin = new URLSearchParams(urlOrigin.search);
const origin = paramsOrigin.get('origin') != null && paramsOrigin.get('origin') != undefined ? paramsOrigin.get('origin') : '';
const searchParams = paramsOrigin.get('searchParams') != null && paramsOrigin.get('searchParams') != undefined ? paramsOrigin.get('searchParams') : '';

switch (origin) {
    case 'vivo':
        linkHome = '../../vivo/index.html' + window.atob(searchParams);
        if (document.getElementById("divOpVivo")) {
            document.getElementById("divOpVivo").style.display = "block";
            document.getElementById("divOpVivo").getElementsByTagName('a')[0].href = linkHome
        }
        break;
    case 'claro':
        linkHome = '../../claro/index.html' + window.atob(searchParams);
        if (document.getElementById("divOpClaro")) {
            document.getElementById("divOpClaro").style.display = "block";
            document.getElementById("divOpClaro").getElementsByTagName('a')[0].href = linkHome;
        }
        break;
    case 'tim':
        linkHome = '../../tim/index.html' + window.atob(searchParams);
        if (document.getElementById("divOpTim")) {
            document.getElementById("divOpTim").style.display = "block";
            document.getElementById("divOpTim").getElementsByTagName('a')[0].href = linkHome;
        }
        break;
    case '':
        if (document.getElementById("divOpGeral"))
            document.getElementById("divOpGeral").style.display = "block";
        break;
}