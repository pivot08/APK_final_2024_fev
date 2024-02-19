let pressTimer;
function startTimer() {
    pressTimer = window.setTimeout(function() {
        window.location.href = '../index.html';
    }, 5000);  // 5000 ms = 5 segundos
}


$(document).ready(function () {

    $('#carousel-promo').owlCarousel({
        loop: true,
        margin: 10,
        dots: false,
        autoplay: true,
        autoplaySpeed: 300,
        nav: false,
        responsive: {
            0:{
                items: 1
            },
            600:{
                items: 1
            },
            1000:{
                items: 1
            }
        }
    });

});



        function goBack() {
            history.back(-1);
        }
    

        

        $(window).on('load', function() {
            // Oculte o overlay de carregamento
            $('#loading-overlay').css('opacity', 0);
            // Mostre o conteúdo da página com fadeIn
            $('#loadingContainer').fadeIn(1000, function() {
                // Após a conclusão do fadeIn, oculte o overlay
                $('#loading-overlay').css('display', 'none');
            });
        });
  


 // Função que será chamada após 40 segundos
 function redirect() {
    window.location.href = '../index.html';
}

// Definindo o redirecionamento para daqui a 40 segundos (40000 milissegundos)
setTimeout(redirect, 90000);



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
    destaquesH5.forEach(function(h5) {
        h5.style.display = 'block';
    });
}, 16000000); 