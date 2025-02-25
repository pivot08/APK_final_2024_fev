<?php include ('function.php') ?>
<?php
// Função que remove acentos
function removeAccents($str) {
   return transliterator_transliterate('Any-Latin; Latin-ASCII', $str);
}

//Criar a pasta "report" se não existir
$reportDir = __DIR__ . "/report";
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0777, true);
}

// Obter a data do primeiro registro
$firstDate = getFirstRecordDateByStore();
if (!$firstDate) {
    die("Nenhum registro encontrado na tabela.");
}

// Converter para o formato de data
$firstDate = new DateTime($firstDate);
$firstDate->modify('monday this week'); // Ajustar para a primeira segunda-feira anterior ou a mesma

// Obter a data atual
$today = new DateTime();
$today->modify('sunday this week'); // Ajustar para o último domingo da semana atual

// Loop por todas as semanas até a atual
while ($firstDate <= $today) {
    $startDate = $firstDate->format('Y-m-d 00:00:00');
    $endDate = $firstDate->modify('+6 days')->format('Y-m-d 23:59:59');

    // Criar nome do arquivo baseado no número da semana e ano
    $weekNumber = $firstDate->format('W');
    $year = $firstDate->format('Y');
    $filename = "$reportDir/report-store_{$weekNumber}_{$year}.xls";

    // Abrir arquivo para escrita
    $file = fopen($filename, "w");

    // Escrever cabeçalho no arquivo Excel
    fwrite($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
    fwrite($file, "Data;CNPJ;Cod.Loja;Loja;Acao;Aplicativo;Tipo;Template;Conteudo;Versao;Data Versao;Dispositivo;Modelo;Versao mais recente?\n");

    // Buscar registros da semana
    $result = getWeeklyRecordsByStore($startDate, $endDate);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Criar linha com os dados
            $line = $row["ActionDate"] . ";"
                . $row["CNPJ"] . ";" . $row["StoreCode"] . ";" . $row["StoreName"] . ";"
                . removeAccents($row["Action"]) . ";" . removeAccents($row["Application"]) . ";" . removeAccents($row["PageType"]) . ";"
                . removeAccents($row["Template"]) . ";" . removeAccents($row["TemplateContent"]) . ";" . $row["TabletVersion"] . ";"
                . $row["VersionDate"] . ";"
                . $row["DeviceID"] . ";" . $row["DeviceModel"] . ";" . ($row['IsProduction'] == 1 ? 'Sim' : 'Não') . "\n";

            // Escrever no arquivo
            fwrite($file, $line);
        }
    }

    // Fechar o arquivo
    fclose($file);

    echo "Arquivo gerado: $filename\n";

    // Avançar para a próxima semana
    $firstDate->modify('+1 day');
}
?>