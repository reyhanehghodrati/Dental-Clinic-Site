<?php
session_start();
include('config.php');

$id = mysqli_real_escape_string($conn, $_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM slider_details WHERE id = '$id'");
$slider = mysqli_fetch_assoc($result);

function validate_input($input) {
    return preg_match('/^[a-zA-Z\s\x{200C}آ-ی۰-۹0-9؟.!]+$/u', $input);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $title = isset($_POST['home_title']) ? mysqli_real_escape_string($conn, $_POST['home_title']) : '';
    $subtitle = isset($_POST['home_subtitle']) ? mysqli_real_escape_string($conn, $_POST['home_subtitle']) : '';

    // بررسی اعتبار و طول رشته
    if (strlen($title) > 255 || strlen($subtitle) > 255) {
        $errors[] = "عنوان و زیرعنوان نباید بیشتر از 255 کاراکتر باشند.";
    } elseif (!validate_input($title) || !validate_input($subtitle)) {
        $errors[] = "عنوان و زیرعنوان فقط شامل حروف فارسی، انگلیسی، اعداد و علائم مجاز هستند.";
    }

    // مدیریت تصویر
    $background_image = $slider['background_image']; // مقدار قبلی را نگه می‌داریم
    $allowed_types = ['image/png', 'image/jpeg'];
    $max_size = 1 * 1024 * 1024; // 1MB
    $upload_dir = "../image/";

    if (isset($_FILES['background']) && $_FILES['background']['size'] > 0) {
        $file_type = $_FILES['background']['type'];
        $file_size = $_FILES['background']['size'];
        $file_name = basename($_FILES['background']['name']);
        $new_file_path = $upload_dir . $file_name;

        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "فقط فایل‌های PNG یا JPEG قابل قبول هستند.";
        } elseif ($file_size > $max_size) {
            $errors[] = "حجم فایل نباید بیشتر از 1 مگابایت باشد.";
        } elseif (!move_uploaded_file($_FILES['background']['tmp_name'], $new_file_path)) {
            $errors[] = "بارگذاری تصویر با خطا مواجه شد.";
        } else {
            $background_image = $new_file_path;
        }
    }
    $time = date("Y-m-d H:i:s");

    if (empty($errors)) {
        $update_sql = 'UPDATE slider_details SET title=?, subtitle=?, background_image=?, last_updated=? WHERE id=?';
        $state = mysqli_prepare($conn, $update_sql);

        mysqli_stmt_bind_param($state, "ssssi", $title, $subtitle, $background_image, $time, $id);

        if (mysqli_stmt_execute($state)) {
            $_SESSION['message'] = "<p style='color: #42e230;'>اسلاید بروز شد.</p>";
        } else {
            $_SESSION['message'] = "<p style='color: red;'>خطا در بروزرسانی اسلاید.</p>";
        }

        mysqli_stmt_close($state);

        header("Location: " . $_SERVER['PHP_SELF'] . "?id=$id");
        exit();
    } else {
        $_SESSION['message'] = "<p style='color: red;'>" . implode("<br>", $errors) . "</p>";
    }
}
?>

<html>
<head>
    <title>داشبورد ادمین</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <style>
        #img-tum {
            width: 60px;
            height: 40px;
            margin-right: -50px;
            margin-top: 20px;
        }
        .submit-btn {
            width: 100px;
            height: 30px;
            background-color: #007bff;
            border: none;
            margin-right: 20%;
            color: white;
        }
    </style>
</head>
<body dir="rtl">
<div class="table-container">
    <h3>تغییرات مربوط به استایل دهی</h3>
    <?= $_SESSION['message'] ?? '' ?>
    <form method="post" enctype="multipart/form-data">
        <label>عنوان:</label>
        <input type="text" name="home_title" value="<?= htmlspecialchars($slider['title'] ?? '') ?>">

        <label>زیرعنوان:</label>
        <input type="text" name="home_subtitle" value="<?= htmlspecialchars($slider['subtitle'] ?? '') ?>">

        <label>تصویر پس‌زمینه:</label>
        <input type="file" name="background">
        <img id="img-tum" src="<?= $slider['background_image'] ?>" width="100">
        <br><br>
        <input type="submit" name="submit" value="  ثبت " class="submit-btn">
    </form>
</div>
</body>
</html>
