<?php
include 'connect.php';

session_start();

if(!$_SESSION['username']) {
  header ('Location: login.php');
} else {
  $username = $_SESSION['username'];
}

// Fetch all registrations
$query = "SELECT * FROM registration_tb ORDER BY id DESC";
$result = mysqli_query($connect, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrations | CORE-TECH</title>
    <meta name="description" content="View and manage course registrations">
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
        <div class="container text-center py-5 mt-3" data-aos="fade-up" data-aos-delay="300">
            <h1 class="display-4 text-secondary"><b>Course Registrations</b></h1>
            <p class="lead text-dark">Manage and review course registrations</p>
            <br><br>
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="" data-aos="fade-up" data-aos-delay="500">
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-start">Name</th>
                                            <th class="text-start">Email</th>
                                            <th class="text-start">Phone</th>
                                            <th class="text-start">Course</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(mysqli_num_rows($result) > 0): ?>
                                            <?php $i = 1; while($row = mysqli_fetch_assoc($result)): ?>
                                                <tr data-aos="fade-up" data-aos-delay="<?= $i * 50 ?>">
                                                    <td class="text-start">
                                                        <div class="d-flex align-items-center">
                                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 12px;">
                                                                <?= strtoupper(substr(htmlspecialchars($row['first_name']), 0, 1)) ?>
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold"><?= htmlspecialchars($row['first_name']) ?> <?= htmlspecialchars($row['surname']) ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-start">
                                                        <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="text-decoration-none text-black">
                                                            <?= htmlspecialchars($row['email']) ?>
                                                        </a>
                                                    </td>
                                                    <td class="text-start">
                                                        <a href="tel:<?= htmlspecialchars($row['phone']) ?>" class="text-decoration-none text-black">
                                                            <?= htmlspecialchars($row['phone']) ?>
                                                        </a>
                                                    </td>
                                                    <td class="text-start">
                                                        <?= htmlspecialchars($row['course_of_study']) ?>
                                                    </td>
                                                    <td>
                                                        <a href="view_registration.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                                            View
                                                            <i class="bi bi-eye-fill ms-2"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php $i++; endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                                                        <p class="mt-2 mb-0">No course registrations found.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="600">
                    <a href="index.php" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Dashboard
                    </a>
                    <a href="logout.php" class="btn btn-outline-danger">
                        Logout
                        <i class="bi bi-box-arrow-right ms-2"></i>
                    </a>
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
