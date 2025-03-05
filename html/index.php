<?php session_start();?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دندان پزشکی یارا</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/index.css">
    <style>
        section{
           padding-top: 0px;
        }
        .homepage {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .slide-section {
            position: relative;
            width: 100%;
            max-width: 1200px;
            height: 2000px;
            overflow: hidden;
            border-radius: 10px;
        }

        .background-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(70%);
        }

        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            padding: 20px;
            width: 80%;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .text h1 {
            font-size: 28px;
            margin: 0;
        }

        .text h2 {
            font-size: 20px;
            margin: 10px 0 0;
            font-weight: normal;
        }

    </style>
</head>

<body dir="rtl">
    <header class="header">
        <nav class="navbar" id="menu">
            <button class="nav-logo nav-button">
                <a href="../html/reserv.html" class="nav-logo">نوبت دهی</a>
            </button>
    
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#" class="nav-link">خانه</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" id="servicesLink">خدمات +</a>
                    <ul class="sub-menu">
                        <li><a href="#services"> خدمات </a></li>
                        <li><a href="../html/moshaver.html">مشاوره آنلاین </a></li>
                        <li><a href="../html/mohasebe.html">محاسبه هزینه </a></li>
                        <li><a href="../html/azmon.html"> آزمون سلامت دندان</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#user-reviews" class="nav-link">نظرات</a>
                </li>
                <li class="nav-item">
                    <a href="#vlogs" class="nav-link">ولاگ</a>
                </li>
                <li class="nav-item">
                    <a href="../html/login.html" class="nav-link">ورود</a>
                </li>
            </ul>
    
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </nav>
    </header>
    <?php
    include('../php/config.php');
    $sql = "SELECT * FROM site_settings ";
    $result = mysqli_query($conn , $sql);
    $slider = mysqli_fetch_assoc($result);
    ?>
<section class="homepage">
    <?php
    $slider_query = "SELECT * FROM site_settings ORDER BY priority ASC";
    $slider_result = mysqli_query($conn, $slider_query);

    while ($slider = mysqli_fetch_assoc($slider_result)) { ?>
        <section class="slide-section flex" id="slide-<?php echo $slider['id']; ?>">
            <img class="background-image" src="<?php echo $slider['background_image'] ?? '../image/home.jpg'; ?>" alt="Background">
            <div class="content">
                <div class="text">
                    <h1><?php echo $slider['title'] ?? "بدون عنوان"; ?></h1>
                    <h2><?php echo $slider['subtitle'] ?? "بدون زیرعنوان"; ?></h2>
                </div>
            </div>
        </section>
    <?php } ?>
</section>


