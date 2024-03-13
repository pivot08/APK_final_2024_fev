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


$(window).on('load', function() {
    // Oculte o overlay de carregamento
    $('#loading-overlay').css('opacity', 0);
    // Mostre o conteúdo da página com fadeIn
    $('#loadingContainer').fadeIn(1250, function() {
        // Após a conclusão do fadeIn, oculte o overlay
        $('#loading-overlay').css('display', 'none');
    });
});

$(document).ready(function() {
    var videos = $(".video-sequence").toArray();

    function playNextVideo(index) {
        if (index < videos.length) {
            var video = videos[index];
            var overlay = $(video).prev('.video-overlay');

            // Inicia o vídeo
            video.play();

            // Define um atraso antes de esconder a overlay
            setTimeout(function() {
                overlay.hide();
            }, 2000); // Atraso de 1000 milissegundos (1 segundo)

            // Quando o vídeo atual termina, chama a função para o próximo vídeo
            $(video).on('ended', function() {
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
});

window.onload = function() {
    let links = document.getElementsByTagName('a');
    for (let i = 0; i < links.length; i++) {
        links[i].addEventListener('click', function() {
            localStorage.setItem('chosenIndex', window.location.href);
        });
    }
}

document.addEventListener('DOMContentLoaded', (event) => {
    setTimeout(function() {
        let redirectPage = localStorage.getItem('chosenIndex');
        if (redirectPage) {
            window.location.replace(redirectPage);
        }
    }, 10000);  // Redireciona após 10 segundos
});


// Função para verificar o login e redirecionar
function checkAndRedirect() {
    var username = sessionStorage.getItem("username");

    // Exibe a div correspondente ao login
    showLoginDiv(username);

    // Verifica se o login foi feito pela Vivo
    if (username === "vivo@operadoras") {
        // Define um timeout de 30 segundos para redirecionar para a URL da Vivo
        setTimeout(function () {
            window.location.href = "vivo/vivo.html";
        }, 30000); // 30 segundos em milissegundos
    }

    // Verifica se o login foi feito pela Claro
    if (username === "claro@operadoras") {
        // Define um timeout de 30 segundos para redirecionar para a URL da Claro
        setTimeout(function () {
            window.location.href = "claro/claro.html";
        }, 30000); // 30 segundos em milissegundos
    }

    // Verifica se o login foi feito pela Tim
    if (username === "tim@operadoras") {
        // Define um timeout de 30 segundos para redirecionar para a URL da Tim
        setTimeout(function () {
            window.location.href = "tim/tim.html";
        }, 30000); // 30 segundos em milissegundos
    }

    // Verifica se o login foi feito pela Geral
    if (username === "geral@apk") {
        // Define um timeout de 60 segundos para redirecionar para a URL geral
        setTimeout(function () {
            window.location.href = "index.html";
        }, 60000); // 60 segundos em milissegundos
    }
}

// Função para mostrar a div correspondente ao login
function showLoginDiv(username) {
  

    // Mostra a div correspondente ao login
    switch (username) {
        case "vivo@operadoras":
            document.getElementById("divOpVivo").style.display = "block";
            break;

        case "claro@operadoras":
            document.getElementById("divOpClaro").style.display = "block";
            break;

        case "tim@operadoras":
            document.getElementById("divOpTim").style.display = "block";
            break;

        case "geral@apk":
            document.getElementById("divOpGeral").style.display = "block";
            break;

        // Adicione mais casos conforme necessário para outros logins

        default:
            // Caso não seja nenhum dos logins específicos, não mostra nenhuma div
            break;
    }
}



// Chame a função quando a página carregar
//checkAndRedirect();