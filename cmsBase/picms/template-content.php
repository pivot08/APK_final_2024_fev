<?php include('func/function.php'); ?>
<?php if (!isLoggedIn()) {
	header('location: auth.sign-in.php');
} ?>
<?php
if (isset($_GET['pageTypeID'])) {
	$pageTypeID = $_GET['pageTypeID'];
}

if (isset($_POST['save']) || isset($_POST['update']) || isset($_POST['copy'])) {
	$templateContentID = $_POST['TemplateContentID'];
	$applicationID = isset($_POST['ApplicationID']) ? $_POST['ApplicationID'] : '';
	$templateID = isset($_POST['TemplateID']) ? $_POST['TemplateID'] : '';
	$buttonSizeID = isset($_POST['ButtonSizeID']) ? $_POST['ButtonSizeID'] : '';
	$buttonPositionID = isset($_POST['ButtonPositionID']) ? $_POST['ButtonPositionID'] : '';
	$contentOrientationID = isset($_POST['ContentOrientationID']) ? $_POST['ContentOrientationID'] : '';
	$templateChildID = isset($_POST['TemplateChildID']) ? $_POST['TemplateChildID'] : '';
	$templateContentChildID = isset($_POST['TemplateContentChildID']) ? $_POST['TemplateContentChildID'] : '';
	$templateContent = isset($_POST['TemplateContent']) ? $_POST['TemplateContent'] : '';
	$title = isset($_POST['Title']) ? $_POST['Title'] : '';
	$subTitle = isset($_POST['SubTitle']) ? $_POST['SubTitle'] : '';
	$content = isset($_POST['Content']) ? $_POST['Content'] : '';
	$footnote = isset($_POST['Footnote']) ? $_POST['Footnote'] : '';
	$buttonOrder = isset($_POST['ButtonOrder']) ? $_POST['ButtonOrder'] : '';
	$media = isset($_POST['media']) ? $_POST['media'] : '';
	$coverImage = isset($_POST['coverImage']) ? $_POST['coverImage'] : '';
	$positionTop = isset($_POST['PositionTop']) ? $_POST['PositionTop'] : '';
	$positionLeft = isset($_POST['PositionLeft']) ? $_POST['PositionLeft'] : '';
	$textTitleColor = isset($_POST['TextTitleColor']) ? $_POST['TextTitleColor'] : '';
	$textColor = isset($_POST['TextColor']) ? $_POST['TextColor'] : '';
	$style = isset($_POST['Style']) ? $_POST['Style'] : '';

	if (isset($_POST['IsWhiteTitle'])) {
		$isWhiteTitle = $_POST['IsWhiteTitle'];
	} else {
		$isWhiteTitle = 0;
	}

	if (isset($_POST['IsTextRight'])) {
		$isTextRight = $_POST['IsTextRight'];
	} else {
		$isTextRight = 0;
	}

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
	$targetPath = dirname(__FILE__) . $ds . '..'. $ds . $storeFolder . $ds;

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
					'Media' => array('jpg', 'jpeg', 'png', 'mp4'),
					'CoverImage' => array('jpg', 'jpeg', 'png')
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
					} else if ($index == 'CoverImage') {
						$coverImage = $fileName;
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

if (isset($_POST['delete'])) {
	$templateContentID = $_POST['TemplateContentID'];
}

if (isset($_POST['save'])) {
	templateContentInsert($applicationID, $templateID, $buttonSizeID, $buttonPositionID, $contentOrientationID, $templateChildID, $templateContentChildID, $templateContent, $title, $subTitle, $content, $footnote, $buttonOrder, $media, $coverImage, $positionTop, $positionLeft, $textTitleColor, $textColor, $style, $isWhiteTitle, $isTextRight, $isActive);
}
if (isset($_POST['update'])) {
	templateContentUpdate($templateContentID, $applicationID, $templateID, $buttonSizeID, $buttonPositionID, $contentOrientationID, $templateChildID, $templateContentChildID, $templateContent, $title, $subTitle, $content, $footnote, $buttonOrder, $media, $coverImage, $positionTop, $positionLeft, $textTitleColor, $textColor, $style, $isWhiteTitle, $isTextRight, $isActive);
}
if (isset($_POST['copy'])) {
	$newTemplateContentID = templateContentCopy($templateContentID, $applicationID, $templateID, $buttonSizeID, $buttonPositionID, $contentOrientationID, $templateChildID, $templateContentChildID, $templateContent, $title, $subTitle, $content, $footnote, $buttonOrder, $media, $coverImage, $positionTop, $positionLeft, $textTitleColor, $textColor, $style, $isWhiteTitle, $isTextRight, $isActive);
}
if (isset($_POST['delete'])) {
	templateContentDelete($templateContentID);
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
	header('location: template-content-list.php?msg='. $msg .'&pageTypeID='. $pageTypeID);
}
if (isset($_POST['copy'])) {
	switch ($pageTypeID) {
		case 1:
		case 5:
			$pageGoTo = "template-content-home.php";
			break;
		case 0:
		case 2:
		case 6:
			$pageGoTo = "template-content-buttons.php";
			break;
		case 3:
			$pageGoTo = "template-content-text.php";
			break;
		case 7:
		case 12:
			$pageGoTo = "template-content-home-feature.php";
			break;
		case 4:
			$pageGoTo = "template-content-product-line.php";
			break;
		case 8:
			$pageGoTo = "template-content-step-by-step.php";
			break;
		case 14:
			$pageGoTo = "template-content-specification.php";
			break;
	}
	header('location: '. $pageGoTo .'?id='. $newTemplateContentID);
}
?>