<section class="services" id="services">
    <div class="container">
        <div class="sec-titel">
            <h2>خدمات ما</h2>
        </div>
        <div class="cards-container">
            <div class="card">
                <img src="../image/blich.webp" alt="bliching">
                <h3>بلیچینگ</h3>
                <p>بلیچینگ دندان یا سفیدسازی دندان یکی از خدمات دندانپزشکی محبوب است که برای روشن کردن دندان‌های تیره و زرد شده استفاده می‌شود...</p>
            </div>
            <div class="card">
                <img src="../image/ortedensi.jpg" alt="ortedensi">
                <h3>ارتودنسی</h3>
                <p>ارتودنسی یکی از روش‌های تخصصی در دندانپزشکی است که به اصلاح ناهنجاری‌های دندانی و فکی می‌پردازد. این درمان با استفاده از ابزارهایی مانند براکت‌ها و سیم‌های مخصوص، دندان‌ها را به موقعیت مناسب هدایت می‌کند. هدف اصلی ارتودنسی بهبود عملکرد دهان، ارتقای زیبایی لبخند و پیشگیری از مشکلات احتمالی در آینده است.</p>
            </div>
            <div class="card">
                <img src="../image/moshavereh.jpg" alt="moshavereh">
                <h3>مشاوره آنلاین</h3>
                <p>مشاوره آنلاین دندانپزشکی راهی آسان و سریع برای ارتباط با متخصصان است. شما می‌توانید از خانه یا محل کار، مشکلات دندانی خود را توضیح دهید و بهترین راه‌حل را دریافت کنید. این روش به‌ویژه برای سوالات فوری یا ارزیابی اولیه بسیار مناسب است. صرفه‌جویی در زمان و هزینه از مزایای اصلی این خدمات است.</p>
            </div>
            <div class="card">
                <img src="../image/asab.jpg" alt="asab">
                <h3>عصب کشی</h3>
                <p>عصب‌کشی روشی برای درمان دندان‌هایی است که دچار عفونت یا آسیب شدید شده‌اند. در این روش، با حذف عصب آسیب‌دیده، دندان تمیز و پر می‌شود تا عملکرد طبیعی خود را بازیابد. این درمان باعث حفظ دندان و جلوگیری از کشیدن آن می‌شود. با عصب‌کشی می‌توانید درد را کاهش داده و لبخند خود را حفظ کنید.</p>
            </div>
            <div class="card">
                <img src="../image/laminat.jpg" alt="laminat">
                <h3>لمینت</h3>
                <p>لمینت دندان (Dental laminate) که گاهی به آن ونیر (Veneer) نیز گفته می شود، روکش نازکی است که همرنگ دندان، و جهت بهبود زیبایی لبخند و ظاهر کلی فرد طراحی شده است. وقتی قطعات کوچک لمینت روی دندان ها قرار می‌گیرند، باعث تغییر رنگ، شکل، اندازه یا طول دندان‌ها به وضعیت دلخواه فرد می شوند</p>
            </div>
            <div class="card">
                <img src="../image/jerm.jpg" alt="jerm">
                <h3>جرم گیری</h3>
                <p>جرم‌گیری دندان‌ها، راهی ساده و مؤثر برای از بین بردن پلاک‌ها و جرم‌های سخت‌شده است. این درمان نه‌تنها باعث زیبایی بیشتر دندان‌ها می‌شود، بلکه از بیماری‌های لثه و بوی بد دهان نیز جلوگیری می‌کند. جرم‌گیری به طور منظم سلامت دندان‌ها و لثه‌های شما را تضمین می‌کند. با این کار، لبخندی درخشان‌تر و سالم‌تر خواهید داشت!</p>
            </div>
        </div>
    </div>
</section>
<section class="user-reviews" id="user-reviews">
    <div class="th-titel">
        <h2>نظرات کاربران</h2>
    </div>
    <div class="review-group">
        <?php
        include('../php/config.php');
        $sql = "SELECT * FROM dbo_user_comments ";
        $result = mysqli_query($conn , $sql);
        $slider = mysqli_fetch_assoc($result);
        ?>
        <section class="homepage">
            <?php
            $slider_query = "SELECT * FROM dbo_user_comments";
            $slider_result = mysqli_query($conn, $slider_query);
            while ($slider = mysqli_fetch_assoc($slider_result)) { ?>
                <div class="review-card">
                    <h1><?php echo $slider['name'] ?? "بدون نام"; ?></h1>
                    <div class="rating">★★★★☆</div>
                    <p><?php echo $slider['comment'] ?? "بدون پیام"; ?></p>
                </div>
            <?php } ?>
            <div class="add-button">
                <a href="comments.php" style="color: white">+</a>
                </div>
        </div>
    </div>
