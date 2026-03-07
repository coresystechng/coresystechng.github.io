<?php
session_start();

if(!$_SESSION['username']) {
  header ('Location: login.php');
} else {
  $username = $_SESSION['username'];
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | CORE-TECH</title>
    <meta name="description" content="CORE-TECH admin dashboard">
    <link rel="icon" href="https://coresystech.ng/assets/img/rel_icon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://coresystech.ng/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-SZZZBMM6GL"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-SZZZBMM6GL');
</script>
<body>
<!-- Preloader -->
<div id="preloader">
    <lord-icon
        src="https://cdn.lordicon.com/ljpehpdr.json"
        trigger="loop"
        state="loop-triangle"
        colors="primary:#134074"
        style="width:80px;height:80px">
    </lord-icon>
</div>

<header id="home">
    <section class="">
        <div class="container text-center text-white pt-5 mt-3" data-aos="fade-up" data-aos-delay="300">
            <h1 class="display-4 bluey"><b>Admin Dashboard</b></h1>
            <p class="lead text-dark">Welcome back, <?= htmlspecialchars($username) ?>! Manage your CORE-TECH website.</p>
            <br><br>
        
            <!-- Quick Actions -->
            <div class="row g-4 py-5 contact-section-2 rounded-3" data-aos="fade-up" data-aos-delay="500">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="contacts.php" class="btn btn-coretech w-100 mb-2">
                            <i class="bi bi-envelope-fill me-2"></i>Manage Contact Messages
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="registration.php" class="btn btn-secondary-3 w-100 mb-2">
                            <i class="bi bi-people-fill me-2"></i>View Registrations
                        </a>
                    </div>
                </div>
            </div>

            <!-- Logout Button -->
            <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="700">
                <a href="logout.php" class="btn btn-outline-danger btn-lg">
                    Logout
                    <i class="bi bi-box-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>
</header>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 900,
        once: true,
    });
</script>

<!-- Back to top -->
<script>
    //Get the button
    let mybutton = document.getElementById("btn-back-to-top");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function () {
    scrollFunction();
    };

    function scrollFunction() {
    if (
        document.body.scrollTop > 20 ||
        document.documentElement.scrollTop > 20
    ) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
    }

    // When the user clicks on the button, scroll to the top of the document
    function backToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
    }
</script>

<!-- preloader -->
<script>
    // Minimum display time in milliseconds
    const minDisplayTime = 1500;
    let startTime = Date.now();

    window.addEventListener('load', function() {
        const elapsedTime = Date.now() - startTime;
        const remainingTime = Math.max(0, minDisplayTime - elapsedTime);
        
        setTimeout(function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.style.opacity = '0';
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 300);
            }
        }, remainingTime);
    });
</script>

</body>
</html>
