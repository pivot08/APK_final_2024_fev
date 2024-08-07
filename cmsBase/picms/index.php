<?php include('func/function.php') ?>
<?php if (isset($_POST['login_btn'])) {
	loginAdmin();
} else if (!isLoggedIn()) {
	header('location: auth.sign-in.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php echo $pageList[$urlPage] .' - '. $siteName; ?></title>
	<!-- ================== GOOGLE FONTS ==================-->
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500" rel="stylesheet">
	<!-- ======================= GLOBAL VENDOR STYLES ========================-->
	<link rel="stylesheet" href="../picms/assets/css/vendor/bootstrap.css">
	<link rel="stylesheet" href="../picms/assets/vendor/metismenu/dist/metisMenu.css">
	<link rel="stylesheet" href="../picms/assets/vendor/switchery-npm/index.css">
	<link rel="stylesheet" href="../picms/assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
	<!-- ======================= LINE AWESOME ICONS ===========================-->
	<link rel="stylesheet" href="../picms/assets/css/icons/line-awesome.min.css">
	<link rel="stylesheet" href="../picms/assets/css/icons/simple-line-icons.css">
	<!-- ======================= DRIP ICONS ===================================-->
	<link rel="stylesheet" href="../picms/assets/css/icons/dripicons.min.css">
	<!-- ======================= MATERIAL DESIGN ICONIC FONTS =================-->
	<link rel="stylesheet" href="../picms/assets/css/icons/material-design-iconic-font.min.css">
	<!-- ======================= PAGE VENDOR STYLES ===========================-->
	<link rel="stylesheet" href="../picms/assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.css">
	<!-- ======================= GLOBAL COMMON STYLES ============================-->
	<link rel="stylesheet" href="../picms/assets/css/common/main.bundle.css">
	<!-- ======================= LAYOUT TYPE ===========================-->
	<link rel="stylesheet" href="../picms/assets/css/layouts/vertical/core/main.css">
	<!-- ======================= MENU TYPE ===========================-->
	<link rel="stylesheet" href="../picms/assets/css/layouts/vertical/menu-type/default.css">
	<!-- ======================= THEME COLOR STYLES ===========================-->
	<link rel="stylesheet" href="../picms/assets/css/layouts/vertical/themes/theme-a.css">
</head>
<body>
	<!-- START APP WRAPPER -->
	<div id="app">
		<?php include('func/menu.php') ?>
		<div class="content-wrapper">
			<?php include('func/nav_bar.php') ?>			
			
			<div class="content">
				<!--START PAGE HEADER -->
				<header class="page-header">
					<div class="d-flex align-items-center">
						<div class="mr-auto">
							<h1>Bem-vindo</h1>
							<p>Bem-vindo ao sistema de administração do APK Samsung.<br>Preparamos abaixo alguns <strong>tutoriais</strong> de como criar e gerenciar o contéudo.</
<BR><BR>
							<p>
<strong>Noções básicas deste CMS:</strong><br>
1 - Para qualquer página é necessário criar um template e após isso, criar o conteúdo desta página nos menus abaixo.<br><br>
2 - Após qualquer modificação, é necessário clicar em "Atualizar Site" ou "Atualizar Tablet"  para que as alterações sejam efetivadas no sistema.<br><br>
3 - Na escolha das criações ou edições, você verá 3 opções: <br>

a) Template (onde o conteúdo deve aparecer) *<br>
Escolha o template que você criou, ou seja, onde este conteúdo deve ser incluído<BR><BR>

b) Template Filho (onde o link deve direcionar) *<br>
Escolha aqui para qual página o link deste conteúdo deve ser direcionado<BR><BR>

