<?php include('func/function.php') ?>
<?php if (!isLoggedIn()) {
	header('location: auth.sign-in.php');
} ?>
<?php

if (isset($_GET['update']) && isset($_GET['guid']) && isset($_GET['isProduction'])) {
	updateTabletVersion($_GET['guid'], $_GET['isProduction']);
}

if (isset($_GET['insert'])) {
	$newGuid = strtoupper(generateGUID());
	insertTabletVersion($newGuid);
	generateJsonFile($newGuid);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>
		<?php echo $pageList[$urlPage] . ' - ' . $siteName; ?>
	</title>
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
		<?php include('func/menu.php') ?>
		<div class="content-wrapper">
			<?php include('func/nav_bar.php') ?>
			<div class="content">
				<header class="page-header">
					<div class="d-flex align-items-center">
						<div class="mr-auto">
							<h1 class="separator">Versões do Tablet</h1>
						</div>
					</div>
				</header>
				<section class="page-content container-fluid">
					<div class="row">
						<div class="col-12">
							<div class="card">
								<h5 class="card-header">Versões</h5>
								<div class="card-body">
									<table id="bs4-table" class="table table-striped table-bordered" style="width:100%">
										<thead>
											<tr>
												<th>Data</th>
												<th>ID da Versão</th>
												<th>Publicada em Produção</th>
												<th>Qtd.Tablets</th>
												<th>Reverter Versão</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$result = tabletVersionList();
											while ($row = mysqli_fetch_array($result)) { ?>
												<tr>
													<td><?php echo $row['DControl']; ?></td>
													<td><?php echo $row['TabletVersion']; ?></td>
													<td><?php echo $row['IsProduction'] == 1 ? 'Sim' : 'Não'; ?></td>
													<td><?php echo getTabletVersionDetail($row['TabletVersion']); ?></td>
													<td><a href="update-tablet.php?update=1&guid=<?php echo $row['TabletVersion']; ?>&isProduction=1">Reverter</a></td>
												</tr>
											<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<th>Data</th>
												<th>ID da Versão</th>
												<th>Publicada em Produção</th>
												<th>Qtd.Tablets</th>
												<th>Reverter Versão</th>
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
	<?php include('func/footer.php') ?>
</body>

</html>