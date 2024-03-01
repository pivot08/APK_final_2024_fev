<?php include('function.php') ?>
<?php
if (
   isset($_POST['version'])
   && isset($_POST['deviceId'])
   && isset($_POST['deviceModel'])
   && isset($_POST['deviceManufacturer'])
) {
   insertTabletVersionDetail($_POST['version'], $_POST['deviceId'], $_POST['deviceModel'], $_POST['deviceManufacturer']);
}

if (
   isset($_GET['version'])
   && isset($_GET['deviceId'])
   && isset($_GET['deviceModel'])
   && isset($_GET['deviceManufacturer'])
) {
   insertTabletVersionDetail($_GET['version'], $_GET['deviceId'], $_GET['deviceModel'], $_GET['deviceManufacturer']);
}
?>