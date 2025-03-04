<?php
include('config.php');
$sql = mysqli_query($conn,"DELETE FROM site_settings WHERE id = '".$_GET['id']."'");
    unlink($_GET['background_image']);

header("Location:admin_dashboard.php ".$_SERVER['']);
exit();
?>
