<?php

include('config/db_connect.php');

$name = $gender = $dob = $age = $phone = $email = $others = "";
$errors = array('name' => "", 'gender' => "", 'dob' => "", 'phone' => "", 'email' => "", 'others' => "");

function isRealDate($date)
{
  if (false === strtotime($date)) {
    return false;
  }
  list($year, $month, $day) = explode('-', $date);
  return checkdate($month, $day, $year);
}

if (isset($_POST['submit'])) {
  // NAME
  if (empty($_POST['name'])) {
    $errors['name'] = "Name is required <br>";
  } else {
    $name = $_POST['name'];
    // Validate name
    if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
      $errors['name'] =  "Name must be alphabets and spaces only.";
    } else {
      // Reset no error
      $errors['name'] =  "";
    }
  }

  // GENDER
  if (empty($_POST['gender'])) {
    $errors['gender'] = "A gender is required <br>";
  } else {
    $gender = $_POST['gender'];
    // Reset no error
    $errors['gender'] =  "";
  }

  // Date oF birth
  if (empty($_POST['dob'])) {
    $errors['dob'] = "A date of birth is required <br>";
  } else {
    $dob = $_POST['dob'];
    // Validate dob
    if (!isRealDate($dob)) {
      $errors['dob'] = "Date of birth is not valid.";
    } else {
      // Reset no error
      $errors['dob'] =  "";
    }
  }

  // Age
  if (empty($_POST['age'])) {
    $errors['age'] = "Age is required <br>";
  } else {
    $age = $_POST['age'];
    // Validate age
    if ($age < 16 || $age > 80) {
      $errors['age'] = "Please enter age between 16 and 80.";
    } else {
      // Reset no error
      $errors['age'] =  "";
    }
  }

  // Phone Number
  if (empty($_POST['phone'])) {
    $errors['phone'] = "A Phone Number is required <br>";
  } else {
    $phone = $_POST['phone'];
    // Validate phone
    if (!preg_match('/^[+][6][0][0-9]{9,10}$/', $phone)) {
      $errors['phone'] =  "Phone number must start with +60 and followed by 9 or 10 digits";
    } else {
      // Reset no error
      $errors['phone'] =  "";
    }
  }

  // EMAIL
  if (empty($_POST['email'])) {
    $errors['email'] = "An email is required <br>";
  } else {
    $email = $_POST['email'];
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = "Email must be a valid email address.";
    } else {
      // Check whether emails ends with the only two allowed domain
      $domain = explode('@', $email)[1];
      if ($domain == 'taylors.edu.my' || $domain == 'sd.taylors.edu.my') {
        // Valid
        $errors['email'] =  "";
      } else {
        // If email does not end with @taylors.edu.my or @sd.taylors.edu.my
        $errors['email'] = "Email must only end with @taylors.edu.my or @sd.taylors.edu.my";
      }
    }
  }

  if (empty($_POST['others'])) {
    $errors['others'] =  "Other information is required <br>";
  } else {
    $others = $_POST['others'];
  }

  if (array_filter($errors)) {
  } else {
    // mysqli_real_escape_string protects from malicious SQL codes.
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $others = mysqli_real_escape_string($conn, $_POST['others']);

    // Create sql
    $sql = "INSERT INTO patients(name, gender, dob, age, phone, email, others) VALUES('$name', '$gender', '$dob', '$age', '$phone', '$email', '$others')";

    // Save to db and check
    if (mysqli_query($conn, $sql)) {
      // Success
      // Returns user to the index.php page.
      header('Location: index.php');
    } else {
      // error
      echo 'Query Error: ' . mysqli_error($conn);
    }
  }
}
?>

<!DOCTYPE html>
<html>
<?php include('templates/header.php'); ?>
<?php if ($_SESSION['username'] == 'admin' && $_SESSION['loggedIn'] == true) { ?>
  <section class="container grey-text">
    <h4 class="center">Add patient</h4>
    <form action="add.php" class="white" method="POST">
      <!-- NAME Input -->
      <label>Patient Name:</label>
      <input type="text" name="name" value="<?php echo $name ?>">
      <!-- Error PHP -->
      <div class="red-text">
        <?php
        echo $errors['name'];
        ?>
      </div>
      <!-- GENDER SELECT -->
      <label>Gender:</label>
      <p>
        <label>
          <input class="with-gap" name="gender" type="radio" value="Male" checked="checked" />
          <span>Male</span>
        </label>
      </p>
      <p>
        <label>
          <input class="with-gap" name="gender" type="radio" value="Female" />
          <span>Female</span>
        </label>
      </p>
      <!-- Error PHP -->
      <div class="red-text">
        <?php
        echo $errors['gender'];
        ?>
      </div>

      <!-- DOB date input -->
      <label for="dob">Date Of Birth:</label>
      <input type="date" name="dob" value="<?php echo $dob ?>">
      <!-- Error PHP -->
      <div class="red-text">
        <?php
        echo $errors['dob'];
        ?>
      </div>

      <!-- Age -->
      <label>Patient Age:</label>
      <input type="text" name="age" value="<?php echo $age ?>">
      <!-- Error PHP -->
      <div class="red-text">
        <?php
        echo $errors['age'];
        ?>
      </div>
      <!-- Phone Number Input -->
      <label>Patient Phone Number:</label>
      <input type="text" name="phone" value="<?php echo $phone ?>">
      <!-- Error PHP -->
      <div class="red-text">
        <?php
        echo $errors['phone'];
        ?>
      </div>
      <!-- Email Input -->
      <label>Patient Email:</label>
      <input type="text" name="email" value="<?php echo $email ?>">
      <!-- Error PHP -->
      <div class="red-text">
        <?php
        echo $errors['email'];
        ?>
      </div>
      <!-- Others Input -->
      <label>Other Information:</label>
      <textarea name="others" class="materialize-textarea"><?php echo $others ?></textarea>
      <!-- Error PHP -->
      <div class="red-text">
        <?php
        echo $errors['others'];
        ?>
      </div>

      <!-- SUBMIT BUTTON -->
      <div class="center">
        <input type="submit" name="submit" value="submit" class="btn brand z-depth-0">
      </div>
    </form>
  </section>
<?php } else { ?>
  <h4 class="center grey-text">Please Log In to add Patients</h4>

<?php } ?>

<?php include('templates/footer.php'); ?>

</html>