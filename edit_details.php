<?php

include('connect.php');

if ($_GET['id']) {
    session_start();
    $student_id = $_GET['id'];
    
    $get_details = "SELECT * FROM `registration_tb` WHERE `id` = '$student_id' ";

    $send_query = mysqli_query($connect, $get_details);

    $student_data = mysqli_fetch_assoc($send_query);

} else {
    echo "No Student ID Selected";
};

$username =  $_SESSION['username'] ?? 'Guest';

$update_id = $first_name = $surname = $email = $phone = $home_address = "";


if (isset($_POST['update'])) {
    $update_id = $_POST['update_id'];
    
    $first_name = $_POST['first_name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $home_address = $_POST['home_address'];

    // sql to update student details using prepared statements
    $update_sql = "UPDATE registration_tb SET 
        first_name = ?,
        surname = ?,
        email = ?,
        phone = ?,
        home_address = ?
        WHERE id = ?";

    // prepare and bind
    $stmt = mysqli_prepare($connect, $update_sql);
    // bind parameters "sssssi" means string, string, string, string, string, integer
    mysqli_stmt_bind_param($stmt, "sssssi", $first_name, $surname, $email, $phone, $home_address, $update_id);
    // execute the prepared statement
    $send_update_query = mysqli_stmt_execute($stmt);

    if ($send_update_query) {
        $_SESSION['success'] = "Details edited successfully";
        // redirect to student details page after update
        header("Location: student_details.php?id=$update_id");
        exit();
    }
    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?php echo $student_data['surname'];?> Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <style>
        *{
            overflow-y: hidden;
            overflow-x: hidden;
            max-height: 100vh;
        }
        /* :: is used to style placeholders and other attributes */
        input, textarea {
            font-weight: 600 !important;
            font-size: smaller;
            font-family: cera_light !important;
            font-style: italic !important;
            color: #777 !important;
        }
    </style>
</head>

<body class="bg-light">

    <main class="container">
        <section class="justify-content-center mt-5 pt-3">
            <div class="card p-5 border-0 shadow-sm">
                <!-- submit to same page so the PHP above runs -->
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    
                    <!-- retains the unique id -->
                    <input type="text" hidden id="update_id" name="update_id" value="<?php echo $student_data['id'];?>">

                    <div class="mb-5 text-center">
                        <h1 class="blue">Edit student details</h1>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="<?php echo $student_data['first_name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Surname</label>
                                <input type="text" name="surname" class="form-control" value="<?php echo $student_data['surname']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="emailInput" class="form-label">Email Address</label>
                                <input id="emailInput" type="email" value="<?php echo $student_data['email']; ?>" class="form-control" name="email" aria-describedby="emailHelp">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo $student_data['phone']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Home Address (optional)</label>
                        <textarea name="home_address" class="form-control"><?php echo $student_data['home_address']; ?></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="update" class="btn btn-primary mt-3">Update</button>
                    </div>

                </form>
            </div>
        </section>
    </main>

</html>
