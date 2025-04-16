<?php
include('config.php');
$sql = mysqli_query($conn,"DELETE FROM user_feedback WHERE id = '".$_GET['id']."'");

header("Location:admin_dashboard.php ");
exit();
?>
