<?php

    session_start();
    
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
        $stmt = $connect->prepare("SELECT * FROM admin_reg WHERE username = ?"); // prepared sql statement to prevent SQL injection
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
        
        .light {
            font-family: cera_light !important;
            font-weight: 500 !important;
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
    </style>
</head>
    <body>

        <div class="wrapper">
            <aside id="sidebar" class="d-lg-block d-none">
                <div class="h-100">
                    <div class="sidebar-logo">
                        <a href="./admin_dashboard.php"><img src="img/core_white.png" class="p-1 my-3" style="width: 8rem !important;" alt="CORE-TECH"></a>
                    </div>
                    <ul class="sidebar-nav">
                        <li class="active reg">
                            <a href="admin_dashboard.php" class="sidebar-link">
                                <i class="fa-solid fa-grip"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="reg my-1">
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
                            <li class="mb-2"><a href="./admin_dashboard.php" class="text-dark" style="text-decoration:none;"><i class="fa-solid fa-grip fs-2 pe-2"></i> Dashboard</a></li>
                            <li class="mb-2"><a href="./student_dashboard.php" class="text-dark" style="text-decoration:none;"><i class="fa-solid fa-graduation-cap fs-2 pe-2"></i> Students</a></li>
                            <li class="mb-2"><a href="./staff_dashboard.php" class="text-dark" style="text-decoration:none;"><i class="fa-solid fa-briefcase fs-2 pe-2"></i> Staff</a></li>
                            <li class="mb-2"><a href="./payment.php" class="text-dark" style="text-decoration:none;"><i class="fa-solid fa-sack-dollar fs-2 pe-2"></i> Payments</a></li>
                            <li class="mb-2"><a href="./files.php" class="text-dark" style="text-decoration:none;"><i class="fa-solid fa-file-lines fs-2 pe-2"></i> Files</a></li>
                        </ul>
                    </div>
                    <div class="navbar-collapse navbar">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown ms-2">

                                <!-- mobile view -->
                                <a id="userDropdown2" class="nav-link align-items-center d-md-none d-sm-block" href="#" data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer;">
                                    <img src="img/avatar.jpg" class="avatar img-fluid rounded-pill me-2" alt="" style="width:50px;height:50px;object-fit:contain;">
                                </a>

                                <!-- desktop view -->
                                <a id="userDropdown" class="nav-link align-items-center rounded-pill bg px-1 pe-0 d-none d-md-block" href="#" data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer;">
                                    <span class="px-2 mb-0">Welcome, <?php echo htmlspecialchars($username); ?></span>
                                    <img src="img/avatar.jpg" class="avatar img-fluid rounded-pill me-2" alt="" style="width:40px;height:40px;object-fit:contain;">
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
                        <h1 class="blue">Dashboard</h1>
                        <!-- <h5 class="mb-0 light" style="font-family: cera_light !important; font-weight: 600 !important;">Overview of student data</h5> -->
                    </div>
                    
                    <!-- cards -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="./student_dashboard.php" style="text-decoration: none">
                                <div class="card special-effects border-0 text-white bg-success h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Prospective Students</h5>
                                        <p class="light pt-1">View applications and manage new student entries.</p>
                                        <p class="card-text fs-1 text-end"><i class="fa-solid fa-up-right-from-square fs-6"></i></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="./student_dashboard.php" style="text-decoration: none">
                                <div class="card special-effects border-0 text-white bg-primary h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Registered Students</h5>
                                        <p class="light pt-1">Access the full list of enrolled students and manage their details.</p>
                                        <p class="card-text fs-1 text-end"><i class="fa-solid fa-up-right-from-square fs-6"></i></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="./student_dashboard.php" style="text-decoration: none">
                                <div class="card special-effects border-0 text-white bg-secondary h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Graduated Students</h5>
                                        <p class="light pt-1">Browse records of past students and verify graduation information.</p>
                                        <p class="card-text fs-1 text-end"><i class="fa-solid fa-up-right-from-square fs-6"></i></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="./staff_dashboard.php" style="text-decoration: none">
                                <div class="card special-effects border-0 text-white bg-dark h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Staff</h5>
                                        <p class="light pt-1">Manage staff profiles, roles, and individual details.</p>
                                        <p class="card-text fs-1 text-end"><i class="fa-solid fa-up-right-from-square fs-6"></i></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="" style="text-decoration: none">
                                <div class="card special-effects border-0 text-white bg-danger h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Payments</h5>
                                        <p class="light pt-1">Review student payments and manage financial records.</p>
                                        <p class="card-text fs-1 text-end"><i class="fa-solid fa-up-right-from-square fs-6"></i></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="" style="text-decoration: none">
                                <div class="card special-effects border-0 text-white bg-warning h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Files</h5>
                                        <p class="light pt-1">Upload, organize, and access important brand documents.</p>
                                        <p class="card-text fs-1 text-end"><i class="fa-solid fa-up-right-from-square fs-6"></i></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="script.js" ></script>
</body>
</html>