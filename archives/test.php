<?php


include('connect.php');
if($_GET['id']) {
    session_start();
    $student_id = $_GET['id'];

    $get_details = "SELECT * FROM `registration_tb` WHERE `id` = '$student_id'";

    $send_query = mysqli_query($connect, $get_details);

    $student_data = mysqli_fetch_assoc($send_query);

    // print_r($student_data);
} else {
    echo "No Student ID Selected";
};

$username =  $_SESSION['username'] ?? 'Guest';

$update_id = $update_first_name = "";
if(isset($_POST['update'])){
    $update_id = $_POST['update_id'];
    $update_first_name = $_POST['update_first_name'];

    $update_query = "UPDATE `registration_tb` SET `first_name`='$update_first_name' WHERE `id` = '$update_id'";

    $send_update_query = mysqli_query($connect, $update_query);

    if($update_query){
        header("Location: student_details.php?id=$update_id");
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?php echo $student_data['first_name'] . ' ' . $student_data['surname'];?> Details</title>
</head>
<body>
    <h1>Update Details</h1>
    <div>
        <form action="test.php" method="POST">
            <input type="text" hidden id="update_id" name="update_id" value="<?php echo $student_data['id'];?>">
            <label for="update_first_name">First Name: </label>
            <input type="text" name="update_first_name" id="update_first_name" value="<?php echo $student_data['first_name']; ?>">
            <input type="submit" value="update" name="update">
        </form>
    </div>
</body>
</html>