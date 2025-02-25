<?php include('func/function.php') ?>
<?php if (!isLoggedIn()) {
	header('location: auth.sign-in.php');
}

// Definir diretório onde os relatórios estão armazenados
$reportDir = __DIR__ . "/func/report";

// Criar a pasta "report" se não existir
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0777, true);
}

// Função para listar os arquivos de relatório na pasta
function getReportFiles($directory) {
    $files = glob($directory . "/report-location_*.xls"); // Buscar arquivos no formato report_[numero_semana_ano].xls
    $reports = [];

    foreach ($files as $file) {
        if (preg_match('/report-location_(\d+)_(\d+)\.xls$/', basename($file), $matches)) {
            $weekNumber = $matches[1];
            $year = $matches[2];

            // Calcular a data de início e fim da semana
            $startDate = new DateTime();
            $startDate->setISODate($year, $weekNumber, 1); // Segunda-feira da semana
            $endDate = clone $startDate;
            $endDate->modify('+6 days'); // Domingo da semana

            $reports[] = [
                'week' => $weekNumber,
                'year' => $year,
                'startDate' => $startDate->format('d/m/Y'),
                'endDate' => $endDate->format('d/m/Y'),
                'file' => basename($file)
            ];
        }
    }

    // Ordenar por ano e semana (do mais recente para o mais antigo)
    usort($reports, function ($a, $b) {
        return ($b['year'] . $b['week']) <=> ($a['year'] . $a['week']);
    });

    return $reports;
}

// Obter a lista de relatórios
$reports = getReportFiles($reportDir);
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
							<h1 class="separator">Arquivos de Relatórios</h1>
						</div>
					</div>
				</header>
				<section class="page-content container-fluid">
					<div class="row">
						<div class="col-12">
							<div class="card">
								<h5 class="card-header">Registro de Navegação - Localização</h5>
								<div class="card-body">
									<table id="bs6-table" class="table table-striped table-bordered" style="width:100%">
										<thead>
											<tr>
												<th>Número da Semana</th>
												<th>Ano</th>
												<th>Data de Início</th>
												<th>Data de Fim</th>
												<th>Download</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($reports)): ?>
												<?php foreach ($reports as $report): ?>
													<tr>
														<td><?= $report['week'] ?></td>
														<td><?= $report['year'] ?></td>
														<td><?= $report['startDate'] ?></td>
														<td><?= $report['endDate'] ?></td>
														<td><a href="func/report/<?= $report['file'] ?>" download>Baixar</a></td>
													</tr>
												<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td colspan="5">Nenhum relatório disponível.</td>
												</tr>
											<?php endif; ?>
										</tbody>
										<tfoot>
											<tr>
												<th>Número da Semana</th>
												<th>Ano</th>
												<th>Data de Início</th>
												<th>Data de Fim</th>
												<th>Download</th>
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