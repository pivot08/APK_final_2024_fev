<?php include('func/function.php'); ?>
<?php if (!isLoggedIn()) {
	header('location: auth.sign-in.php');
} ?>
<?php
$operatorValueID = '';
$applicationID = '';
$operatorUserID = '';
$templateID = '';
$operatorPlanID = '';
$deviceID = '';
$planDetail = '';
$deviceValue = '';
$planMonthValue = '';
$installments = '';
$media = '';
$observation = '';
$isActive = '1';
$update = false;

if (isset($_GET['pageTypeID'])) {
	$pageTypeID = $_GET['pageTypeID'];
}

if (isset($_GET['id'])) {
	$id = $_GET['id'];

	$record = operatorValueGet($id);
	if (mysqli_num_rows($record) == 1) {
		$update = true;

		$n = mysqli_fetch_array($record);
		$operatorValueID = $n['OperatorValueID'];
		$applicationID = $n['ApplicationID'];
		$operatorUserID = $n['OperatorUserID'];
		$templateID = $n['TemplateID'];
		$operatorPlanID = $n['OperatorPlanID'];
		$deviceID = $n['DeviceID'];
		$planDetail = $n['PlanDetail'];
		$deviceValue = $n['DeviceValue'];
		$planMonthValue = $n['PlanMonthValue'];
		$installments = $n['Installments'];
		$media = $n['Media'];
		$observation = $n['Observation'];
		$isActive = $n['IsActive'];
	}
}

if (isset($_POST['save']) || isset($_POST['update'])) {
	$applicationID = $_POST['ApplicationID'];
	$operatorUserID = $_POST['OperatorUserID'];
	$templateID = $_POST['TemplateID'];
	$operatorPlanID = $_POST['OperatorPlanID'];
	$deviceID = $_POST['DeviceID'];
	$planDetail = $_POST['PlanDetail'];
	$deviceValue = $_POST['DeviceValue'];
	$planMonthValue = $_POST['PlanMonthValue'];
	$installments = $_POST['Installments'];
	$media = isset($_POST['media']) ? $_POST['media'] : '';
	$observation = $_POST['Observation'];

	if (isset($_POST['IsActive'])) {
		$isActive = $_POST['IsActive'];
	} else {
		$isActive = 0;
	}

	//processa os uploads
	$field = 'xfiles';
	$errors = array();
	$status = array();
	$maxfs = pow(10240, 2) * 5;
	$ds = DIRECTORY_SEPARATOR;
	$storeFolder = 'content';
	$targetPath = dirname(__FILE__) . $ds . '..' . $ds . $storeFolder . $ds;

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES[$field])) {
		$obj = $_FILES[$field];

		foreach ($obj['name'] as $index => $void) {
			$name = $obj['name'][$index];
			$tmp = $obj['tmp_name'][$index];
			$error = $obj['error'][$index];
			$type = $obj['type'][$index];
			$size = $obj['size'][$index];
			$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

			$allowed = (object) array(
				'Media' => array('jpg', 'jpeg', 'png')
			);

			// if ($error !== UPLOAD_ERR_OK)
			//      $errors[] = sprintf('An error [code:%d] occurred with file %s', $error, $name);
			// if (!in_array($ext, $allowed->$index))
			//     $errors[] = sprintf('Incorrect file extension %s for %s', $ext, $name);
			if ($size > $maxfs)
				$errors[] = sprintf('The file %s is too large @%d', $name, $size);

			if (empty($errors) && $size > 0) {
				$fileName = time() . '-' . $name;
				move_uploaded_file($tmp, $targetPath . $fileName);

				if ($index == 'Media') {
					$media = $fileName;
				}
			}
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($errors)) {
			echo '<h1>Error</h1>';
			foreach ($errors as $error)
				printf('<div>%s</div>', $error);
		}
	}
}

