<?php
// Connect to database
$conn = mysqli_connect('localhost', 'onn', 'test123', 'patient_management_system');

// Check connection
if (!$conn) {
  echo "Connection error: " . mysqli_connect_error();
}
?>