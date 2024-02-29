<?php include('func/function.php'); ?>
<?php if (!isLoggedIn()) {
	header('location: auth.sign-in.php');
} ?>
<?php
$operatorPlanID = '';
$applicationID = '';
$operatorPlan = '';
$buttonContent = '';
$footNote = '';
$planOrder = '';
$isActive = '1';
$update = false;

if (isset($_GET['pageTypeID'])) {
	$pageTypeID = $_GET['pageTypeID'];
}

if (isset($_GET['id'])) {
	$id = $_GET['id'];

	$record = operatorPlanGet($id);
	if (mysqli_num_rows($record) == 1) {
		$update = true;

		$n = mysqli_fetch_array($record);
		$operatorPlanID = $n['OperatorPlanID'];
		$applicationID = $n['ApplicationID'];
		$operatorPlan = $n['OperatorPlan'];
		$buttonContent = $n['ButtonContent'];
		$footNote = $n['FootNote'];
		$planOrder = $n['PlanOrder'];
		$isActive = $n['IsActive'];
	}
}

if (isset($_POST['save']) || isset($_POST['update'])) {
	$applicationID = $_POST['ApplicationID'];
	$operatorPlan = $_POST['OperatorPlan'];
	$buttonContent = $_POST['ButtonContent'];
	$footNote = $_POST['FootNote'];
	$planOrder = $_POST['PlanOrder'];

	if (isset($_POST['IsActive'])) {
		$isActive = $_POST['IsActive'];
	} else {
		$isActive = 0;
	}
}

if (isset($_POST['save'])) {
	operatorPlanInsert($applicationID, $operatorPlan, $buttonContent, $footNote, $planOrder, $isActive);
}
if (isset($_POST['update'])) {
	operatorPlanUpdate($operatorPlanID, $applicationID, $operatorPlan, $buttonContent, $footNote, $planOrder, $isActive);
}
if (isset($_POST['delete'])) {
	operatorPlanDelete($operatorPlanID);
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
	header('location: operator-plan-list.php?msg=' . $msg);
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
							<h1 class="separator">Operadoras - Planos</h1>
						</div>
					</div>
				</header>
				<section class="page-content container-fluid">
					<div class="row">
						<div class="col-8">
							<div class="card">
								<form method="post">
									<h5 class="card-header">Operadoras - Planos</h5>
									
									<input type="hidden" name="OperatorPlanID" value="<?php echo $operatorPlanID; ?>">
									<div class="card-body">
										<div class="form-group">
											<label for="input">Nome *</label>
											<input type="text" name="OperatorPlan" class="form-control" value="<?php echo $operatorPlan; ?>" required>
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
											<label for="input">Texto do Botão</label>
											<input type="text" name="ButtonContent" class="form-control" value="<?php echo $buttonContent; ?>">
										</div>
										<div class="form-group">
											<label for="input">Observações de rodapé exíbido no conteúdo</label>
											<textarea name="FootNote" class="form-control" row="5"><?php echo $footNote; ?></textarea>
										</div>
										<div class="form-group">
											<label for="input">Order de Exibição *</label>
											<input type="number" name="PlanOrder" class="form-control" value="<?php echo $planOrder; ?>" required>
										</div>
										<div class="form-group">
											<div class="col-12">
												<div class="input-group left">
													<input type="checkbox" name="IsActive" class="form-control" value="<?php echo $isActive; ?>" <?php if ($isActive) { echo "checked"; } ?> onclick="if(this.checked){this.value='1';}else{this.value='0';}"> Ativo?
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
		var pageTypeList = document.getElementById('TemplateID');
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