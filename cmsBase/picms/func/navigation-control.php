<?php
$host = 'mysql833.umbler.com:41890';
$db = 'dpopinterativo';
$user = 'dpopinterativo';
$pass = ',u-(Cw8Eh8';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Read parameters sent by DataTables
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];

// Total records
$totalRecordsQuery = $pdo->query("SELECT COUNT(*) FROM NavigationControl");
$totalRecords = $totalRecordsQuery->fetchColumn();

// Base SQL query
$baseQuery = "
    SELECT
        nvc.NavigationControlID,
        act.ActionID,
        act.Action,
        tmp.TemplateID,
        tmp.Template,
        pgt.PageTypeID,
        pgt.PageType,
        tpc.TemplateContentID,
        tpc.TemplateContent,
        apk.ApplicationID,
        apk.Application,
        DATE_FORMAT(nvc.ActionDate, '%d/%m/%Y %H:%i:%s') AS ActionDate,
        nvc.TabletVersion,
        str.StoreID,
        str.StoreCode,
        str.StoreName,
        str.StoreCode +' - '+ str.StoreName AS Store,
        nvc.DeviceID,
        nvc.DeviceModel,
        nvc.DeviceManufacturer,
        tbv.IsProduction
    FROM
        NavigationControl nvc
        INNER JOIN Action act ON nvc.ActionID = act.ActionID
        INNER JOIN Template tmp ON nvc.TemplateID = tmp.TemplateID
        INNER JOIN PageType pgt ON tmp.PageTypeID = pgt.PageTypeID
        INNER JOIN Application apk ON tmp.ApplicationID = apk.ApplicationID
        INNER JOIN TabletVersion tbv ON nvc.TabletVersion = tbv.TabletVersion
        LEFT JOIN TemplateContent tpc ON nvc.TemplateContentID = tpc.TemplateContentID
        LEFT JOIN Store str ON nvc.StoreID = str.StoreID";

// Filtered records
if ($search) {
    $baseQuery .= " WHERE
        act.Action LIKE :search OR
        tmp.Template LIKE :search OR
        pgt.PageType LIKE :search OR
        apk.Application LIKE :search OR
        tpc.TemplateContent LIKE :search OR
        str.StoreCode LIKE :search OR
        str.StoreName LIKE :search OR
        nvc.DeviceID LIKE :search OR
        nvc.DeviceModel LIKE :search OR
        nvc.DeviceManufacturer LIKE :search";
    $filteredRecordsQuery = $pdo->prepare("SELECT COUNT(*) FROM ($baseQuery) AS filtered");
    $filteredRecordsQuery->execute(['search' => "%$search%"]);
    $filteredRecords = $filteredRecordsQuery->fetchColumn();
} else {
    $filteredRecords = $totalRecords;
}

$baseQuery .= " ORDER BY nvc.ActionDate DESC LIMIT :start, :length";
$dataQuery = $pdo->prepare($baseQuery);

if ($search) {
    $dataQuery->bindParam(':search', $search, PDO::PARAM_STR);
}
$dataQuery->bindParam(':start', $start, PDO::PARAM_INT);
$dataQuery->bindParam(':length', $length, PDO::PARAM_INT);
$dataQuery->execute();

$data = $dataQuery->fetchAll();

foreach ($data as &$row) {
   $row['IsProduction'] = $row['IsProduction'] ? 'Sim' : 'Não';
}

// Response
$response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($filteredRecords),
    "data" => $data
];

echo json_encode($response);
?>
