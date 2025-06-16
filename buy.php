<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $product = $_POST['product'];
  $name = $_POST['name'];
  $contact = $_POST['contact'];
  $email = $_POST['email'];
  $address = $_POST['address'];
  $payment = $_POST['payment'];

  // You can replace this with database logic or mail functionality
  echo "<h2>Thank you, $name!</h2>";
  echo "<p>Your order for <strong>$product</strong> has been received.</p>";
  echo "<p>We’ll contact you shortly at $contact or $email.</p>";
}
?>
