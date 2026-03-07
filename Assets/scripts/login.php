<?php


// Connect to database
include 'connect.php';

// Set blank varibales
$username = $password = $error_msg = "";

// Get the form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Write the SQL query to check if the user exists
    $stmt = $connect->prepare("SELECT * FROM users_tb WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows === 1) {
        session_start();
        // if credentials is validated
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        // else it is not validated
        $error_msg = "Invalid username or password";
    }

    // Close the statement and connection
    $stmt->close();
    $connect->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CORE-TECH</title>
    <meta name="description" content="Login to CORE-TECH admin panel">
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
        <h1 class="display-4 bluey"><b>Admin Login</b></h1>
        <br><br>
        <div class="row justify-content-center">
          <div class="col-md-6 col-lg-4">
            <div class="card border-0 py-5 px-4 contact-section-2" data-aos="fade-up" data-aos-delay="500">
                  <p class="lead small">Access your CORE-TECH admin panel</p>
                        <?php if (!empty($error_msg)): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <?php echo htmlspecialchars($error_msg); ?>
                            </div>
                        <?php endif; ?>
                        <form method="post" action="login.php">
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control fw-light" name="username" required placeholder="Username">
                                </div>
                            </div>
                            <div class="mb-5">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control fw-light" name="password" required placeholder="Password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                              Login
                              <i class="bi bi-box-arrow-in-right ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
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