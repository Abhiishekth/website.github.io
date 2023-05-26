<?php
// Start the session
session_start();

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "database");


// Handle user login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
  // Get login data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Check user credentials
  $sql = "SELECT username FROM users WHERE username = '$username' AND password = '$password'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION["username"] = $row["username"];
  } else {
    die("Invalid login credentials.");
  }
}



// Check if the form has been submitted
if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['item_id'])) {
  // Get  form data
  $item_id = $_POST['item_id'];
  $quantity = $_POST['quantity'];
  $username = $_SESSION["username"];

  // Get the other form data
  $receiver = mysqli_real_escape_string($conn, $_POST['receiver']);
  $removal_date = mysqli_real_escape_string($conn, $_POST['removal_date']);
  $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);
  $project = mysqli_real_escape_string($conn, $_POST['project']);

 // Retrieve current stock and user ID from database
 $sql = "SELECT current_stock FROM items WHERE item_id = '$item_id'";
 $result = mysqli_query($conn, $sql);
 $row = mysqli_fetch_assoc($result);
 $current_stock = $row["current_stock"];

 $new_stock = $current_stock - $quantity;

   // Update current stock and user ID in database
   $sql = "UPDATE items SET current_stock = $new_stock WHERE item_id = '$item_id'";

}
if (mysqli_query($conn, $sql)) {
  // Display success message and redirect to previous page
  echo "<script>alert('Stock updated successfully!');window.location.href=document.referrer;</script>";
  


 // Insert a record into the inventory_logs table
 $sql = "INSERT INTO activity_log (username,item_id, receiver, removal_date, purpose, project, previous_stock, new_stock, quantity) 
          VALUES ('$username','$item_id', '$receiver', '$removal_date', '$purpose', '$project',  $current_stock, $new_stock, '$quantity')";
 mysqli_query($conn, $sql);
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
