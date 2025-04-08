<?php
include ('config.php'); // اتصال به دیتابیس

$query = "SELECT id, name FROM dbo_add_doctors";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
  echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
?>
