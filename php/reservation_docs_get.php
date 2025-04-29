<?php
include ('config.php'); // اتصال به دیتابیس

$query = "SELECT id, name FROM reservation_doctor_profiles";
$result = mysqli_query($conn, $query);

var_dump($result);


while ($row = mysqli_fetch_assoc($result)) {

  $selected = $old_values['doctor_id'] == $row['id'] ? 'selected' :  '';

  echo "<option value='{$row['id']}' $selected >{$row['name']}</option>";
}

?>
