<?php

include('connect.php');

$email = $firstname = $lastname = $dateofbirth = $gender = $maritalstatus = $phone = $address = $image_name = '';

$errors = array(
    'email' => '', 'firstname' => '', 'lastname' => '', 'dateofbirth' => '',
    'gender' => '', 'maritalstatus' => '', 'phone' => '', 'address' => '', 'image_name' => ''
);

if (isset($_POST['submit'])) {

    // check email
    if (empty($_POST['email'])) {
        $errors['email'] = "An email is required";
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email must be a valid email address";
        }
    }

    // check first name
    if (empty($_POST['firstname'])) {
        $errors['firstname'] = "First name is required";
    } else {
        $firstname = $_POST['firstname'];
        if (!preg_match('/^[a-zA-Z\s.]+$/', $firstname)) { // ensure there are no numbers in the input field
            $errors['firstname'] = "First name must be letters and spaces only";
        }
    }

    // check last name
    if (empty($_POST['lastname'])) {
        $errors['lastname'] = "Last name is required";
    } else {
        $lastname = $_POST['lastname'];
        if (!preg_match('/^[a-zA-Z\s.]+$/', $lastname)) { // ensure there are no numbers in the input field
            $errors['lastname'] = "Last name must be letters and spaces only";
        }
    }

    // check number
    if (empty($_POST['phone'])) {
        $errors['phone'] = "Phone number is required";
    } else {
        $number = $_POST['phone'];

    }

    // check date of birth
    if (empty($_POST['dateofbirth'])) {
        $errors['dateofbirth'] = "Date of birth is required";
    } else {
        $dateofbirth = $_POST['dateofbirth'];
        // optional: validate format YYYY-MM-DD
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateofbirth)) { // ensure the format is dd/mm/yy
            $errors['dateofbirth'] = "Invalid date format";
        }
    }

    // check marital status
    if (empty($_POST['marital_status'])) {
        $errors['maritalstatus'] = "Marital status is required";
    } else {
        $maritalstatus = $_POST['marital_status'];
    }

    // check gender
    if (empty($_POST['gender'])) {
        $errors['gender'] = "Select a gender";
    } else {
        $gender = $_POST['gender'];
        if (!in_array($gender, ['male', 'female'])) {
            $errors['gender'] = "Invalid selection for gender";
        }
    }

    // handle file upload (passport)
    if (isset($_FILES['image_name']) && $_FILES['image_name']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['image_name']['name'];
        $file_tmp = $_FILES['image_name']['tmp_name'];
        $file_size = $_FILES['image_name']['size'];
        $file_type = $_FILES['image_name']['type'];

        // validate file type (optional)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file_type, $allowed_types)) {
            $errors['image_name'] = "Only JPG, PNG, and GIF files are allowed";
        }

        // validate file size (optional)
        if ($file_size > 2 * 1024 * 1024) { // limit to 2MB
            $errors['image_name'] = "File size must be less than 2MB";
        }
    } else {
        $errors['image_name'] = "Passport image is required";
    }

    // if any errors, do not insert
    if (array_filter($errors)) {
        // errors exist — they will be shown in the form below
    } else {
        // sanitize and insert
        $email = mysqli_real_escape_string($connect, $_POST['email']);
        $firstname = mysqli_real_escape_string($connect, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($connect, $_POST['lastname']);
        $phone = mysqli_real_escape_string($connect, $_POST['phone']);
        $dateofbirth = mysqli_real_escape_string($connect, $_POST['dateofbirth']);
        $gender = mysqli_real_escape_string($connect, $_POST['gender']);
        $maritalstatus = mysqli_real_escape_string($connect, $_POST['marital_status']);
        $address = mysqli_real_escape_string($connect, $_POST['address']);
        $image_name = $_FILES['image_name']['name'];

        // create sql
        $sql = "INSERT INTO registration_tb (email, first_name, surname, phone, date_of_birth, gender, marital_status, address, image_name) VALUES ('$email', '$firstname', '$lastname', '$phone', '$dateofbirth', '$gender', '$maritalstatus', '$address', '$image_name')";

        $send_query = mysqli_query($connect, $sql);

        // set target directory
        $target_dir = 'uploads';

        // get temp file location
        $tmp_address = $_FILES['image_name']['tmp_name'];

        // upload image to server
        $upload_img = move_uploaded_file($tmp_address, "$target_dir/$image_name");

        if ($upload_img) {
            header('Location: success.php');
            exit;
        } else {
            $errors['general'] = 'Query error ' . mysqli_error($connect);
        }   
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include('templates/header.php'); ?>

    <main class="container">
        <section class="justify-content-center mt-5 pt-5">
            <div class="card p-5 my-5 border-0 shadow-lg">
                <!-- submit to same page so the PHP above runs -->
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-5 text-center">
                        <h1 class="blue">Registration Form</h1>
                    </div>

                    <?php if (array_filter($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>There were some problems:</strong>
                            <ul class="mb-0">
                            <?php foreach ($errors as $msg): if ($msg): ?>
                                <li><?php echo htmlspecialchars($msg); ?></li>
                            <?php endif; endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="firstname" class="form-control" value="<?php echo htmlspecialchars($firstname); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="lastname" class="form-control" value="<?php echo htmlspecialchars($lastname); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="emailInput" class="form-label">Email address</label>
                                <input id="emailInput" type="email" value="<?php echo htmlspecialchars($email); ?>" class="form-control" name="email" aria-describedby="emailHelp">
                                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label name="dateofbirth" class="form-label">Date of Birth</label>
                                <!-- added name so PHP can read it -->
                                <input type="date" name="dateofbirth" class="form-control" value="<?php echo htmlspecialchars($dateofbirth); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="maritalStatus" class="form-label">Marital Status</label>
                                <!-- keep the visible select but match the php key via name -->
                                <select class="form-select" id="maritalStatus" name="marital_status">
                                    <option value="" disabled <?php if ($maritalstatus === '') echo 'selected'; ?>>-- Select your status --</option>
                                    <option value="single" <?php if ($maritalstatus === 'single') echo 'selected'; ?>>Single</option>
                                    <option value="married" <?php if ($maritalstatus === 'married') echo 'selected'; ?>>Married</option>
                                    <option value="divorced" <?php if ($maritalstatus === 'divorced') echo 'selected'; ?>>Divorced</option>
                                    <option value="widowed" <?php if ($maritalstatus === 'widowed') echo 'selected'; ?>>Widowed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">gender</label>
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="male" name="gender" value="male" <?php if ($gender === 'male') echo 'checked'; ?>>
                                        <label class="form-check-label" for="male">Male</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="female" name="gender" value="female" <?php if ($gender === 'female') echo 'checked'; ?>>
                                        <label class="form-check-label" for="female">Female</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="passport" class="mb-2">Passport</label><br>
                                <input class="form-control" type="file" name="image_name" id="passport" accept="image/*">
                                <?php if ($image_name): ?>
                                    <small class="text-success">Uploaded file: <?php echo htmlspecialchars($image_name); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address (optional)</label>
                        <textarea name="address" class="form-control"><?php echo htmlspecialchars($address); ?></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-primary mt-3">Submit</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

<?php include('templates/footer.php'); ?>

</html>
