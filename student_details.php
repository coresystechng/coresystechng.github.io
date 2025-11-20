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

    include('connect.php');

    $username =  $_SESSION['username'] ?? 'Guest';

    // initialize super admin flag
    $is_super = 0;

    // If we have a logged-in admin, fetch their is_super field (1 or 0)
    if ($username) {
        $stmt = $connect->prepare("SELECT is_super FROM users_tb WHERE username = ? LIMIT 1");
        if ($stmt) { // check prepare success
            $stmt->bind_param("s", $username);
            // execute the query
            $stmt->execute();
            // bind result variables
            $stmt->bind_result($is_super_db);
            if ($stmt->fetch()) {
                // Ensure it's an int 0/1
                $is_super = (int)$is_super_db;
            }
            $stmt->close();
        } else {
            error_log("Prepare failed (users_tb lookup): " . $connect->error);
        }
    }

    // delete user details
    if (isset($_POST['delete'])) {

        $id_to_delete = mysqli_real_escape_string($connect, $_POST['id_to_delete']);
        $sql = "DELETE FROM registration_tb WHERE id = $id_to_delete";

        if (mysqli_query($connect, $sql)) {
            // success
            header('Location: student_dashboard.php');
        } {
            // failure
            echo 'query error: ' . mysqli_error($connect);
        }
    }

    // check GET request id parameter
    if(isset($_GET['id'])) {
        $id = mysqli_real_escape_string($connect, $_GET['id']); // ensure no injection of malicious script from the unique id

        // make sql
        $sql = "SELECT * FROM registration_tb WHERE id = $id";

        // get the query result
        $result = mysqli_query($connect, $sql);

        // fetch the result in array format
        $row = mysqli_fetch_assoc($result);

        // free result memory
        mysqli_free_result($result);
        
        // close connection
        mysqli_close($connect);

    }

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
        .small {
            color: #0e2a46ff !important;
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
        
        .small-5 {
            font-size: 15px;
        }

        /* Safe print-only CSS for #card_1 */
        @media print {
            @page { size: A4; margin: 12mm; }    /* A4 with small margins */

            /* ensure page starts clean */
            html, body { height: auto; margin: 0; padding: 0; }

            /* hide everything by default (use !important to override other rules) */
            body * { visibility: hidden !important; }

            /* make the card and its children visible and printable */
            #card_1, #card_1 * {
                visibility: visible !important;
                opacity: 1 !important;
                -webkit-transform: none !important;
                        transform: none !important;
            }

            /* ensure the card is in normal flow and fully on the printed page */
            #card_1 {
                position: absolute !important;
                display: block !important;
                float: none !important;
                width: auto !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                left: 0 !important;
                top: 0 !important;
                box-shadow: none !important; /* shadows often don't print well */
                background: #fff !important;  /* white background for readability */
            }

            /* force images/backgrounds to render better in some browsers */
            #card_1 img {
                -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                margin-left: 0 !important;        /* ensure it sticks left */
                max-width: 25% !important;
            }
            #card_1 .align-items-center,
            #card_1 .justify-content-center {
                text-align: left !important;
                justify-content: flex-start !important;
                align-items: flex-start !important;
            }
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
                    <div class="d-flex justify-content-center gap-2 pt-2">
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
                    <a href="./admin_dashboard.php"><img loading="lazy" src="img/core_white.png" class="p-1 my-3 logo" style="width: 8rem !important;" alt="CORE-TECH"></a>
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
                    <li class="reg my-1">
                        <a href="./inquiries.php" class="sidebar-link">
                            <i class="fa-solid fa-circle-info"></i>
                            Inquiries
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
                            <li class="mb-3"><a href="./inquiries.php" class="text-dark" style="text-decoration:none;"><i class="fa-solid fa-circle-info"></i> Inquiries</a></li>
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
                    
                    <div class="mt-5 mb-4 blue">
                        <h1>Student Profile</h1>
                    </div>

                    <?php if($row): ?>

                        <!-- desktop and tab view -->
                        <div class="px-0">
                            <div id="card_1" class="card d-none d-md-block w-100 p-3 mb-5">
                                <div class="card-body">
                                    <!-- <p class="fs-4" >Student Details</p> -->
                                    <div class="row mt-2 px-4 pt-3">
    
                                        <section class="card border-0">
                                            <h2 class="blue mt-2">Personal Details</h2><hr>
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="row mt-2 pb-1">
                                                        <div class="col-md-4 mb-2">
                                                            <span class="blue fs-5">Surname</span>
                                                            <br>
                                                            <p class="fs-4"><?php echo ($row['surname']); ?></p>
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <span class="blue fs-5">First Name</span>
                                                            <br>
                                                            <p class="fs-4"><?php echo ($row['first_name']); ?></p>
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <span class="blue fs-5">Other Names</span>
                                                            <br>
                                                            <p class="fs-4"><?php echo ($row['other_names']); ?></p>
                                                        </div>
                                                    </div>
                                                        <hr>
                                                    <div class="row mt-1 mb-0">
                                                        <div class="col-md-4 mb-2">
                                                            <span class="blue fs-5">Gender</span>
                                                            <br>
                                                            <p class="fs-4"><?php echo ($row['gender']); ?></p>
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <span class="blue fs-5">Date of Birth</span>
                                                            <br>
                                                            <p class="fs-4"><?php echo ($row['date_of_birth']); ?></p>
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <span class="blue fs-5">Marital Status</span>
                                                            <br>
                                                            <p class="fs-4"><?php echo ($row['marital_status']); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-center justify-content-center">
                                                    <?php
                                                        if (!empty($row['image_name'])) {
                                                            $imagePath = $row['image_name'];
                                                            echo '<img loading="lazy" src="https://coresystech.ng/assets/scripts/uploads/' . $imagePath . '" class="w-100">';
                                                        } else {
                                                            
                                                            if ($row['gender'] == 'Male') {
                                                                $imagePath = 'img/default.jpg'; // Path to default image
                                                                echo '<img loading="lazy" src="' . $imagePath . '" class="w-50">';
                                                                # code...
                                                                
                                                            } else {
                                                                $imagePath = 'img/default_f.jpeg'; // Path to default image
                                                                echo '<img loading="lazy" src="' . $imagePath . '" class="w-50">';
                                                                # code...
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <hr>
                                        </section>
                                            
                                        <section class="card border-0 mt-1">
                                            <h2 class="blue mt-2">Contact Information</h2><hr>
                                            <div class="row mt-1 pb-2">
                                                <div class="col-md-9">
                                                    <span class="blue fs-5">Home Address</span>
                                                    <br>
                                                    <p class="fs-4"><?php echo ($row['home_address']); ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="blue fs-5">Phone Number</span>
                                                    <br>
                                                    <p class="fs-4 dark"><a class="dark" href="tel:<?php echo ($row['phone']); ?>"><?php echo ($row['phone']); ?><i class="fa-solid fa-up-right-from-square ps-1 fs-5"></i></a></p>
                                                </div>
                                            </div>
                                                <hr>
                                            <div class="row mt-1 mb-2">
                                                <div class="col-md-6">
                                                    <span class="blue fs-5">Email</span>
                                                    <br>
                                                    <p class="fs-4"><a class="dark" href="mailto:<?php echo ($row['email']); ?>"><?php echo ($row['email']); ?><i class="fa-solid fa-up-right-from-square ps-1 fs-5"></i></a></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="blue fs-5">State of Origin</span>
                                                    <br>
                                                    <p class="fs-4"><?php echo ($row['state_of_origin']); ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="blue fs-5">LGA</span>
                                                    <br>
                                                    <p class="fs-4"><?php echo ($row['lga']); ?></p>
                                                </div>
                                            </div> <hr>
                                        </section>
    
                                        <section class="card border-0 mt-1">
                                            <h2 class="blue mt-2">Course Details</h2><hr>
    
                                            <div class="row mt-1 mb-2">
                                                <div class="col-md-5">
                                                    <span class="blue fs-5">Course of Study</span>
                                                    <br>
                                                    <p class="fs-4">
                                                        <?php
                                                            if ($row['course_of_study'] == 'CIT') {
                                                                echo 'Computer Appreciation & Information Technology';
                                                            } elseif ($row['course_of_study'] == 'GAD') {
                                                                echo 'Graphic Design';
                                                            } elseif ($row['course_of_study'] == 'WFE') {
                                                                echo 'Web Development - Front End';
                                                            } elseif ($row['course_of_study'] == 'WBE') {
                                                                echo 'Web Development - Back End';
                                                            } elseif ($row['course_of_study'] == 'WFS') {
                                                                echo 'Web Development - Full Stack';
                                                            } elseif ($row['course_of_study'] == 'AIB') {
                                                                echo 'Artificial Intelligence - Basics';
                                                            }
                                                        ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="blue fs-5">Session</span>
                                                    <br>
                                                    <p class="fs-4"><?php echo ($row['session']); ?></p>
                                                </div>
                                                <div class="col-md-5">
                                                    <span class="blue fs-5">Days of Availability</span>
                                                    <br>
                                                    <p class="fs-4"><?php echo ($row['days_available']); ?></p>
                                                </div>
                                            </div> <hr>
                                        </section>
    
                                        <section class="card border-0 mt-1">
                                            <h2 class="blue mt-2">Next of Kin Details</h2><hr>
    
                                            <div class="row mt-1 mb-2">
                                                <div class="col-md-6">
                                                    <span class="blue fs-5">Fullname</span>
                                                    <br>
                                                    <p class="fs-4"><?php echo ($row['nok_name']); ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="blue fs-5">Phone Number</span>
                                                    <br>
                                                    <p class="fs-4"><a class="dark" href="tel:<?php echo ($row['nok_tel_no']); ?>"><?php echo ($row['nok_tel_no']); ?><i class="fa-solid fa-up-right-from-square ps-1 fs-5"></i></a></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="blue fs-5">Relationship</span>
                                                    <br>
                                                    <p class="fs-4"><?php echo ($row['nok_relationship']); ?></p>
                                                </div>
                                            </div> <hr>
                                        </section>
                                    </div>
    
                                    <div class="d-flex gap-3 py-4 px-4">
                                        <a href="student_dashboard.php" class="btn btn-primary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    
                                        <a onclick="window.print()" class="btn btn-warning"><i class="fa-solid fa-print"></i> Print</a>
                                        
                                        <?php if ($is_super): ?>
                                            <!-- delete form -->
                                            <form action="student_details.php" method="POST">
                                                <input type="hidden" name="id_to_delete" value="<?php echo $row['id']; ?>">
                                                <button type="submit" name="delete" class="btn btn-danger shadow-none"><i class="fa-solid fa-trash-can"></i> Delete</button>
                                            </form>
                                            
                                            <a class="btn btn-success shadow-none" href="edit_details.php?id=<?php echo $row['id']; ?>"><i class="fa-solid fa-pen-to-square pe-1"></i>Edit</a>
                                        <?php endif; ?>
                                    </div>
    
                                </div>
                            </div>
                        </div>


                        <!-- mobile view -->
                        <div class="card w-100 p-1 mb-5 d-block d-md-none">
                            <div class="card-body">
                                <!-- <p class="fs-4" >Student Details</p> -->

                                <div class="col-md-5 mb-md-0 mb-3">
                                    <?php
                                        if (!empty($row['image_name'])) {
                                            $imagePath = $row['image_name'];
                                            echo '<img loading="lazy" src="https://coresystech.ng/assets/scripts/uploads/' . $imagePath . '" class="w-50">';
                                        } else {
                                            
                                            if ($row['gender'] == 'Male') {
                                                $imagePath = 'img/default.jpg'; // Path to default image
                                                echo '<img loading="lazy" src="' . $imagePath . '" class="w-50">';
                                                # code...
                                                
                                            } else {
                                                $imagePath = 'img/default_f.jpeg'; // Path to default image
                                                echo '<img loading="lazy" src="' . $imagePath . '" class="w-50">';
                                                # code...
                                            }
                                        }
                                    ?>
                                </div>
                                
                                <div class="row mt-2">
                                    <section class="card border-0">
                                        <hr>
                                        <h2 class="blue mt-1">Personal Details</h2><hr>
                                        <div class="row mt-0 pb-4">
                                            <div class="col-md-4 mb-2">
                                                <span class="blue fs-5">Surname</span> <br> <?php echo ($row['surname']); ?>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <span class="blue fs-5">First Name</span> <br> <?php echo ($row['first_name']); ?>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <span class="blue fs-5">Other Names</span> <br> <?php echo ($row['other_names']); ?>
                                            </div>
                                        </div>
                                            <hr>
                                        <div class="row mt-1 mb-4">
                                            <div class="col-md-4 mb-2">
                                                <span class="blue fs-5">Gender</span> <br> <?php echo ($row['gender']); ?>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <span class="blue fs-5">Date of Birth</span> <br> <?php echo ($row['date_of_birth']); ?>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <span class="blue fs-5">Marital Status</span> <br> <?php echo ($row['marital_status']); ?>
                                            </div>
                                        </div> <hr>
                                    </section>
                                        
                                    <section class="card border-0 mt-1">
                                        <h2 class="blue mt-2">Contact Information</h2><hr>
                                        <div class="row mt-1 pb-4">
                                            <div class="col-md-8">
                                                <span class="blue fs-5">Home Address</span>
                                                <br>
                                                <p class="fs-4"><?php echo ($row['home_address']); ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <span class="blue fs-5">Phone Number</span>
                                                <br>
                                                <p class="fs-4 dark"><a class="dark" href="tel:<?php echo ($row['phone']); ?>"><?php echo ($row['phone']); ?><i class="fa-solid fa-up-right-from-square ps-1 fs-5"></i></a></p>
                                            </div>
                                        </div>
                                            <hr>
                                        <div class="row mt-1 mb-4">
                                            <div class="col-md-4">
                                                <span class="blue fs-5">Email</span>
                                                <br>
                                                <p class="fs-4"><a class="dark" href="mailto:<?php echo ($row['email']); ?>"><?php echo ($row['email']); ?><i class="fa-solid fa-up-right-from-square ps-1 fs-5"></i></a></p>
                                            </div>
                                            <div class="col-md-4">
                                                <span class="blue fs-5">State of Origin</span>
                                                <br>
                                                <p class="fs-4"><?php echo ($row['state_of_origin']); ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <span class="blue fs-5">LGA</span>
                                                <br>
                                                <p class="fs-4"><?php echo ($row['lga']); ?></p>
                                            </div>
                                        </div> <hr>
                                    </section>

                                    <section class="card border-0 mt-1">
                                        <h2 class="blue mt-2">Course Details</h2><hr>

                                        <div class="row mt-1 mb-4">
                                            <div class="col-md-4">
                                                <span class="blue fs-5">Course of Study</span>
                                                <br>
                                                <p class="fs-4">
                                                    <?php
                                                        if ($row['course_of_study'] == 'CIT') {
                                                            echo 'Computer Appreciation & Information Technology';
                                                        } elseif ($row['course_of_study'] == 'GAD') {
                                                            echo 'Graphic Design';
                                                        } elseif ($row['course_of_study'] == 'WFE') {
                                                            echo 'Web Development - Front End';
                                                        } elseif ($row['course_of_study'] == 'WBE') {
                                                            echo 'Web Development - Back End';
                                                        } elseif ($row['course_of_study'] == 'WFS') {
                                                            echo 'Web Development - Full Stack';
                                                        } elseif ($row['course_of_study'] == 'AIB') {
                                                            echo 'Artificial Intelligence - Basics';
                                                        }
                                                    ?>
                                                </p>
                                            </div>
                                            <div class="col-md-4">
                                                <span class="blue fs-5">Session</span>
                                                <br>
                                                <p class="fs-4"><?php echo ($row['session']); ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <span class="blue fs-5">Days of Availability</span>
                                                <br>
                                                <p class="fs-4"><?php echo ($row['days_available']); ?></p>
                                            </div>
                                        </div> <hr>
                                    </section>

                                    <section class="card border-0 mt-1">
                                        <h2 class="blue mt-2">Next of Kin Details</h2><hr>

                                        <div class="row mt-1 mb-4">
                                            <div class="col-md-6">
                                                <span class="blue fs-5">Fullname</span>
                                                <br>
                                                <p class="fs-4"><?php echo ($row['nok_name']); ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="blue fs-5">Phone Number</span>
                                                <br>
                                                <p class="fs-4"><a class="dark" href="tel:<?php echo ($row['nok_tel_no']); ?>"><?php echo ($row['nok_tel_no']); ?><i class="fa-solid fa-up-right-from-square ps-1 fs-5"></i></a></p>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="blue fs-5">Relationship</span>
                                                <br>
                                                <p class="fs-4"><?php echo ($row['nok_relationship']); ?></p>
                                            </div>
                                        </div> <hr>
                                    </section>
                                </div>

                                <div class="d-flex gap-3 pt-4">
                                    <div class="row">
                                        <div class="col-12 gap-2 d-flex">
                                            <a href="student_dashboard.php" class="btn btn-primary"><i class="fa-solid fa-arrow-left"></i> Back</a>

                                            <a onclick="window.print()" class="btn btn-warning"><i class="fa-solid fa-print"></i> Print</a>
                                        </div>
                                        <?php if ($is_super): ?>
                                            <div class="col-12 d-flex mt-2 gap-2">
                                                <!-- delete form -->
                                                <form action="student_details.php" method="POST">
                                                    <input type="hidden" name="id_to_delete" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" name="delete" class="btn btn-danger shadow-none"><i class="fa-solid fa-trash-can"></i> Delete</button>
                                                </form>
                                                
                                                <a class="btn btn-success shadow-none" href="edit_details.php?id=<?php echo $row['id']; ?>"><i class="fa-solid fa-pen-to-square pe-1"></i>Edit</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    
                                    
                                </div>

                            </div>
                        </div>

                    <?php else: ?>

                        <h1 class="mb-4">Error 404: User Not Found</h1>
                        <p>The User you are looking for does not exist.</p>
                        <a href="admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>

                    <?php endif; ?>
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