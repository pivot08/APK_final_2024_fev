<?php include ('function.php') ?>
<?php
$result = navigationControlList();

if ($result->num_rows > 0) {
   // header("Content-Type: application/vnd.ms-excel");
   // header("Content-Disposition: attachment; filename=exported_data.xls");
   // header("Pragma: no-cache");
   // header("Expires: 0");

   $excelContent = chr(0xEF) . chr(0xBB) . chr(0xBF); // UTF-8 BOM
    $excelContent .= "Data\tAção\tAplicativo\tTemplate\tConteúdo\tVersão\tEm Produção?\tDispositivo\tModelo\n";

   while ($row = $result->fetch_assoc()) {
      $excelContent .= $row["ActionDate"] . "\t" . $row["Action"] . "\t" . $row["Application"] . "\t" . $row["Template"] . "\t" . $row["TemplateContent"] . "\t" . $row["TabletVersion"] . "\t" . ($row['IsProduction'] == 1 ? 'Sim' : 'Não') . "\t" . $row["DeviceID"] . "\t" . $row["DeviceModel"] . "\n";
   }
} else {
   echo "0 results";
}

$file = fopen("report.xls", "w");
fwrite($file, $excelContent);
fclose($file);
?>