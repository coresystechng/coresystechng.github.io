<?php

session_start();
include('connect.php');

$username = $email = $password = $confirm_password = "";

$error = array ('username' => '', 'email' => '', 'password' => '', 'confirm_password' => '');

if (isset($_POST['submit'])) {
    
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

    // check email
    if (empty($_POST['email'])) {
        $error['email'] = "An email is required";
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error['email'] = "Email must be a valid email home_address";
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

    // Check confirm password
    if (empty($_POST['confirm_password'])) {
        $error['confirm_password'] = 'Confirmation password is required';
    }   else {
        $confirm_password = $_POST['confirm_password'];
    }

    // if any errors, do not insert
    if (array_filter($error)) {
        // errors exist — they will be shown in the form below
    } else {
        // sanitize and insert
        $username = mysqli_real_escape_string($connect, $_POST['username']);
        $email = mysqli_real_escape_string($connect, $_POST['email']);
        $password = mysqli_real_escape_string($connect, $_POST['password']);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // hash password before storing

        // check if email already exists
        $check_email_query = "SELECT email FROM users_tb WHERE email = '$email' ";
        $check_email_query_run = mysqli_query($connect, $check_email_query);
        
        if(mysqli_num_rows($check_email_query_run) > 0) {
            $error['email'] = "Email already registered.";
            // header('Location: users_tbister.php');
        }

        // check if username already exists
        $check_username_query = "SELECT username FROM users_tb WHERE username = '$username' ";
        $check_username_query_run = mysqli_query($connect, $check_username_query);

        if(mysqli_num_rows($check_username_query_run) > 0) {
            $error['username'] = "Username already taken.";
            // header('Location: users_tbister.php');
        }

        // check if passwords match
        if ($password == $confirm_password) {
            // insert values
            $sql = "INSERT INTO users_tb (username, email, password)
                VALUES ('$username', '$email', '$hashed_password')";

            // save to the database and check
            if (mysqli_query($connect, $sql)) {
                // success message for login page
                $_SESSION['success'] = "Registration successful. Please log in.";
                header('Location: index.php');
                exit;
            } else {
                $error['general'] = 'Query error: ' . mysqli_error($connect);
            }
        } else {
            $error['confirm_password'] = 'Passwords do not match.';
        }

    }

}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register | CORE-TECH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <style>
        .log {
            text-decoration: none;
            color: #134074;
        }

        .log:hover {
            text-decoration: underline;
            color: #134074;
        }
    </style>
</head>

<body class="bg-light">
    <header>
        <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-white">
            <div class="container">
                <a class="navbar-brand" href="https://coresystech.ng/"><img src="img/logo.png" class="brand" alt=""></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <div class="text-center d-lg-none d-flex justify-content-center">
                            <li class="nav-item" style="width: 12rem; list-style: none;">
                                <a class="nav-link btn btn-secondary" href="https://coresystech.ng/" target="_blank">Back To Home</a>
                            </li>
                        </div>
                        <div class="d-none d-lg-block">
                            <li class="nav-item">
                                <a class="nav-link btn btn-secondary" href="https://coresystech.ng/" target="_blank">Back To Home</a>
                            </li>
                        </div>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main style="font-family: cera_light !important ;">
        <div class="container mt-5 py-5 justify-content-center align-items-center d-flex">
            <div class="card p-5 mt-5 border-0 shadow-lg" style="width: 35rem;">
                <h2 class="blue mb-4 text-center" >Admin Register</h2>
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
                        <input class="form-control" type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" placeholder="Enter username">
                        <br>
                    </div>
                    <div>
                        <label class="form-label">Email home_address:</label>
                        <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter email home_address">
                        <br>
                    </div>
                    <div>
                        <label class="form-label">Password:</label>
                        <input class="form-control" type="password" name="password" value="<?php echo htmlspecialchars($password); ?>" placeholder="Enter password">
                        <br>
                    </div>
                    <div>
                        <label class="form-label">Confirm Password:</label>
                        <input class="form-control" type="password" name="confirm_password" value="<?php echo htmlspecialchars($confirm_password); ?>" placeholder="Confirm password">
                        <br>
                    </div>
                    <div class="text-center ">
                        <input type="submit" name="submit" class="btn btn-primary w-100" value="Register">
                    </div>
                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="index.php" class="log">login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include('templates/footer.php'); ?>

</html>