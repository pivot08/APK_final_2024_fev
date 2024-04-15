<?php include('func/function.php'); ?>
<?php if (!isLoggedIn()) {
	header('location: auth.sign-in.php');
} ?>
<?php
$applicationID = '';
$templateID = '';
$pageTypeID = '';
$template = '';
$buttonContent = '';
$headerText = '';
$color = '';
$isHeaderColorWhite = '0';
$isMainPage = '0';
$isOperatorExclusive = 0;
$isActive = '1';
$update = false;

if (isset($_GET['id'])) {
	$id = $_GET['id'];

	$record = templateGet($id);
	if (mysqli_num_rows($record) == 1) {
		$update = true;

		$n = mysqli_fetch_array($record);
		$applicationID = $n['ApplicationID'];
		$templateID = $n['TemplateID'];
		$pageTypeID = $n['PageTypeID'];
		$template = $n['Template'];
		$buttonContent = $n['ButtonContent'];
		$headerText = $n['HeaderText'];
		$color = $n['Color'];
		$isHeaderColorWhite = $n['IsHeaderColorWhite'];
		$isMainPage = $n['IsMainPage'];
		$isActive = $n['IsActive'];
	}
}

if (isset($_POST['save']) || isset($_POST['update'])) {
	$applicationID = $_POST['ApplicationID'];
	$templateID = $_POST['TemplateID'];
	$pageTypeID = $_POST['PageTypeID'];
	$template = $_POST['Template'];
	$buttonContent = $_POST['ButtonContent'];
	$headerText = $_POST['HeaderText'];
	$color = $_POST['Color'];

	if (isset($_POST['IsMainPage'])) {
		$isMainPage = $_POST['IsMainPage'];
	} else {
		$isMainPage = 0;
	}

	if (isset($_POST['IsActive'])) {
		$isActive = $_POST['IsActive'];
	} else {
		$isActive = 0;
	}

	if (isset($_POST['IsHeaderColorWhite'])) {
		$isHeaderColorWhite = $_POST['IsHeaderColorWhite'];
	} else {
		$isHeaderColorWhite = 0;
	}
}

if (isset($_POST['save'])) {
	templateInsert($applicationID, $pageTypeID, $template, '', $buttonContent, $headerText, '', '', '', $color, $isHeaderColorWhite, $isMainPage, $isOperatorExclusive, $isActive);
}
if (isset($_POST['update'])) {
	templateUpdate($templateID, $applicationID, $pageTypeID, $template, '', $buttonContent, $headerText, '', '', '', $color, $isHeaderColorWhite, $isMainPage, $isOperatorExclusive, $isActive);
}
if (isset($_POST['delete'])) {
	templateDelete($templateID);
}

