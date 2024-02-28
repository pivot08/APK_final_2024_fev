<?php include('func/function.php') ?>
<?php if (!isLoggedIn()) {
	header('location: auth.sign-in.php');
} ?>
<?php
$userID = '';
$applicationID = '';
$userTypeID = 0;
$userName = '';
$userEmail = '';
$userPassword = '';
$update = false;

if (isset($_GET['id'])) {
	$id = $_GET['id'];

	$record = userGet($id);

	if (mysqli_num_rows($record) == 1) {
		$update = true;

		$n = mysqli_fetch_array($record);
		$userID = $n['UserID'];
		$userTypeID = $n['UserTypeID'];
		$userName = $n['UserName'];
		$userEmail = $n['Email'];
		$userPassword = $n['Password'];
	}
}

if (isset($_POST['save']) || isset($_POST['update'])) {
	$userID = $_POST['userID'];
	$userTypeID = $_POST['userTypeID'];
	$userName = $_POST['userName'];
	$userEmail = $_POST['userEmail'];
	$userPassword = $_POST['userPassword'];
	$applicationList = $_POST['ApplicationID'];
}

if (isset($_POST['save'])) {
	userInsert($userTypeID, $userName, $userEmail, $userPassword, $applicationList);
}
if (isset($_POST['update'])) {
	userUpdate($userID, $userTypeID, $userName, $userEmail, $userPassword, $applicationList);
}
if (isset($_POST['delete'])) {
	userDelete($userID);
}

if (isset($_POST['save']) || isset($_POST['update']) || isset($_POST['delete'])) {
	// if ($_SESSION['user']['ProfileID'] == 1) {
	$msg = '';
	if (isset($_POST['update'])) {
		$msg = 'Dados alterados com sucesso.';
	}
	if (isset($_POST['delete'])) {
		$msg = 'Registro excluído com sucesso.';
	}
	header('location: lista-usuarios.php?msg=' . $msg);
	// } else {
	// 	header('location: index.php');
	// }
}
?>
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
		<?php include('func/menu.php') ?>
		<div class="content-wrapper">
			<?php include('func/nav_bar.php') ?>

			<div class="content">
				<header class="page-header">
					<div class="d-flex align-items-center">
						<div class="mr-auto">
							<h1 class="separator">Usuário - <?php echo $userName; ?></h1>

						</div>

					</div>
				</header>
				<section class="page-content container-fluid">
					<div class="row">
						<div class="col-5">
							<div class="card">
								<h5 class="card-header">Dados do Usuário</h5>
								<form method="post">
									<input type="hidden" name="userID" value="<?php echo $userID; ?>">
									<div class="card-body">
										<div class="form-group">
											<label for="exampleInputNome">Nome</label>
											<input type="text" class="form-control" name="userName" aria-describedby="emailNome" value="<?php echo $userName; ?>" placeholder="Insira se nome">
										</div>
										<div class="form-group">
											<label for="input">Tipo do Usuário</label>
											<select class="form-control" name="userTypeID" required>
												<?php
												$result = userTypeList();
												while ($row = mysqli_fetch_array($result)) { ?>
													<option value="<?php echo $row['UserTypeID']; ?>" <?php if ($userTypeID == $row['UserTypeID']) {
																															echo 'selected';
																														} ?>><?php echo $row['UserType']; ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="form-group">
											<label for="exampleInputEmail1">Email</label>
											<input type="email" class="form-control" name="userEmail" aria-describedby="emailHelp1" autocomplete="email" value="<?php echo $userEmail; ?>" placeholder="Enter email">
										</div>
										<div class="form-group">
											<label for="exampleInputPassword1">Senha</label>
											<input type="password" class="form-control" name="userPassword" autocomplete="current-password" placeholder="Password" value="<?php echo $userPassword; ?>">
										</div>
										<div class="form-group">
											<label for="exampleInputPassword1">Aplicativo</label>
											<select class="form-control" name="ApplicationID[]" required multiple size="8">
												<?php
												$result = applicationList();
												while ($row = mysqli_fetch_array($result)) { ?>
													<option value="<?php echo $row['ApplicationID']; ?>" <?php if (userApplicationByPersonID($userID, $row['ApplicationID']) == $row['ApplicationID']) { echo 'selected'; } ?>><?php echo $row['Application']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="card-footer bg-light">
										<?php if ($update) { ?>
											<button type="submit" name="update" class="btn btn-primary">Alterar</button>
										<?php } else { ?>
											<button type="submit" name="save" class="btn btn-primary">Incluir</button>
										<?php } ?>
										<?php if ($_SESSION['user']['UserTypeID'] == 1) { ?>
											<button type="submit" name="delete" class="btn btn-secondary">Excluir</button>
										<?php } ?>
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
</body>

</html>