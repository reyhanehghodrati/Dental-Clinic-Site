<?php
session_start();
include('config.php'); 
include('jdf.php'); 


if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

// کوئری برای دریافت اطلاعات هر جدول
$health_test_sql = "SELECT * FROM dental_health_responses"; // جدول مربوط به آزمون سلامت دندان
$consultation_sql = "SELECT * FROM consultation_requests_moshavereh"; // جدول مربوط به مشاوره آنلاین
$doctors = "SELECT * FROM dbo_add_doctors"; // جدول مربوط به نوبت‌دهی
$slider_settings = 'SELECT * FROM site_settings ORDER BY priority ASC';
$comments_sql='select * from dbo_user_comments';
$nobat='select * from dbo_schedule_nobat order by day_of_week';


$health_test_result = mysqli_query($conn, $health_test_sql);
$consultation_result = mysqli_query($conn, $consultation_sql);
$doctors_result = mysqli_query($conn, $doctors);
$slider_settings_result=mysqli_query($conn,$slider_settings);
$comments_result=mysqli_query($conn,$comments_sql);
$nobat_result=mysqli_query($conn,$nobat);

$sql = "SELECT * FROM site_settings LIMIT 1";
$result = mysqli_query($conn , $sql);
$slider = mysqli_fetch_assoc($result);

function validate_input($input) {
    return preg_match('/^[a-zA-Z\s\x{200C}آ-ی۰-۹0-9؟.!]+$/u', $input);
}

function miladi_to_shamsi($date) {
    // جدا کردن تاریخ و زمان
    list($date_only, $time) = explode(" ", $date); 
    
    // جدا کردن اجزای تاریخ میلادی
    list($year, $month, $day) = explode('-', $date_only);
    
    // جدا کردن اجزای ساعت
    list($hour, $minute, $second) = explode(':', $time);
    
    // تبدیل تاریخ و زمان به شمسی
    $shamsi_date = jdate('Y/m/d H:i:s', mktime((int)$hour, (int)$minute, (int)$second, (int)$month, (int)$day, (int)$year));
    
    return $shamsi_date;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ذخیره عنوان و زیرعنوان
    if (!empty($_POST['home_title']) || !empty($_POST['home_subtitle'])) {
        $title = mysqli_real_escape_string($conn, $_POST['home_title']);
        $subtitle = mysqli_real_escape_string($conn, $_POST['home_subtitle']);

        // بررسی طول رشته و اعتبار ورودی
        if ((strlen($title) > 255 || strlen($subtitle) > 255)) {
            $_SESSION['message'] = "<p style='color: red;'>عنوان و زیرعنوان نباید بیشتر از 255 کاراکتر باشند.</p>";
        } elseif (!validate_input($title) || !validate_input($subtitle)) {
            $_SESSION['message'] = "<p style='color: red;'>عنوان و زیرعنوان فقط شامل حروف فارسی، انگلیسی، اعداد و علائم مجاز هستند.</p>";
        } else {
            $slider[] = "title='$title'";
            $slider[] = "subtitle='$subtitle'";
        }
    }

    // مدیریت تصویر
    $allowed_types = ['image/png', 'image/jpeg'];
    $max_size = 1 * 1024 * 1024; // 1MB
    $upload_dir = "../image/";



    if (isset($_FILES['background']) && $_FILES['background']['size'] > 0) {


        $file_type = $_FILES['background']['type'];
        $file_size = $_FILES['background']['size'];
        $file_name = basename($_FILES['background']['name']);
        $new_file_path = $upload_dir . $file_name;

        if (!in_array($file_type, $allowed_types)) {
            $_SESSION['message'] = "<p style='color: red;'>فقط فایل‌های PNG یا JPEG قابل قبول هستند.</p>";
        } elseif ($file_size > $max_size) {
            $_SESSION['message'] = "<p style='color: red;'>حجم فایل نباید بیشتر از 1 مگابایت باشد.</p>";
        } elseif (move_uploaded_file($_FILES['background']['tmp_name'], $new_file_path)) {
            $slider[] = "background_image='$new_file_path'";
            $background_image = $new_file_path;
        } else {
            $_SESSION['message'] = "<p style='color: red;'>بارگذاری تصویر با خطا مواجه شد.</p>";
        }
    }

$max_priority_query = "SELECT MAX(priority) AS max_priority FROM site_settings";
$max_priority_result = mysqli_query($conn, $max_priority_query);
$max_priority_row = mysqli_fetch_assoc($max_priority_result);
$new_priority = $max_priority_row['max_priority'] + 1; // مقدار اولویت اسلاید جدیدی که قراره اضافه بشه

$insert_sql = "INSERT INTO site_settings (title, subtitle, background_image, priority, created_in) VALUES (?, ?, ?, ?, NOW())";
$state = mysqli_prepare($conn, $insert_sql);
mysqli_stmt_bind_param($state, "sssi", $title, $subtitle, $background_image, $new_priority);

if (mysqli_stmt_execute($state)) {
    $_SESSION['message'] = "<p style='color: #42e230;'>اسلاید ایجاد شد.</p>";
} else {
    $_SESSION['message'] = "<p style='color: red;'>خطا در ایجاد اسلاید.</p>";
}
mysqli_stmt_close($state);
header("Location: " . $_SERVER['PHP_SELF']);
exit();

}
?>



