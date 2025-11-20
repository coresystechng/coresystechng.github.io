<?php

    session_start();

    // server-side logout logic
    // 5 minutes
    $limit = 300;

    if (isset($_SESSION['last_activity'])) {
        if (time() - $_SESSION['last_activity'] > $limit) {
            header("Location: logout.php");
            exit();
        }
    }

    $_SESSION['last_activity'] = time();
    
    if($_SERVER['QUERY_STRING'] == 'noname') {
        unset($_SESSION['username']);  // Log out user by unsetting username (if there is no username registered)
    }
    
    // Check if user is authenticated
    if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
        $_SESSION['error'] = "Please log in to continue.";
        header("Location: index.php");
        exit;
    }
    
    include('connect.php');
    
    // Get username from session or set to 'Guest' if not available
    $username =  $_SESSION['username'] ?? 'Guest';
    

    // Fetch admin details
    if ($username) {
        $stmt = $connect->prepare("SELECT * FROM users_tb WHERE username = ?"); // prepared sql statement to prevent SQL injection
        $stmt->bind_param("s", $username); // 's' specifies the variable type => 'string'... "i" => integer, "d" => double, "b" => blob
        $stmt->execute(); // execute/run the query
        $result = $stmt->get_result(); // get the mysqli result of all executed query data
        $admin = $result->fetch_assoc(); // fetch data as an associative array
    } else {
        $admin = null; // no admin found
    }

    $sql = "SELECT * FROM registration_tb";
    $result = mysqli_query($connect, $sql); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="dashboard_style.css">
    <style>
        #userDropdown {
        color: #fff !important; 
        text-decoration: none;
        }

        #userDropdown:hover,
        #userDropdown:focus,
        #userDropdown.show {
        color: #fff !important;
        background-color: #0e2a46ff !important;
        }
        .blue {
            color: #0e2a46ff;
        }
        .active {
            background-color: #0f2942ff;
            border-radius: 12px;
            margin: 0 10px;
        }
        .reg:hover {
            background-color: #163450ff;
            border-radius: 12px;
            margin: 0 10px;
            transition: all ease .12s;
        }
        .h-100 {
            display: flex;
            flex-direction: column;
        }

        .foot {
            color: #adb5bd;
            margin-left: 1.2em;
            margin-right: 1em;
            font-size: .7em;
        }

        .dark {
            color: #444;
        }
        
        .dark:hover {
            color: #000;
            /* font-weight: 600; */
        }
    </style>
