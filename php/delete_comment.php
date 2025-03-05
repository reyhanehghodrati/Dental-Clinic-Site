<?php
include('config.php');
$sql = mysqli_query($conn,"DELETE FROM dbo_user_comments WHERE id = '".$_GET['id']."'");

header("Location:admin_dashboard.php ");
exit();
?>
