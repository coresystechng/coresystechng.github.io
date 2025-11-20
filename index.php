<?php

session_start();
include('connect.php');

$username = $password = "";

$error = array ('username' => '', 'password' => '', 'general' => '');

if (isset($_POST['submit'])) {
    
    $_SESSION['username'] = $_POST['username'];
    
    // Check username
    if (empty($_POST['username'])) {
        $error['username'] = 'Username is required';
    }   else {
        $username = $_POST['username'];
        // only allow alphanumeric characters and underscores
        if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
            $error['username'] = 'Username can only contain letters, numbers, and underscores.';
        }
    }

    // Check password
    if (empty($_POST['password'])) {
        $error['password'] = 'Password is required';
    }   else {
        $password = $_POST['password'];
        // Minimum length of 8 characters
        if (strlen($password) < 8) {
            $error['password'] = 'Password must be at least 8 characters long.';
        }
    }

        // if any errors, do not insert
    if (array_filter($error)) {
        // errors exist — they will be shown in the form below
    } else {
        // sanitize and fetch
        $username = mysqli_real_escape_string($connect, $_POST['username']);
        $password_raw = $_POST['password']; // raw password for verification

        // create sql
        $login_query = "SELECT * FROM users_tb WHERE username = '$username' ";
        $login_query_run = mysqli_query($connect, $login_query);

        // check if user exists
        if (mysqli_num_rows($login_query_run) > 0) {
            $row = mysqli_fetch_assoc($login_query_run);
            $hashed = $row['password']; // should be a hash from registration

            // verify password by compaing to hashed password
            if (password_verify($password_raw, $hashed)) {
                // success — set session only after password is verified
                $_SESSION['id'] = $row['id'];
                $_SESSION['auth'] = true;
                $_SESSION['authuser'] = [
                    'username' => $row['username'],
                    'password' => $row['password']
                ];
                $_SESSION['success'] = "Login successful. Welcome back, " . $row['username'] . ".";
                header('Location: admin_dashboard.php');
                exit;
            } else {
                $error['general'] = 'Invalid credentials.';
            }
        } else {
            $error['general'] = 'No user found with that username.';
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | CORE-TECH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <style>
        *{
            overflow-y: hidden;
        }

        .log {
            text-decoration: none;
            color: #134074;
        }

        .light {
            font-family: cera_light !important;
            font-weight: 500 !important;
        }

        .log:hover {
            text-decoration: underline;
            color: #134074;
        }

        /* :: is used to style placeholders and other attributes */
        ::placeholder {
            font-weight: 600 !important;
            font-size: smaller;
            font-family: cera_light !important;
            font-style: italic !important;
        }
    </style>
</head>

<body class="bg-light">

    <main style="font-family: cera_light !important ;">
        <div class="container mt-5 py-5 justify-content-center align-items-center d-flex">
            <div class="card p-5 border-0 shadow-lg" style="width: 35rem;">
                <div class="text-center">
                    <img loading="lazy" src="img/logo.png" class="w-50" alt="">
                </div>
                <h2 class="light mb-4 fs-5 text-center" >Login to your account</h2>

                <!-- display error alerts -->
                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (array_filter($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>There were some problems:</strong>
                        <ul class="mb-0">
                        <?php foreach ($error as $msg): if ($msg): ?>
                            <li><?php echo htmlspecialchars($msg); ?></li>
                        <?php endif; endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <div>
                        <label class="form-label">Username:</label>
                        <input class="form-control" type="text" value="<?php echo htmlspecialchars($username); ?>" name="username" placeholder="admin@coresystech.ng"><br>
                    </div>
                    <div>
                        <label class="form-label">Password:</label>
                        <input class="form-control" type="password" value="<?php echo htmlspecialchars($password); ?>" name="password" placeholder="********"><br>
                    </div>
                    <div class="text-center">
                        <input type="submit" name="submit" class="btn btn-primary w-100" value="Login">
                    </div>
                    <div class="text-center mt-4">
                        <!-- <p>Don't have an account? <a href="users_tbister.php" class="log">Register</a></p> -->
                        &copy; 2025 <a href="https://coresystech.ng" target="_blank" class="log">CORE-TECH</a>. <span class="light" >All Rights Reserved.</span>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>