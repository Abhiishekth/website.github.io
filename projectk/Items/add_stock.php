<?php

// Start the session
session_start();

// Establish database connection
$conn = mysqli_connect("localhost", "root", "", "database");

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

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

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
  // Get the form data
  $item_id = $_POST['item_id'];
  $manufacturer = $_POST['manufacturer'];
  $purchase_date = $_POST['purchase_date'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];
  $username = $_SESSION["username"];


  // Check if the file was uploaded successfully
  if (isset($_FILES['invoice']) && $_FILES['invoice']['error'] == UPLOAD_ERR_OK) {
    // Get the file details
    $file_name = $_FILES['invoice']['name'];
    $file_size = $_FILES['invoice']['size'];
    $file_tmp = $_FILES['invoice']['tmp_name'];
    $file_type = $_FILES['invoice']['type'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Check if the file type is valid
    $allowed_ext = array('pdf', 'jpg', 'jpeg', 'png');
    if (in_array($file_ext, $allowed_ext)) {
      // Generate a unique file name
      $new_file_name = uniqid() . '.' . $file_ext;

      // Move the uploaded file to the uploads directory
      $upload_dir = 'invoice/';
      $upload_path = $upload_dir . $new_file_name;
      move_uploaded_file($file_tmp, $upload_path);

    // Retrieve current stock and user ID from database
    $sql = "SELECT current_stock FROM items WHERE item_id = '$item_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $current_stock = $row["current_stock"];

    $new_stock = $current_stock + $quantity;
    
    

    } else {
      // File type is not allowed
      $error_message = 'Only PDF, JPG, JPEG, and PNG files are allowed.';
    }
  } else {
    // File was not uploaded
    $error_message = 'Please choose a file.';
  }
}

// Display the error message (if any)
if (isset($error_message)) {
  echo $error_message;
} else{

      // Update current stock and user ID in database
      $sql = "UPDATE items SET current_stock = $new_stock WHERE item_id = '$item_id'";

}


if (mysqli_query($conn, $sql)) {
    // Display success message and redirect to previous page
    echo "<script>alert('Stock updated successfully!');window.location.href=document.referrer;</script>";
    
  // Insert the data into the database
     
      $sql = "INSERT INTO addstock (username,item_id, manufacturer, purchase_date, price, quantity, invoice) 
                VALUES ( '$username','$item_id', '$manufacturer', '$purchase_date', '$price', '$quantity', '$new_file_name')";
       mysqli_query($conn, $sql);
  
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
  


mysqli_close($conn);

?>
