<?php
include 'connect.php';

session_start();

if(!$_SESSION['username']) {
  header ('Location: login.php');
} else {
  $username = $_SESSION['username'];
}

// Fetch all contacts
$query = "SELECT * FROM contact_tb ORDER BY id DESC";
$result = mysqli_query($connect, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Submissions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4" data-aos="fade-down">Contact Submissions</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" data-aos="fade-up">
            <thead class="table-dark">
          <tr>
              <th>#</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Date</th>
              <th>Action</th>
          </tr>
            </thead>
            <tbody>
            <?php if(mysqli_num_rows($result) > 0): ?>
          <?php $i = 1; while($row = mysqli_fetch_assoc($result)): ?>
              <tr data-aos="fade-up" data-aos-delay="<?= $i * 50 ?>">
            <td><?= $i ?></td>
            <td><?= htmlspecialchars($row['first_name']) ?></td>
            <td><?= htmlspecialchars($row['last_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['subject']) ?></td>
            <td><?= htmlspecialchars($row['message']) ?></td>
            <td><?= isset($row['date_created']) ? htmlspecialchars($row['date_created']) : '' ?></td>
            <td>
                <a href="view_contact.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-primary">View</a>
            </td>
              </tr>
          <?php $i++; endwhile; ?>
            <?php else: ?>
          <tr>
              <td colspan="9" class="text-center">No contacts found.</td>
          </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
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