</section>
<section id="vlogs">
    <div class="blog-section">
        <h2>مجله آموزش  </h2>
        <div class="blog-container">
            <div class="blog-card">
                <img src="../image/vlog1.jpg" alt="وبلاگ 1">
                <h3>کامپوزیت دندان کج چگونه انجام می‌شود؟</h3>
                <p>دو هفته پیش</p>
                <p>لبخند زیبا، پل ارتباطی ما با دیگران است. دندان‌های سالم و مرتب، اولین تصویری است که از خود به دیگران منتقل می‌کنیم و می‌تواند تأثیر مثبتی بر روابط ...</p>
            </div>
            <div class="blog-card">
                <img src="../image/vlog2.jpg" alt="وبلاگ 2">
                <h3>بعد از کشیدن دندان عقل چه غذایی بخوریم؟</h3>
                <p>سه هفته پیش</p>
                <p>جراحی دندان عقل، یکی از تجربه‌هایی است که بسیاری از ما در طول زندگی با آن مواجه می‌شویم. پس از جراحی، مراقبت از محل زخم و توجه به رژیم غذایی مناسب،...</p>
            </div>
            <div class="blog-card">
                <img src="../image/vlog3.jpg" alt="وبلاگ 3">
                <h3>همه چیز درباره پوسیده شدن دندان زیر لمینت</h3>
                <p>سه هفته پیش</p>
                <p>آیا لمینت می‌تواند باعث پوسیده شدن دندان زیر لمینت گردد؟ لمینت سرامیکی، در بازسازی طرح لبخند بسیار کاربردی است. اما سؤالی که همواره در افراد کاندید...</p>
            </div>
            <div class="blog-card">
                <img src="../image/vlog4.jpg" alt="وبلاگ 4">
                <h3>بررسی انواع کامپوزیت دندان و معرفی بهترین</h3>
                <p>سه هفته پیش</p>
                <p>کامپوزیت دندان به‌عنوان یکی از پیشرفته‌ترین و کارآمدترین مواد در دندانپزشکی ترمیمی و زیبایی، مورد استفاده قرار می‌گیرد. هدف از استفاده از کامپوزیت،...</p>
            </div>
            <div class="blog-card">
                <img src="../image/vlog5.jpg" alt="وبلاگ 5">
                <h3>بررسی معایب و مزایای خمیر دندان زغالی</h3>
                <p>یک ماه پیش</p>
                <p>خمیر دندان زغالی، یکی از مواردی است که امروزه شاهد افزایش استفاده از آن می‌باشیم. اگر از افرادی که از این خمیر دندان استفاده می‌کنند، سؤال کنید که ...</p>
            </div>
            <div class="blog-card">
                <img src="../image/vlog6.jpg" alt="وبلاگ 6">
                <h3>چه ‌کسانی به دهانشویه نیاز دارند؟</h3>
                <p>چهار هفته پیش</p>
                <p>بهداشت دهان و دندان یکی از مهم‌ترین اصول سلامت عمومی بدن است. بسیاری از افراد با استفاده منظم از مسواک تلاش می‌کنند تا از سلامت دندان‌های خود محافظ...</p>
            </div>
            
        </div>
        <button id="scrollButton">مشاهده بیشتر</button>
    </div>
    
</section>

<footer>
    <div class="row">
        <div class="col"><h3>راه های ارتباطی</h3>
            <div class="icon-div"><p>تهرانپارس میدان شاهد کوچه جعفری پلاک 11 واحد 8</p></div>    
            <div class="icon-div"><p>09109253995</p></div>
            <div class="icon-div"><p>reyhanghodrati@gmail.com</p></div>
        </div>
        <div class="col"><h3>دسترسی سریع</h3>
            <ul><li><a href="#">خانه </a></li>
            <li><a href="#services">خدمات</a></li>
            <li><a href="../html/moshaver.html">مشاوره</a></li>
            <li><a href="../html/reserv.html">نوبت دهی</a></li></ul>
        </div>
        <div class="col"><h3 id="h33">اخرین اخبار ما:</h3>
            <form action="">
                <i class="far fa fa-envelope"></i>
                <input type="email" placeholder="  ایمیلتون رو وارد کنید">
                <button type="submit"> <i class="fas fa-arrow-left"></i></button>
            </form>
            <div class="social-icons">
                  <i class="fab fa-facebook-f"></i>
                  <i class="fab fa-twitter"></i>
                  <i class="fab fa-instagram"></i>
                  <i class="fab fa-linkedin-in"></i>
                </a>
              </div>
            </div>
        </div>
    </div>
</footer>
    <script src="../js/js.js"></script>
</body>

</html>
