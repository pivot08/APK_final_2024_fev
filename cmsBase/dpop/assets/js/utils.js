$(document).ready(function () {

    $('#carousel-promo').owlCarousel({
        loop: true,
        margin: 10,
        dots: false,
        autoplay: true,
        autoplaySpeed: 300,
        nav: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });

});

function goBack() {
    history.back(-1);
}

$(window).on('load', function () {
    // Oculte o overlay de carregamento
    $('#loading-overlay').css('opacity', 0);
    // Mostre o conteúdo da página com fadeIn
    $('#loadingContainer').fadeIn(1000, function () {
        // Após a conclusão do fadeIn, oculte o overlay
        $('#loading-overlay').css('display', 'none');
    });
});

// Espera 2 segundos e depois oculta o vídeo
setTimeout(function () {
    var videoPromo = document.querySelector('.videoPromo');
    videoPromo.style.display = 'none';

    // Seleciona o elemento com a classe 'destaques' e 'bac-preto'
    var destaqueElement = document.querySelector('.destaques.bac-preto');
    // Remove a classe 'bac-preto' e adiciona a classe 'bac-branco'
    destaqueElement.classList.remove('bac-preto');
    destaqueElement.classList.add('bac-branco');

    // Torna o carousel-promo visível
    var carouselPromo = document.getElementById('carousel-promo');
    carouselPromo.style.display = 'block';

    // Torna todos os h5 dentro de .destaques visíveis
    var destaquesH5 = document.querySelectorAll('.destaques h5');
    destaquesH5.forEach(function (h5) {
        h5.style.display = 'block';
    });
}, 16000000);

const urlOrigin = new URL(window.location.href);
const paramsOrigin = new URLSearchParams(urlOrigin.search);
const origin = paramsOrigin.get('origin') != null && paramsOrigin.get('origin') != undefined ? paramsOrigin.get('origin') : '';
const searchParams = paramsOrigin.get('searchParams') != null && paramsOrigin.get('searchParams') != undefined ? paramsOrigin.get('searchParams') : '';
var linkHome = '';

var links = document.getElementsByTagName('a');
for (var i = 0; i < links.length; i++) {
    if (links[i].href.indexOf('javascript:history.') < 0)
        links[i].href = links[i].href + (links[i].href.indexOf('?') > 0 ? '&' : '?') + 'origin=' + origin + '&searchParams=' + searchParams;
}

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

function runTimer(linkHome) {
    console.log(linkHome)
    window.location.href = linkHome;
}

const timer = setTimeout(() => {
    clearTimeout(timer);
    runTimer(linkHome);
}, 30000);
