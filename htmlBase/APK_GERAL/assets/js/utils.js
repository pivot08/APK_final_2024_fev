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
            window.location.href = "../vivo/vivo.html";
        }, 30000); // 30 segundos em milissegundos
    }

    // Verifica se o login foi feito pela Claro
    if (username === "claro@operadoras") {
        // Define um timeout de 30 segundos para redirecionar para a URL da Claro
        setTimeout(function () {
            window.location.href = "../claro/claro.html";
        }, 30000); // 30 segundos em milissegundos
    }

    // Verifica se o login foi feito pela Tim
    if (username === "tim@operadoras") {
        // Define um timeout de 30 segundos para redirecionar para a URL da Tim
        setTimeout(function () {
            window.location.href = "../tim/tim.html";
        }, 30000); // 30 segundos em milissegundos
    }

    // Verifica se o login foi feito pela Geral
    if (username === "geral@apk") {
        // Define um timeout de 60 segundos para redirecionar para a URL geral
        setTimeout(function () {
            window.location.href = "../index.html";
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