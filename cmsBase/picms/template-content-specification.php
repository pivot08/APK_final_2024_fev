<?php include('func/function.php'); ?>
<?php if (!isLoggedIn()) {
	header('location: auth.sign-in.php');
} ?>
<?php
$templateContentID = '';
$applicationID = '';
$pageTypeID = '';
$templateID = '';
$buttonSizeID = '';
$contentOrientationID = '';
$templateChildID = '';
$templateContentChildID = '';
$templateContent = '';
$title = '';
$subTitle = '';
$content = '';
$footnote = '';
$buttonOrder = '';
$media = '';
$coverImage = '';
$positionTop = '';
$positionLeft = '';
$isActive = '1';
$update = false;

if (isset($_GET['pageTypeID'])) {
	$pageTypeID = $_GET['pageTypeID'];
}

if (isset($_GET['id'])) {
	$id = $_GET['id'];

	$record = templateContentGet($id);
	if (mysqli_num_rows($record) == 1) {
		$update = true;

		$n = mysqli_fetch_array($record);
		$templateContentID = $n['TemplateContentID'];
		$applicationID = $n['ApplicationID'];
		$templateID = $n['TemplateID'];
		$buttonSizeID = $n['ButtonSizeID'];
		$contentOrientationID = $n['ContentOrientationID'];
		$templateChildID = $n['TemplateChildID'];
		$templateContentChildID = $n['TemplateContentChildID'];
		$templateContent = $n['TemplateContent'];
		$title = $n['Title'];
		$subTitle = $n['SubTitle'];
		$content = $n['Content'];
		$footnote = $n['Footnote'];
		$buttonOrder = $n['ButtonOrder'];
		$media = $n['Media'];
		$coverImage = $n['CoverImage'];
		$positionTop = $n['PositionTop'];
		$positionLeft = $n['PositionLeft'];
		$isActive = $n['IsActive'];
	}
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
							<h1 class="separator">Cadastro de Conteúdo dos Templates</h1>
						</div>
					</div>
				</header>
				<section class="page-content container-fluid">
					<div class="row">
						<div class="col-8">
							<div class="card">
								<form method="post" action="template-content.php?pageTypeID=<?php echo $pageTypeID; ?>" enctype="multipart/form-data">
									<h5 class="card-header">Conteúdo do Template *</h5>
									<h2 class="card-header" style="font-size:10px">
										- **<strong>texto</strong>** : ** antes e depois de um texto deixam o mesmo negrito<br>
										- __<i>texto</i>__ : __ antes e depois de um texto deixam o mesmo itálico<br>
										- para incluir o ícone Galaxy IA <img src="assets/images/ico-inteligencia.png" width="16">,
										insira o texto [ICO_AI]
									</h2>

									<input type="hidden" name="TemplateContentID" value="<?php echo $templateContentID; ?>">
									<input type="hidden" name="media" value="<?php echo $media; ?>">
									<input type="hidden" name="coverImage" value="<?php echo $coverImage; ?>">
									<div class="card-body">
										<div class="form-group">
											<label for="input">Nome *</label>
											<input type="text" name="TemplateContent" class="form-control" value="<?php echo $templateContent; ?>" required>
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
											<label for="input">Template *</label><br>
											<span style="font-size:12px">obs.: ao deixar o template vazio, o conteúdo será exibido em todas as páginas</span>
											<select class="form-control" id="TemplateID" name="TemplateID" required>
												<option value="" data-id="0">Selecione</option>
												<?php
												$result = templateList(0, $applicationID);
												while ($row = mysqli_fetch_array($result)) { ?>
													<option value="<?php echo $row['TemplateID']; ?>" <?php if ($row['TemplateID'] == $templateID) { echo 'selected'; } ?> data-id="<?php echo $row['ApplicationID']; ?>"><?php echo $row['Template']; ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="form-group">
											<label for="input">Template Filho *</label>
											<select class="form-control" name="TemplateChildID">
												<option value="">Selecione</option>
												<?php
												$result = templateList();
												while ($row = mysqli_fetch_array($result)) { ?>
													<option value="<?php echo $row['TemplateID']; ?>" <?php if ($row['TemplateID'] == $templateChildID) { echo 'selected'; } ?>><?php echo $row['Application']; ?> - <?php echo $row['Template']; ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="form-group">
											<label for="input">Conteúdo Filho</label>
											<select class="form-control" name="TemplateContentChildID">
												<option value="">Selecione</option>
												<?php
												$result = templateContentList();
												while ($row = mysqli_fetch_array($result)) { ?>
													<option value="<?php echo $row['TemplateContentID']; ?>" <?php if ($row['TemplateContentID'] == $templateContentChildID) { echo 'selected'; } ?>><?php echo $row['Application']; ?> - <?php echo $row['Template']; ?> - <?php echo $row['TemplateContent']; ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="form-group">
											<label for="input">Mídia *</label><br>
											<input type="file" id="file-input-photo" name="xfiles[Media]" accept=".jpg, .jpeg, .png, .mp4" <?php echo $media != '' ? '' : 'required'; ?>>
											<div class="file-name-photo">
												<?php echo $media != '' ? $media : ''; ?>
											</div>
										</div>
										<div class="form-group">
											<label for="input">Imagem do Botão *</label><br>
											<input type="file" id="file-input-photo" name="xfiles[CoverImage]" accept=".jpg, .jpeg, .png, .mp4" <?php echo $coverImage != '' ? '' : 'required'; ?>>
											<div class="file-name-photo">
												<?php echo $coverImage != '' ? $coverImage : ''; ?>
											</div>
										</div>
										<div class="form-group">
											<label for="input">Título do botão</label>
											<input type="text" name="Title" class="form-control" value="<?php echo $title; ?>">
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
										<button type="submit" name="copy" class="btn btn-secondary">Copiar</button>
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