if (isset($_POST['save']) || isset($_POST['update']) || isset($_POST['delete'])) {
	$msg = '';
	if (isset($_POST['save'])) {
		$msg = 'Dados inseridos com sucesso.';
	}
	if (isset($_POST['update'])) {
		$msg = 'Dados alterados com sucesso.';
	}
	if (isset($_POST['delete'])) {
		$msg = 'Registro excluído com sucesso.';
	}
	header('location: template-list.php?msg=' . $msg);
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
	<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
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
							<h1 class="separator">Cadastro de Templates</h1>
						</div>
					</div>
				</header>
				<section class="page-content container-fluid">
					<div class="row">
						<div class="col-8">
							<div class="card">
								<form method="post">
									<h5 class="card-header">Template *</h5>
									<h2 class="card-header" style="font-size:10px">
										- **<strong>texto</strong>** : ** antes e depois de um texto deixam o mesmo negrito<br>
										- __<i>texto</i>__ : __ antes e depois de um texto deixam o mesmo itálico<br>
										- para incluir o ícone Galaxy IA <img src="assets/images/ico-inteligencia.png" width="16">,
										insira o texto [ICO_AI]
									</h2>
									
									<input type="hidden" name="TemplateID" value="<?php echo $templateID; ?>">
									<div class="card-body">
										<div class="form-group">
											<label for="input">Nome *</label>
											<input type="text" name="Template" class="form-control"
												value="<?php echo $template; ?>" required>
										</div>
										<div class="form-group">
											<label for="input">Aplicativo *</label>
											<select class="form-control" name="ApplicationID" required
												onChange="pageTypeGet(this)">
												<option value="">Selecione</option>
												<?php
												$result = applicationByUserList($_SESSION['user']['UserID']);
												while ($row = mysqli_fetch_array($result)) { ?>
													<option value="<?php echo $row['ApplicationID']; ?>" <?php if ($row['ApplicationID'] == $applicationID) {
															echo 'selected';
														} ?>>
														<?php echo $row['Application']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
										<div class="form-group">
											<label for="input">Tipo de Template *</label>
											<select class="form-control" id="PageTypeID" name="PageTypeID" required>
												<option value="" data-id="0">Selecione</option>
												<?php
												$result = pageTypeByUserList($_SESSION['user']['UserID'], $applicationID);
												while ($row = mysqli_fetch_array($result)) { ?>
													<option value="<?php echo $row['PageTypeID']; ?>" <?php if ($row['PageTypeID'] == $pageTypeID) {
															echo 'selected';
														} ?>
														data-id="<?php echo $row['ApplicationID']; ?>">
														<?php echo $row['PageType']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
										<div class="form-group">
											<label for="input">Texto do Botão</label>
											<input type="text" name="ButtonContent" class="form-control"
												value="<?php echo $buttonContent; ?>">
										</div>
										<div class="form-group">
											<label for="input">Texto Cabeçalho</label>
											<input type="text" name="HeaderText" class="form-control"
												value="<?php echo $headerText; ?>">
										</div>
										<div class="form-group">
											<label for="input">Cor de Fundo</label>
											<input type="text" id="colorpicker-popup" name="Color" class="form-control"
												value="<?php echo $color; ?>">
										</div>
										<div class="form-group">
											<div class="col-12">
												<div class="input-group left">
													<input type="checkbox" name="IsMainPage" class="form-control"
														value="<?php echo $isMainPage; ?>" <?php if ($isMainPage) {
																echo "checked";
															} ?>
														onclick="if(this.checked){this.value='1';}else{this.value='0';}"> Página Inicial do APK?
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="col-12">
												<div class="input-group left">
													<input type="checkbox" name="IsHeaderColorWhite" class="form-control"
														value="<?php echo $isHeaderColorWhite; ?>" <?php if ($isHeaderColorWhite) {
																echo "checked";
															} ?>
														onclick="if(this.checked){this.value='1';}else{this.value='0';}"> Barra superior branca?
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="col-12">
												<div class="input-group left">
													<input type="checkbox" name="IsActive" class="form-control"
														value="<?php echo $isActive; ?>" <?php if ($isActive) {
																echo "checked";
															} ?>
														onclick="if(this.checked){this.value='1';}else{this.value='0';}"> Ativo?
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer bg-light">
										<button type="button" name="back" class="btn btn-primary"
											onclick="history.go(-1)">Voltar</button>
										<?php if ($update) { ?>
											<button type="submit" name="update" class="btn btn-primary">Alterar</button>
										<?php } else { ?>
											<button type="submit" name="save" class="btn btn-primary">Incluir</button>
										<?php } ?>
										<button type="submit" name="delete" class="btn btn-secondary">Excluir</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
	<?php include('func/footer.php') ?>
	<script>
		var pageTypeList = document.getElementById('PageTypeID');
		var originalPageTypeList = pageTypeList.getElementsByTagName("option");

		function pageTypeGet(application) {
			if (application != null && application != undefined) {
				for (var i = 0; i < originalPageTypeList.length; i++) {
					var option = originalPageTypeList[i];
					var dataId = option.getAttribute("data-id");

					if (dataId.indexOf(application.value) > -1) {
						option.style.display = "";
					} else {
						option.style.display = "none";
					}
				}
			}
		}
	</script>
</body>

</html>