<?php include('function.php') ?>
<?php
if (
   isset($_POST['version'])
   && isset($_POST['actionID'])
   && isset($_POST['templateID'])
   && isset($_POST['templateContentID'])
   && isset($_POST['actionDate'])
   && isset($_POST['deviceId'])
   && isset($_POST['deviceModel'])
   && isset($_POST['deviceManufacturer'])
) {
   insertNavigationControl($_POST['version'], $_POST['actionID'], $_POST['templateID'], $_POST['templateContentID'], $_POST['actionDate'], $_POST['deviceId'], $_POST['deviceModel'], $_POST['deviceManufacturer']);
}

if (
   isset($_GET['version'])
   && isset($_GET['actionID'])
   && isset($_GET['templateID'])
   && isset($_GET['templateContentID'])
   && isset($_GET['actionDate'])
   && isset($_GET['deviceId'])
   && isset($_GET['deviceModel'])
   && isset($_GET['deviceManufacturer'])
) {
   insertNavigationControl($_GET['version'], $_GET['actionID'], $_GET['templateID'], $_GET['templateContentID'], $_GET['actionDate'], $_GET['deviceId'], $_GET['deviceModel'], $_GET['deviceManufacturer']);
}
?>