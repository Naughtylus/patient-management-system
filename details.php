<?php

include('config/db_connect.php');

// Delete POST
if (isset($_POST['delete'])) {
  // Protects from malicious code
  $id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_delete']);

  $sql = "DELETE FROM patients WHERE id = $id_to_delete";

  if (mysqli_query($conn, $sql)) {
    // Success
    header('Location: index.php');
  } else {
    echo "Query error: " . mysqli_error($conn);
  }
}

// Check GET request ID param
if (isset($_GET['id'])) {
  $id = mysqli_real_escape_string($conn, $_GET['id']);
  // Make sql
  $sql = "SELECT * FROM patients WHERE id = $id";

  // Get the query result
  $result = mysqli_query($conn, $sql);

  // Fetch Result in array format
  $patient = mysqli_fetch_assoc($result);

  // Free memory
  mysqli_free_result($result);
  // Close connection
  mysqli_close($conn);
}

?>

<!DOCTYPE html>

<body>
  <?php include('templates/header.php'); ?>

  <?php if ($_SESSION['username'] == 'admin' && $_SESSION['loggedIn'] == true) { ?>
    <div class="container center">
      <?php if ($patient) : ?>
        <h3>Patient Details:</h4>
          <h5>Patient ID: <?php echo htmlspecialchars($patient['id']); ?></h5>
          <p>Name: <?php echo htmlspecialchars($patient['name']); ?></p>
          <p>Gender: <?php echo htmlspecialchars($patient['gender']); ?></p>
          <p>Date Of Birth: <?php echo htmlspecialchars($patient['dob']); ?></p>
          <p>Age: <?php echo htmlspecialchars($patient['age']); ?></p>
          <p>Phone Number: <?php echo htmlspecialchars($patient['phone']); ?></p>
          <p>Email: <?php echo htmlspecialchars($patient['email']); ?></p>
          <p>Other Information: <br><?php echo htmlspecialchars($patient['others']); ?></p>

          <ul id="nav-mobile" class="hide-on-small-and-down">
            <a href="edit.php?id=<?php echo $patient['id'] ?>" class="btn brand">Edit Patient</a>
          </ul>
          <form action="details.php" method="POST">
            <input type="hidden" name="id_to_delete" value="<?php echo $patient['id'] ?>">
            <input type="submit" name="delete" value="Delete" class="btn brand z-depth-0">
          </form>

        <?php else : ?>
          <h5>No such patient exsists !</h5>
        <?php endif; ?>
    </div>
  <?php } else { ?>
    <!-- If not logged in -->
    <h4 class="center grey-text">Please Log In to view Patient details</h4>
  <?php } ?>

  <?php include('templates/footer.php'); ?>

</body>

</html>