<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد ادمین</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <style>
        #img-tum{
            width: 60px;
            height: 40px;
            margin-right: -50px;
            margin-top: 20px;
        }
        .submit-btn{
            width: 100px;
            height: 30px;
            background-color: #007bff;
            border: none;
            margin-right: 20%;
            color: white;
        }
        a{
            text-decoration: none;
            color: #2d7fe3;
        }
    </style>
</head>

<body dir=rtl>
    <header>
        <h1>سلام، <?php echo $_SESSION['username']; ?> خوش آمدید</h1>
    </header>
    <section>
        <div class="table-container">
            <?php
            if (isset($_SESSION['message'])):
                ?>
                <p style="color: red"><?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    ?></p>
            <?php
            endif; ?>
        </div>
    <div class="table-container">
        <?php if (isset($message)) echo "<p>$message</p>"; ?>
        <h3>تغییرات مربوط به بنر سایت</h3>
        <form method="post"  enctype="multipart/form-data">
        <label>عنوان:</label>
        <input type="text" name="home_title">

        <label>زیرعنوان:</label>
        <input type="text" name="home_subtitle" >

        <label>تصویر پس‌زمینه:</label>
        <input type="file" name="background">
            <br><br>
            <input type="submit" name="submit" value="  ثبت " class="submit-btn">
        </form>
    </div>
    </section>
    <section>
        <div class="table-container">
            <table>
                <tr>
                    <th>id</th>
                    <th>عنوان</th>
                    <th>زیر عنوان</th>
                    <th>پس زمینه</th>
                    <th>تاریخ</th>
                    <th>عملیات</th>
                    <th>آخرین ویرایش</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($slider_settings_result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['subtitle']; ?></td>
                        <td><img id="img-tum" src="<?=  $row['background_image'] ?>" width="100"></td>
                        <td><?php echo miladi_to_shamsi($row['created_in']); ?></td>
                        <td>
                            <a href="delete.php?id=<?= $row["id"] ?>& background_image=<?= $row["background_image"] ?> "methods="get" onclick="return confirm('ایا مطمین هستید')" >حذف     </a>
                            <p>------</p>
                            <a href="update.php?id=<?= $row["id"] ?> "methods="get">ویرایش     </a>
                            <p>------</p>
                            <a href="update_priority.php?id=<?= $row["id"] ?>&direction=up">⬆ بالا</a>
                            <a href="update_priority.php?id=<?= $row["id"] ?>&direction=down">⬇ پایین</a>
                        </td>
                        <td>
                               <?php 
                                   if ($row['last_updated']) {
                                      echo miladi_to_shamsi($row['last_updated']);
                                    } else {
                                      echo "ویرایش نشده";
                                     }
                                   ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </section>
    <section>
        <div class="table-container">
            <h3> نظرات سایت</h3>
            <table>
                <thead>
                <tr>
                    <th>نام</th>
                    <th>شماره تماس</th>
                    <th>نظر</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($comments_result)) { ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['comment']; ?></td>
                        <td><a href="delete_comment.php?id=<?= $row["id"] ?> "methods="get" onclick="return confirm('ایا مطمین هستید')" >حذف     </a>
                        </td>

                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="table-container">
            <h3>درخواست‌های آزمون سلامت دندان</h3>
            <table class="tb">
                <thead>
                    <tr>
                        <th>شناسه</th>
                        <th>نام</th>
                        <th>شماره تماس</th>
                        <th>سوال1</th>
                        <th>سوال2</th>
                        <th>سوال3</th>
                        <th>سوال4</th>
                        <th>سوال5</th>
                        <th>سوال6</th>
                        <th>سوال7</th>
                        <th>سوال8</th>
                        <th>سوال9</th>
                        <th>سوال10</th>
                    </tr>
                </thead>
                <tabel>
                    <?php while ($row = mysqli_fetch_assoc($health_test_result)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['fullname']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['question1']; ?></td>
                            <td><?php echo $row['question2']; ?></td>
                            <td><?php echo $row['question3']; ?></td>
                            <td><?php echo $row['question4']; ?></td>
                            <td><?php echo $row['question5']; ?></td>
                            <td><?php echo $row['question6']; ?></td>
                            <td><?php echo $row['question7']; ?></td>
                            <td><?php echo $row['question8']; ?></td>
                            <td><?php echo $row['question9']; ?></td>
                            <td><?php echo $row['question10']; ?></td>
                        </tr>
                    <?php } ?>
                    </tabel>
            </table>
        </div>

        <div class="table-container">
            <h3>درخواست‌های مشاوره آنلاین</h3>
            <table>
                <thead>
                    <tr>
                        <th>شناسه</th>
                        <th>ایمیل</th>
                        <th>نام و نام خانوادگی</th>
                        <th>پیام</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($consultation_result)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['message']; ?></td>
                           
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="table-container">
            <h3>درخواست‌های نوبت‌دهی</h3>
            <table>
                <thead>
                    <tr>
                        <th>نام</th>
                        <th>شماره تماس</th>
                        <th>تاریخ نوبت</th>
                    </tr>
                </thead>
                <tbody>
<!--                    --><?php //while ($row = mysqli_fetch_assoc($appointment_result)) { ?>
<!--                        <tr>-->
<!--                            <td>--><?php //echo $row['full_name']; ?><!--</td>-->
<!--                            <td>--><?php //echo $row['phone']; ?><!--</td>-->
<!--                            <td>--><?php //echo $row['appointment_time']; ?><!--</td>-->
<!--                        </tr>-->
<!--                    --><?php //} ?>
                </tbody>
            </table>
        </div>
        <div class="table-container">
            <h3>نوبت کاری پزشکان </h3>
            <form method="post" action="doctor_schedule.php" enctype="multipart/form-data">
                <label>نام پزشک:</label>
                <select name="doctor_id">
                    <?php
                    include ('config.php');
                    $sql='select * from dbo_add_doctors';
                    $result=$conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['name']} - {$row['takhasos']}</option>";
                    }
                    ?>
                </select>
                <label>انتخاب بازه زمانی:</label>
                <select name="schedule_id">
                    <?php
                    include ('config.php');
                    $sql='select * from dbo_schedule_nobat ';
                    $result=$conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['day_of_week']} - {$row['time_slot']}</option>";
                    }
                    ?>
                </select>
                <input type="submit" name="submit" value="  ثبت " class="submit-btn">
            </form>


            <table>
                <thead>
                <tr>
                    <th>نام پزشک</th>
                    <th>تخصص</th>
                    <th>روز</th>
                    <th>ساعت</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                <?php
                // دریافت اطلاعات نوبت‌ها همراه با اطلاعات پزشک
                $sql = "SELECT ds.id as doctor_schedule_id, d.id as doctor_id, d.name, d.takhasos, s.id as schedule_id, s.day_of_week, s.time_slot 
                FROM doctor_schedule ds
                JOIN dbo_add_doctors d ON ds.doctor_id = d.id
                JOIN dbo_schedule_nobat s ON ds.schedule_id = s.id";

                $result = $conn->query($sql);



                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['takhasos']; ?></td>
                        <td><?php echo $row['day_of_week']; ?></td>
                        <td><?php echo $row['time_slot']; ?></td>
                        <td><a href="delete_doctor_schedule.php?id=<?= $row['doctor_schedule_id'] ?>" onclick="return confirm('آیا مطمئن هستید؟')">حذف</a></td>                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>






        <div class="table-container">
            <h3>مشخصات دکتر</h3>
            <form method="post" action="doctors.php" enctype="multipart/form-data">
                <label>:نام</label>
                <input type="text" name="d-name">

                <label>تخصص:</label>
                <input type="text" name="d-takhasos" >

                <label>شماره همراه:</label>
                <input type="number" name="d-phone">
                <br><br>
                <input type="submit" name="submit" value="  ثبت " class="submit-btn">
            </form>
            <table>
                <thead>
                <tr>
                    <th>نام</th>
                    <th>تخصص</th>
                    <th>شماره تماس</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($doctors_result)) { ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['takhasos']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><a href="delete_docs.php?id=<?= $row["id"] ?> "methods="get" onclick="return confirm('ایا مطمین هستید')" >حذف     </a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>




        <div class="table-container">
            <h3>نوبت کاری </h3>
            <form method="post" action="nobats.php" enctype="multipart/form-data">
                <label>روز:</label>
                <select name="day">
                    <option value="شنبه">شنبه</option>
                    <option value="یکشنبه">یکشنبه</option>
                    <option value="دوشنبه">دوشنبه</option>
                    <option value="سه‌شنبه">سه‌شنبه</option>
                    <option value="چهارشنبه">چهارشنبه</option>
                    <option value="پنجشنبه">پنجشنبه</option>
                    <option value="جمعه">جمعه</option>
                </select>
                <label>ساعت:</label>
                <input type="text" name="time">
                <input type="submit" name="submit" value="  ثبت " class="submit-btn">
            </form>
            <table>
                <thead>
                <tr>
                    <th>روز</th>
                    <th>ساعت</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($nobat_result)) { ?>
                    <tr>
                        <td><?php echo $row['day_of_week']; ?></td>
                        <td><?php echo $row['time_slot']; ?></td>
                        <td><a href="delete_schedule.php?id=<?= $row["id"] ?> "methods="get" onclick="return confirm('ایا مطمین هستید')" >حذف     </a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</body>

</html>
