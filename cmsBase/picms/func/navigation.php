<?php include('function.php') ?>
<?php
if (
   isset($_POST['version'])
   && isset($_POST['actionID'])
   && isset($_POST['actionDate'])
   && isset($_POST['deviceId'])
   && isset($_POST['deviceModel'])
   && isset($_POST['deviceManufacturer'])
   && (isset($_POST['templateID'])
      || isset($_POST['templateContentID']))
) {
   $input = $_POST['actionID'];
   if (strpos($input, '|') !== false) {
      list($actionID, $storeID) = explode('|', $input);

      if (is_numeric($actionID) && is_numeric($storeID)) {
         $actionID = intval($actionID);
         $storeID = intval($storeID);
      } else {
         $actionID = '1';
         $storeID = '';
      }
   } else {
      if (is_numeric($input)) {
         $actionID = intval($input);
         $storeID = '';
      } else {
         $actionID = '1';
      }
   }

   insertNavigationControl($_POST['version'], $actionID, $_POST['templateID'], $_POST['templateContentID'], $_POST['actionDate'], $storeID, $_POST['deviceId'], $_POST['deviceModel'], $_POST['deviceManufacturer']);
}

if (
   isset($_GET['version'])
   && isset($_GET['actionID'])
   && isset($_GET['actionDate'])
   && isset($_GET['deviceId'])
   && isset($_GET['deviceModel'])
   && isset($_GET['deviceManufacturer'])
   && (isset($_GET['templateID'])
      || isset($_GET['templateContentID']))
) {
   $input = $_GET['actionID'];
   if (strpos($input, '|') !== false) {
      list($actionID, $storeID) = explode('|', $input);

      if (is_numeric($actionID) && is_numeric($storeID)) {
         $actionID = intval($actionID);
         $storeID = intval($storeID);
      } else {
         $actionID = '1';
         $storeID = '';
      }
   } else {
      if (is_numeric($input)) {
         $actionID = intval($input);
         $storeID = '';
      } else {
         $actionID = '1';
      }
   }
   
   insertNavigationControl($_GET['version'], $actionID, $_GET['templateID'], $_GET['templateContentID'], $_GET['actionDate'], $storeID, $_GET['deviceId'], $_GET['deviceModel'], $_GET['deviceManufacturer']);
}
?>