if (isset($_POST['save'])) {
	operatorValueInsert($applicationID, $operatorUserID, $templateID, $operatorPlanID, $deviceID, $planDetail, $deviceValue, $planMonthValue, $installments, $media, $observation, $isActive);
}
if (isset($_POST['update'])) {
	operatorValueUpdate($operatorValueID, $applicationID, $operatorUserID, $templateID, $operatorPlanID, $deviceID, $planDetail, $deviceValue, $planMonthValue, $installments, $media, $observation, $isActive);
}
if (isset($_POST['delete'])) {
	operatorValueDelete($operatorValueID);
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
	header('location: operator-value-list.php?msg=' . $msg);
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
							<h1 class="separator">Operadoras - Valores</h1>
						</div>
					</div>
				</header>
				<section class="page-content container-fluid">
					<div class="row">
						<div class="col-8">
							<div class="card">
								<form method="post" enctype="multipart/form-data">
									<h5 class="card-header">Operadoras - Valores</h5>

									<input type="hidden" name="OperatorValueID" value="<?php echo $operatorValueID; ?>">
									<input type="hidden" name="media" value="<?php echo $media; ?>">
									<div class="card-body">
										<div class="form-group">
											<label for="input">Nome do Plano *</label>
											<input type="text" name="PlanDetail" class="form-control"
												value="<?php echo $planDetail; ?>" required>
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
											<label for="input">Usuário *</label>
											<select class="form-control" name="OperatorUserID" required>
												<option value="">Selecione</option>
												<?php
												$result = operatorUserByUserList($_SESSION['user']['UserID']);
												while ($row = mysqli_fetch_array($result)) { ?>
													<option value="<?php echo $row['OperatorUserID']; ?>" <?php if ($row['OperatorUserID'] == $operatorUserID) {
															echo 'selected';
														} ?>>
														<?php echo $row['OperatorUser']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
										<div class="form-group">
											<label for="input">Template *</label>
											<select class="form-control" id="TemplateID" name="TemplateID" required>
												<option value="" data-id="0">Selecione</option>
												<?php
												$result = templateByUserList($_SESSION['user']['UserID'], 1, $applicationID);
												while ($row = mysqli_fetch_array($result)) { ?>
													<option value="<?php echo $row['TemplateID']; ?>" <?php if ($row['TemplateID'] == $templateID) {
															echo 'selected';
														} ?>
														data-id="<?php echo $row['ApplicationID']; ?>">
														<?php echo $row['Template']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
										<div class="form-group">
											<label for="input">Categoria do Plano *</label>
											<select class="form-control" name="OperatorPlanID" required>
												<option value="">Selecione</option>
												<?php
												$result = operatorPlanByUserList($_SESSION['user']['UserID']);
												while ($row = mysqli_fetch_array($result)) { ?>
													<option value="<?php echo $row['OperatorPlanID']; ?>" <?php if ($row['OperatorPlanID'] == $operatorPlanID) {
															echo 'selected';
														} ?>>
														<?php echo $row['Application']; ?> -
														<?php echo $row['OperatorPlan']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
										<div class="form-group">
											<label for="input">Aparelho *</label>
											<select class="form-control" name="DeviceID" required>
												<option value="">Selecione</option>
												<?php
												$result = deviceList();
												while ($row = mysqli_fetch_array($result)) { ?>
													<option value="<?php echo $row['DeviceID']; ?>" <?php if ($row['DeviceID'] == $deviceID) {
															echo 'selected';
														} ?>>
														<?php echo $row['Device']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
										<div class="form-group">
											<label for="input">Valor do Aparelho *</label>
											<input type="number" name="DeviceValue" class="form-control"
												value="<?php echo $deviceValue; ?>" required step='0.01' placeholder='0.00'>
										</div>
										<div class="form-group">
											<label for="input">Valor Mensal do Plano</label>
											<input type="number" name="PlanMonthValue" class="form-control"
												value="<?php echo $planMonthValue; ?>" step='0.01' placeholder='0.00'>
										</div>
										<div class="form-group">
											<label for="input">Parcelas</label>
											<input type="number" name="Installments" class="form-control"
												value="<?php echo $installments; ?>" placeholder='0'>
										</div>
										<div class="form-group">
											<label for="input">Observações</label>
											<input type="text" name="Observation" class="form-control"
												value="<?php echo $observation; ?>">
										</div>
										<div class="form-group">
											<label for="input">Mídia</label><br>
											<input type="file" id="file-input-photo" name="xfiles[Media]" accept=".jpg, .jpeg, .png" <?php echo $media != '' ? '' : 'required'; ?>>
											<div class="file-name-photo">
												<?php echo $media != '' ? $media : ''; ?>
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