c) Conteúdo Filho (onde o link deve direcionar - se for a página final de texto)<br>
Escolha aqui para qual página o link deste conteúdo deve ser direcionado (aqui somente para páginas de texto e neste caso, deixe o Template Filho vazio).<BR><BR>
<br>
</p>


							<div class="row" style="padding:10px;">
								<div style="padding:10px;max-width:400px;">
									<h3>1 - ÍNDICE E SCREENSAVERS</h3><br>
									<video width="320" height="240" controls>
									<source src="tutorial/1-Home-Screensaver-Indice.mov" type="video/mp4">
									Seu navegador não suporta a tag de vídeo.
									</video>
								</div>
								<div style="padding:10px;max-width:400px;">
									<h3>2 - CRIAÇÃO DE BOTÕES DE CATEGORIA</h3><br>
									<video width="320" height="240" controls>
									<source src="tutorial/2-tutorial-paginas-botao.mov" type="video/mp4">
									Seu navegador não suporta a tag de vídeo.
									</video>
								</div>
								<div style="padding:10px;max-width:400px;">
									<h3>3 - CRIAÇÃO DE BOTÕES DE PRODUTOS</h3><br>
									<video width="320" height="240" controls>
									<source src="tutorial/3-tutorial-pagina-produto.mov" type="video/mp4">
									Seu navegador não suporta a tag de vídeo.
									</video>
								</div>
								<div style="padding:10px;max-width:400px;">
									<h3>4 - CRIAÇÃO DE PÁGINAS DE DETALHES (Texto, video, imagem)</h3><br>
									<video width="320" height="240" controls>
									<source src="tutorial/4-tutorial-pagina-detalhe-produto-texto.mov" type="video/mp4">
									Seu navegador não suporta a tag de vídeo.
									</video>
								</div>

								


								
							</div>

						</div>						
					</div>
				</header>
				<!--END PAGE HEADER -->
				<!--START PAGE CONTENT -->
				<section class="page-content container-fluid">
					<!-- Olá, para cadastrar novos números da sorte para o seu cliente, acesse o menu ao lado. -->
				</section>
			</div>
		</div>
	</div>

	<!-- END CONTENT WRAPPER -->
	<!-- ================== GLOBAL VENDOR SCRIPTS ==================-->
	<script src="../picms/assets/vendor/modernizr/modernizr.custom.js"></script>
	<script src="../picms/assets/vendor/jquery/dist/jquery.min.js"></script>
	<script src="../picms/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="../picms/assets/vendor/js-storage/js.storage.js"></script>
	<script src="../picms/assets/vendor/js-cookie/src/js.cookie.js"></script>
	<script src="../picms/assets/vendor/pace/pace.js"></script>
	<script src="../picms/assets/vendor/metismenu/dist/metisMenu.js"></script>
	<script src="../picms/assets/vendor/switchery-npm/index.js"></script>
	<script src="../picms/assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
	<!-- ================== PAGE LEVEL VENDOR SCRIPTS ==================-->
	<script src="../picms/assets/vendor/countup.js/dist/countUp.min.js"></script>
	<script src="../picms/assets/vendor/chart.js/dist/Chart.bundle.min.js"></script>
	<script src="../picms/assets/vendor/flot/jquery.flot.js"></script>
	<script src="../picms/assets/vendor/jquery.flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
	<script src="../picms/assets/vendor/flot/jquery.flot.resize.js"></script>
	<script src="../picms/assets/vendor/flot/jquery.flot.time.js"></script>
	<script src="../picms/assets/vendor/flot.curvedlines/curvedLines.js"></script>
	<script src="../picms/assets/vendor/datatables.net/js/jquery.dataTables.js"></script>
	<script src="../picms/assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
	<!-- ================== GLOBAL APP SCRIPTS ==================-->
	<script src="../picms/assets/js/global/app.js"></script>
	<!-- ================== PAGE LEVEL SCRIPTS ==================-->
	<script src="../picms/assets/js/components/countUp-init.js"></script>
	<script src="../picms/assets/js/cards/counter-group.js"></script>
	<script src="../picms/assets/js/cards/recent-transactions.js"></script>
	<script src="../picms/assets/js/cards/monthly-budget.js"></script>
	<script src="../picms/assets/js/cards/users-chart.js"></script>
	<script src="../picms/assets/js/cards/bounce-rate-chart.js"></script>
	<script src="../picms/assets/js/cards/session-duration-chart.js"></script>
</body>
</html>
