let pressTimer;

var clickQuant = 0;
function startTimer() {
    clickQuant++;
    if (clickQuant == 5) {
        window.location.href = '../index.html';
    }
}

function clearTimer() {
    clearTimeout(pressTimer);
}

$(window).on('load', function () {
    // Oculte o overlay de carregamento
    $('#loading-overlay').css('opacity', 0);
    // Mostre o conteúdo da página com fadeIn
    $('#loadingContainer').fadeIn(1250, function () {
        // Após a conclusão do fadeIn, oculte o overlay
        $('#loading-overlay').css('display', 'none');
    });
});

function loopVideos() {
    var videos = $(".video-sequence").toArray();

    function playNextVideo(index) {
        if (index < videos.length) {
            var video = videos[index];
            var overlay = $(video).prev('.video-overlay');

            // Inicia o vídeo
            video.play();

            // Define um atraso antes de esconder a overlay
            var timeoutOverlay = setTimeout(function () {
                overlay.hide();
            }, 2000); // Atraso de 1000 milissegundos (1 segundo)

            // Quando o vídeo atual termina, chama a função para o próximo vídeo
            $(video).on('ended', function () {
                clearTimeout(timeoutOverlay);
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

    // Inicia a reprodução do primeiro vídeo
    playNextVideo(0);
}

const url = new URL(window.location.href);
const params = new URLSearchParams(url.search);
const applicationID = params.get('applicationID');
function redirect() {
    if (applicationID)
        applicationID = 0;

    window.location.href = 'index.html?applicationID=' + applicationID;
}
setTimeout(redirect, 60000);
