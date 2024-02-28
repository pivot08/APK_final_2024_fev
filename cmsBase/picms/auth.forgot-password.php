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
	<!-- ======================= DRIP ICONS ===================================-->
	<link rel="stylesheet" href="../picms/assets/css/icons/dripicons.min.css">
	<!-- ======================= MATERIAL DESIGN ICONIC FONTS =================-->
	<link rel="stylesheet" href="../picms/assets/css/icons/material-design-iconic-font.min.css">
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
	<div class="container">
		<form method="post" class="sign-in-form" action="auth.sign-in.php">
			<div class="card">
				<div class="card-body">
					<a href="index.html" class="brand text-center d-block m-b-20">
						<img src="../img/logo_plataforma_horizontal.png" width="230" alt="<?php echo $siteName; ?>" />
					</a>
					<h5 class="sign-in-heading text-center">Esqueceu a sua senha?</h5>
					<p class="text-center text-muted">Digite o seu email para resetar a sua senha</p>
					<div class="form-group">
						<label for="inputEmail" class="sr-only">Email</label>
						<input type="email" name="userEmail" class="form-control" placeholder="Email" required="">
					</div>
					<button class="btn btn-primary btn-rounded btn-floating btn-lg btn-block" type="submit" name="password_btn">Resetar Senha</button>
				</div>
			</div>
		</form>
	</div>

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
	<!-- ================== GLOBAL APP SCRIPTS ==================-->
	<script src="../picms/assets/js/global/app.js"></script>

</body>

</html>
