<?php

include 'connect.php';

session_start();

if(!$_SESSION['username']) {
  header ('Location: login.php');
} else {
  $username = $_SESSION['username'];
}

// Get contact ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch contact details
$query = "SELECT * FROM contact_tb WHERE id = $id LIMIT 1";
$result = mysqli_query($connect, $query);
$contact = mysqli_fetch_assoc($result);
// print_r($contact);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Contact</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4" data-aos="fade-down">Contact Details</h2>
    <?php if ($contact): ?>
        <div class="card" data-aos="fade-up">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) ?></h5>
                <p><strong>Email:</strong> <?= htmlspecialchars($contact['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($contact['phone']) ?></p>
                <p><strong>Subject:</strong> <?= htmlspecialchars($contact['subject']) ?></p>
                <p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($contact['message'])) ?></p>
                <p><strong>Date:</strong> <?= isset($contact['date_created']) ? htmlspecialchars($contact['date_created']) : '' ?></p>
                <a href="contacts.php" class="btn btn-secondary mt-3">Back to List</a>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger" data-aos="fade-up">
            Contact not found.
        </div>
        <a href="contacts.php" class="btn btn-secondary mt-3">Back to List</a>
    <?php endif; ?>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
</script>
</body>
</html>