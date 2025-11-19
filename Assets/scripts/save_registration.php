<?php
// Database connection
include 'connect.php';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // handle file upload (passport)
    if (isset($_FILES['image_name']) && $_FILES['image_name']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['image_name']['name'];
        $file_tmp = $_FILES['image_name']['tmp_name'];
        $file_size = $_FILES['image_name']['size'];
        $file_type = $_FILES['image_name']['type'];
    }

    // Sanitize and validate input fields
    $surname = mysqli_real_escape_string($connect, $_POST['surname']);
    $firstName = mysqli_real_escape_string($connect, $_POST['first_name']);
    $otherNames = mysqli_real_escape_string($connect, $_POST['other_names']);
    $gender = mysqli_real_escape_string($connect, $_POST['gender']);
    $dob = mysqli_real_escape_string($connect, $_POST['dob']);
    $maritalStatus = mysqli_real_escape_string($connect, $_POST['marital_status']);
    $address = mysqli_real_escape_string($connect, $_POST['home_address']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $state = mysqli_real_escape_string($connect, $_POST['state_of_origin']);
    $lga = mysqli_real_escape_string($connect, $_POST['lga']);
    $course = mysqli_real_escape_string($connect, $_POST['course_of_study']);
    $session = mysqli_real_escape_string($connect, $_POST['session']);
    $availability = isset($_POST['days']) ? implode(', ', $_POST['days']) : '';
    $nok_name = mysqli_real_escape_string($connect, $_POST['nok_name']);
    $nok_tel_no = mysqli_real_escape_string($connect, $_POST['nok_tel_no']);
    $nok_relationship = mysqli_real_escape_string($connect, $_POST['nok_relationship']);
    $image_name = $_FILES['image_name']['name'];

    // print_r($_POST);

    // echo $dob . ' ' . $lga;
    // print_r($availability);

    $sql = "INSERT INTO `registration_tb`(`surname`, `first_name`, `other_names`, `gender`, `date_of_birth`, `marital_status`, `home_address`, `phone`, `email`, `state_of_origin`, `lga`, `course_of_study`, `session`, `days_available`, `nok_name`, `nok_tel_no`, `nok_relationship`, `image_name`) VALUES ('$surname','$firstName','$otherNames','$gender','$dob','$maritalStatus','$address','$phone','$email','$state','$lga','$course','$session','$availability','$nok_name','$nok_tel_no','$nok_relationship','$image_name')";

    $send_query = mysqli_query($connect, $sql);

    // set target directory
    $target_dir = 'uploads/';

    // get temp file location
    $tmp_address = $_FILES['image_name']['tmp_name'];

    // upload image to server
    $upload_img = move_uploaded_file($tmp_address, "$target_dir/$image_name");

    if($send_query){
      session_start();
      $_SESSION['email'] = $email;
      $_SESSION['name'] = $firstName . ' '. $surname;
      $_SESSION['course'] = $course;
      header("Location: success.php?email=$email");
      include_once 'send_registration_mail.php';
    } else {
      echo "Could Not Complete Registration" . mysqli_error($connect);
    }

  }
    // Close database connection
    $connect->close();
?>