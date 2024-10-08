<?php include ('function.php') ?>
<?php
// Função que remove acentos
function removeAccents($str) {
   return transliterator_transliterate('Any-Latin; Latin-ASCII', $str);
}

// Abrir arquivo para escrita
$file = fopen("report.xls", "w");

// Escrever o cabeçalho do arquivo Excel (com BOM para UTF-8)
fwrite($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
fwrite($file, "Data;Acao;Aplicativo;Tipo;Template;Conteudo;Versao;Loja;Em Producao?;Dispositivo;Modelo\n");

// Variáveis de controle de paginação
$offset = 0;
$limit = 10000; // Define quantos registros buscar por vez

// Loop para buscar os dados paginados
do {
   // Consulta paginada ao banco de dados
   $result = navigationControlList($offset, $limit); // Adaptar a função para aceitar offset e limit

   // Verificar se há resultados
   if ($result->num_rows > 0) {
       // Processar cada linha de resultado
       while ($row = $result->fetch_assoc()) {
           // Montar a linha para o arquivo Excel
           $line = $row["ActionDate"] . ";" . removeAccents($row["Action"]) . ";" . removeAccents($row["Application"]) . ";" . removeAccents($row["PageType"]) . ";" 
                   . removeAccents($row["Template"]) . ";" . removeAccents($row["TemplateContent"]) . ";" . $row["TabletVersion"] . ";" 
                   . $row["StoreCode"] . ' - ' . $row["StoreName"] . ";" 
                   . ($row['IsProduction'] == 1 ? 'Sim' : 'Não') . ";" . $row["DeviceID"] . ";" . $row["DeviceModel"] . "\n";
           
           // Escrever a linha no arquivo diretamente
           fwrite($file, $line);
       }

       // Incrementa o offset para buscar a próxima página de resultados
       $offset += $limit;
   }
} while ($result->num_rows > 0); // Continua enquanto houver resultados

// Fechar o arquivo após completar a escrita
fclose($file);

echo "Arquivo Excel gerado com sucesso!";
?>