<?php include ('func/function.php') ?>
<?php if (!isLoggedIn()) {
	header('location: auth.sign-in.php');
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php echo $pageList[$urlPage] . ' - ' . $siteName; ?></title>
	<!-- ================== GOOGLE FONTS ==================-->
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500" rel="stylesheet">
	<!-- ======================= GLOBAL VENDOR STYLES ========================-->
	<link rel="stylesheet" href="../picms/assets/css/vendor/bootstrap.css">
	<link rel="stylesheet" href="../picms/assets/vendor/metismenu/dist/metisMenu.css">
	<link rel="stylesheet" href="../picms/assets/vendor/switchery-npm/index.css">
	<link rel="stylesheet" href="../picms/assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
	<!-- ======================= LINE AWESOME ICONS ===========================-->
	<link rel="stylesheet" href="../picms/assets/css/icons/line-awesome.min.css">
	<!-- ======================= DRIP ICONS ===================================-->
	<link rel="stylesheet" href="../picms/assets/css/icons/dripicons.min.css">
	<!-- ======================= MATERIAL DESIGN ICONIC FONTS =================-->
	<link rel="stylesheet" href="../picms/assets/css/icons/material-design-iconic-font.min.css">
	<!-- ======================= PAGE LEVEL VENDOR STYLES ============================-->
	<link rel="stylesheet" href="../picms/assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.css">
	<!-- ======================= GLOBAL COMMON STYLES ============================-->
	<link rel="stylesheet" href="../picms/assets/css/common/main.bundle.css">
	<!-- ======================= LAYOUT TYPE ===========================-->
	<link rel="stylesheet" href="../picms/assets/css/layouts/vertical/core/main.css">
	<!-- ======================= MENU TYPE ===========================================-->
	<link rel="stylesheet" href="../picms/assets/css/layouts/vertical/menu-type/default.css">
	<!-- ======================= THEME COLOR STYLES ===========================-->
	<link rel="stylesheet" href="../picms/assets/css/layouts/vertical/themes/theme-a.css">
</head>

<body>
	<!-- CONTENT WRAPPER -->
	<div id="app">
		<?php include ('func/menu.php') ?>
		<div class="content-wrapper">
			<?php include ('func/nav_bar.php') ?>
			<div class="content">
				<header class="page-header">
					<div class="d-flex align-items-center">
						<div class="mr-auto">
							<h1 class="separator">Histórico</h1>
						</div>
					</div>
				</header>
				<section class="page-content container-fluid">
					<div class="row">
						<div class="col-12">
							<div class="card">
								<h5 class="card-header">Registro de Navegação - Por Localização</h5>
								<div class="card-body">
									<table id="bsNavigation" class="table table-striped table-bordered" style="width:100%">
										<thead>
											<tr>
												<th>Data</th>
												<th>Região</th>
												<th>UF</th>
												<th>Cidade</th>
												<th>Bairro</th>
												<th>Ação</th>
												<th>Aplicativo</th>
												<th>Tipo</th>
												<th>Template</th>
												<th>Conteúdo</th>
												<th>Versão</th>
												<th>Dispositivo</th>
												<th>Modelo</th>
												<th>Versão mais recente?</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Data</th>
												<th>Região</th>
												<th>UF</th>
												<th>Cidade</th>
												<th>Bairro</th>
												<th>Ação</th>
												<th>Aplicativo</th>
												<th>Tipo</th>
												<th>Template</th>
												<th>Conteúdo</th>
												<th>Versão</th>
												<th>Dispositivo</th>
												<th>Modelo</th>
												<th>Versão mais recente?</th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
	<?php include ('func/footer.php') ?>
	<script>
		$(document).ready(function () {
			$('#bsNavigation').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url": "func/navigation-control-location.php",
					"type": "POST"
				},
				"columns": [
					{ "data": "ActionDate" },         // Data
					{ "data": "Region" },      			 // Loja
					{ "data": "State" },      			 // Loja
					{ "data": "City" },      			 // Loja
					{ "data": "Neighbor" },      			 // Loja
					{ "data": "Action" },             // Ação
					{ "data": "Application" },        // Aplicativo
					{ "data": "PageType" },           // PageType
					{ "data": "Template" },           // Template
					{ "data": "TemplateContent" },    // Conteúdo
					{ "data": "TabletVersion" },      // Versão
					{ "data": "DeviceID" },           // Dispositivo
					{ "data": "DeviceModel" },         // Modelo
					{ "data": "IsProduction" },       // Versão mais recente?
				]
			});
		});

	</script>
</body>

</html>