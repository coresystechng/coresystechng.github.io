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
        header("Location: contacts.php");
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

<!-- Simple login form -->
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
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
  <h4>Login</h4>
  <?php if (!empty($error_msg)): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error_msg); ?></p>
  <?php endif; ?>
  <form method="post" action="login.php">
    <label>Username:
      <input type="text" name="username" required>
    </label><br><br>
    <label>Password:
      <input type="password" name="password" required>
    </label><br>
    <button type="submit">Login</button>
  </form>
</body>
</html>