</head>
<body>

    <!-- inactivity modal -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <p class="mb-2 fs-5">You will be logged out due to inactivity in <span class="blue"><strong id="logoutCount">60</strong>s.</span></p>
                    <div class="d-flex justify-content-center gap-2">
                        <button id="stayBtn" class="btn btn-primary btn-sm">Stay signed in</button>
                        <button id="logoutBtn" class="btn btn-outline-secondary btn-sm">Log out now</button>
                    </div>
                </div>
                </div>
            </div>
        </div>


    <div class="wrapper">
        <aside id="sidebar" class="d-lg-block d-none">
            <div class="h-100">
                <div class="sidebar-logo">
                    <a href="./admin_dashboard.php"><img loading="lazy" src="img/core_white.png" class="p-1 my-3" style="width: 8rem !important;" alt="CORE-TECH"></a>
                </div>
                <ul class="sidebar-nav">
                    <li class="reg my-1">
                        <a href="admin_dashboard.php" class="sidebar-link">
                            <i class="fa-solid fa-grip"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="reg active">
                        <a href="./student_dashboard.php" class="sidebar-link">
                            <i class="fa-solid fa-graduation-cap"></i>
                            Students
                        </a>
                    </li>
                    <li class="reg my-1">
                        <a href="./staff_dashboard.php" class="sidebar-link">
                            <i class="fa-solid fa-briefcase"></i>
                            Staff
                        </a>
                    </li>
                    <li class="reg my-1">
                        <a href="./payment.php" class="sidebar-link">
                            <i class="fa-solid fa-sack-dollar"></i>
                            Payments
                        </a>
                    </li>
                    <li class="reg my-1">
                        <a href="./files.php" class="sidebar-link">
                            <i class="fa-solid fa-file-lines"></i>
                            Files
                        </a>
                    </li>
                </ul>
                <p class="foot">&copy; 2025 <a href="https://coresystech.ng" target="_blank" class="log">CORE-TECH</a>. <span class="light" >All Rights Reserved.</span></p>
            </div>
        </aside>
        <div class="main bg-light">
                <nav class="navbar navbar-expand border-bottom px-4">
                    <button class="btn d-lg-block d-none" id="sidebar-toggle" type="button">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="d-block d-lg-none gap-3">
                        
                        <a id="userDropdown2" class="nav-link d-flex align-items-center" href="#" data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer;">
                            <i class="fa-solid fa-bars fs-1 ms-2"></i>
                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-start ps-3 ms-4" aria-labelledby="userDropdown">
                            <li class="mt-3"><a href="./admin_dashboard.php" class="text-dark" style="text-decoration:none;"><i class="fa-solid fa-grip fs-2 pe-2"></i> Dashboard</a></li> <hr>
                            <li class="mb-0"><a href="./student_dashboard.php" class="text-dark" style="text-decoration:none;"><i class="fa-solid fa-graduation-cap fs-2 pe-2"></i> Students</a></li> <hr>
                            <li class="mb-0"><a href="./staff_dashboard.php" class="text-dark" style="text-decoration:none;"><i class="fa-solid fa-briefcase fs-2 pe-2"></i> Staff</a></li> <hr>
                            <li class="mb-0"><a href="./payment.php" class="text-dark" style="text-decoration:none;"><i class="fa-solid fa-sack-dollar fs-2 pe-2"></i> Payments</a></li> <hr>
                            <li class="mb-3"><a href="./files.php" class="text-dark" style="text-decoration:none;"><i class="fa-solid fa-file-lines fs-2 pe-2"></i> Files</a></li>
                        </ul>
                    </div>
                    <div class="navbar-collapse navbar">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown ms-2">

                                <!-- mobile view -->
                                <a id="userDropdown2" class="nav-link align-items-center d-md-none d-sm-block" href="#" data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer;">
                                    <img loading="lazy" src="img/avatar.jpg" class="avatar img-fluid rounded-pill me-2" alt="" style="width:50px;height:50px;object-fit:contain;">
                                </a>

                                <!-- desktop view -->
                                <a id="userDropdown" class="nav-link align-items-center rounded-pill bg px-1 pe-0 d-none d-md-block" href="#" data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer;">
                                    <span class="px-2 mb-0">Welcome, <?php echo htmlspecialchars($username); ?></span>
                                    <img loading="lazy" src="img/avatar.jpg" class="avatar img-fluid rounded-pill me-2" alt="" style="width:40px;height:40px;object-fit:contain;">
                                </a>

                                <!-- dropdown -->
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="profile.php"><i class="fa-solid fa-user fs-5 pe-1"></i> Profile</a></li>
                                    <li><a class="dropdown-item text-muted" href="#"><i class="fa-solid fa-gear fs-5 pe-1"></i> Settings</a></li>
                                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa-solid fa-right-from-bracket fs-5 pe-1"></i> Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                </nav>

            <main class="content px-3 py-2">
                <div class="container-fluid">
                    <?php if (!empty($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="mt-3 mb-4">
                        <h1 class="blue">Students</h1>
                        <h5 class="mb-0 light" style="font-family: cera_light !important; font-weight: 600 !important;">Overview of student data</h5>
                    </div>

                    <div class="table-responsive-md">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="blue">Id</th>
                                    <th scope="col" class="blue">Name</th>
                                    <th scope="col" class="blue">Email</th>
                                    <th scope="col" class="blue">Phone</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($result)) { ?>
    
                                    <tr>
                                        <th scope="row"><?php echo htmlspecialchars($row['id']); ?></th>
                                        <td><a href="student_details.php?id=<?php echo $row['id'] ?>" class="dark"><?php echo htmlspecialchars($row['first_name']); ?>&nbsp<?php echo htmlspecialchars($row['surname']); ?><i class="fa-solid fa-up-right-from-square ps-1 fs-6"></i></a></td>
                                        <td><a href="mailto:<?php echo ($row['email']); ?>"><?php echo htmlspecialchars($row['email']); ?></a></td>
                                        <td><a href="tel:<?php echo ($row['phone']); ?>"><?php echo htmlspecialchars($row['phone']); ?></a></td>
                                        
                                    </tr>
    
                                <?php } ?>
                            </tbody>
                        </table>
                        <p class="text-muted fst-italic d-md-none d-sm-block">swipe to view full table</p>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="script.js" ></script>

    <!-- inactivity timeout logic -->
    <script>
        (function(){
        const INACTIVE_MIN = 5; // minutes
        const WARN_SECS = 60;
        const MS = 60000;
        const logoutUrl = 'logout.php';

        const modalEl = document.getElementById('logoutModal');
        const bsModal = new bootstrap.Modal(modalEl, {backdrop:'static', keyboard:false});
        const countEl = document.getElementById('logoutCount');
        const stayBtn = document.getElementById('stayBtn');
        const logoutBtn = document.getElementById('logoutBtn');

        let last = Date.now(), warnTimer = null, logoutTimer = null, tick = null;

        function isModalVisible() {
            return modalEl.classList.contains('show');
        }

        function startTimers(){
            clearTimers();
            const warnDelay = INACTIVE_MIN * MS - WARN_SECS * 1000;
            warnTimer = setTimeout(showWarning, Math.max(0, warnDelay));
            logoutTimer = setTimeout(doLogout, INACTIVE_MIN * MS);
        }

        function clearTimers(){
            clearTimeout(warnTimer); clearTimeout(logoutTimer); clearInterval(tick);
            try { bsModal.hide(); } catch(e){}
        }

        function showWarning(){
            // show and start countdown
            let remaining = Math.ceil((INACTIVE_MIN*MS - (Date.now()-last))/1000);
            countEl.textContent = remaining;
            bsModal.show();

            tick = setInterval(()=>{
            remaining = Math.max(0, Math.ceil((INACTIVE_MIN*MS - (Date.now()-last))/1000));
            countEl.textContent = remaining;
            if(remaining <= 0) clearInterval(tick);
            }, 1000);
        }

        function doLogout(){
            try { bsModal.hide(); } catch(e){}
            window.location.href = logoutUrl;
        }

        // reset triggered by generic activity (but ignore events while modal shown)
        function resetFromActivity(){
            if (isModalVisible()) return; // DO NOT hide modal on incidental activity
            last = Date.now();
            startTimers();
        }

        // explicit "Stay signed in" behaviour: always renew session even if modal is visible
        function staySignedIn(){
            last = Date.now();
            startTimers();
            try { bsModal.hide(); } catch(e){}
        }

        // event listeners
        ['mousemove','mousedown','keydown','scroll','touchstart','click'].forEach(e =>
            window.addEventListener(e, throttle(resetFromActivity, 800), {passive:true})
        );

        stayBtn.addEventListener('click', function(e){
            e.stopPropagation(); // ensure click won't be swallowed by other handlers
            staySignedIn();
        });

        logoutBtn.addEventListener('click', doLogout);

        const manual = document.getElementById('manual-logout');
        if(manual) manual.addEventListener('click', e=>{ e.preventDefault(); doLogout(); });

        function throttle(fn, wait){
            let lastCall = 0;
            return function(...a){
            const now = Date.now();
            if(now - lastCall > wait){ lastCall = now; fn.apply(this, a); }
            };
        }

        // start
        startTimers();
        })();
    </script>

</body>
</html>