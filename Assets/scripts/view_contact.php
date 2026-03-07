<?php

include 'connect.php';

session_start();

if(!$_SESSION['username']) {
  header ('Location: login.php');
} else {
  $username = $_SESSION['username'];
}

// Get contact ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch contact details
$query = "SELECT * FROM contact_tb WHERE id = $id LIMIT 1";
$result = mysqli_query($connect, $query);
$contact = mysqli_fetch_assoc($result);
// print_r($contact);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Contact Details | CORE-TECH</title>
    <meta name="description" content="View detailed contact information">
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
    <section>
        <div class="container text-center pt-5 mt-3" data-aos="fade-up" data-aos-delay="300">
            <h1 class="display-4 bluey"><b><?= htmlspecialchars($contact['first_name']) ?> <?= htmlspecialchars($contact['last_name']) ?></b></h1>
            <p class="lead text-dark"><i class="bi bi-calendar2-check me-1"></i> <?= isset($contact['date_created']) ? date('F j, Y g:i A', strtotime($contact['date_created'])) : '' ?></p>
            <br>
            <br>
            <div class="row justify-content-center ">
                <div class="col-md-8 col-lg-6 contact-section-2 p-4">
                    <?php if ($contact): ?>
                        <div class="" data-aos="fade-up" data-aos-delay="500">
                              <div class="">
                                <div class="row mb-3 text-start">
                                    <div class="col-sm-2 text-muted fw-bold">Email:</div>
                                    <div class="col-sm-10">
                                        <a href="mailto:<?= htmlspecialchars($contact['email']) ?>" class="text-decoration-none text-dark">
                                            <?= htmlspecialchars($contact['email']) ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="row mb-3 text-start">
                                    <div class="col-sm-2 text-muted fw-bold">Phone:</div>
                                    <div class="col-sm-10">
                                        <a href="tel:<?= htmlspecialchars($contact['phone']) ?>" class="text-decoration-none text-dark">
                                            <?= htmlspecialchars($contact['phone']) ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="row mb-3 text-start">
                                    <div class="col-sm-2 text-muted fw-bold">Subject:</div>
                                    <div class="col-sm-10">
                                        <span class="badge bg-warning text-dark p-2">
                                            <?= htmlspecialchars($contact['subject']) ?>
                                        </span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3 text-start">
                                    <div class="col-sm-4 text-muted fw-bold mb-2">Message:</div>
                                    <div class="col-sm-12">
                                        <div class="bg-white p-3 rounded-3">
                                            <p class="mb-0"><?= nl2br(htmlspecialchars($contact['message'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-transparent py-3">
                                <div class="d-flex justify-content-between">
                                    <a href="contacts.php" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-2"></i>Back to List
                                    </a>
                                    <div>
                                        <a href="mailto:<?= htmlspecialchars($contact['email']) ?>" class="btn btn-outline-info me-2" title="Reply via Email">
                                            <i class="bi bi-reply-fill me-2"></i> Reply
                                        </a>
                                        <a href="tel:<?= htmlspecialchars($contact['phone']) ?>" class="btn btn-success" title="Call">
                                            <i class="bi bi-telephone-fill me-2"></i>Call
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card shadow-lg" data-aos="fade-up" data-aos-delay="500">
                            <div class="card-body text-center py-5">
                                <div class="text-danger mb-3">
                                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="text-danger">Contact Not Found</h5>
                                <p class="text-muted">The contact submission you're looking for doesn't exist or has been removed.</p>
                                <a href="contacts.php" class="btn btn-primary">
                                    <i class="bi bi-arrow-left me-2"></i>Back to Contact List
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
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