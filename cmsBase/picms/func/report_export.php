<?php include ('function.php') ?>
<?php
$result = navigationControlList();

if ($result->num_rows > 0) {
   // header("Content-Type: application/vnd.ms-excel");
   // header("Content-Disposition: attachment; filename=exported_data.xls");
   // header("Pragma: no-cache");
   // header("Expires: 0");

   $excelContent = chr(0xEF) . chr(0xBB) . chr(0xBF); // UTF-8 BOM
    $excelContent .= "Data;Acao;Aplicativo;Tipo;Template;Conteudo;Versao;Loja;Em Producao?;Dispositivo;Modelo\n";

   while ($row = $result->fetch_assoc()) {
      $excelContent .= $row["ActionDate"] . ";" . removeAccents($row["Action"]) . ";" . removeAccents($row["Application"]) . ";" . removeAccents($row["PageType"]) . ";"  . removeAccents($row["Template"]) . ";" . removeAccents($row["TemplateContent"]) . ";" . $row["TabletVersion"] . ";"  . $row["StoreCode"] .' - '. $row["StoreName"] . ";" . ($row['IsProduction'] == 1 ? 'Sim' : 'Não') . ";" . $row["DeviceID"] . ";" . $row["DeviceModel"] . "\n";
   }
} else {
   echo "0 results";
}

$file = fopen("report.xls", "w");
fwrite($file, $excelContent);
fclose($file);

function removeAccents($str) {
   return transliterator_transliterate('Any-Latin; Latin-ASCII', $str);
}
?>