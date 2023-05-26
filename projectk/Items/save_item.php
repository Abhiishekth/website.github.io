<?php
// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Establish database connection
  $host = "localhost";
  $username = "root";
  $password = "";
  $dbname = "database";

  $conn = mysqli_connect($host, $username, $password, $dbname);

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Retrieve form data
  $category_id = $_POST["category_id"];
  $subcategory_id = $_POST["subcategory_id"];
  $item_id = $_POST["item_id"];
  $item_name = $_POST["item_name"];
  $initial_stock = $_POST["initial_stock"];
  $place_stored = $_POST["place_stored"];
  $details = $_POST["details"];

  // Check if file was uploaded
  if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES["image"]["tmp_name"];
    $name = basename($_FILES["image"]["name"]);
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    // Check if file type is allowed
    if ($ext != "jpg" && $ext != "jpeg" && $ext != "png" && $ext != "gif") {
      die("Error: Only JPG, JPEG, PNG, and GIF files are allowed.");
    }

    // Move uploaded file to target directory
    $target_dir = "uploads/";
    $target_file = $target_dir . $name;
    move_uploaded_file($tmp_name, $target_file);
  }

  // Insert item into database
  $sql = "INSERT INTO items (category_id,subcategory_id, item_id, item_name, initial_stock, current_stock, place_stored, image, details) 
           VALUES ('$category_id','$subcategory_id', '$item_id', '$item_name', '$initial_stock', '$initial_stock', '$place_stored', '$name', '$details')";
  if (mysqli_query($conn, $sql)) {
    echo "<div class = 'success'>New item added successfully. To add another item <a href = 'items-form.php'>Click Here</a></div>";
  } else {
    echo "Error adding item: " . mysqli_error($conn);
  }

  mysqli_close($conn);
}
?>
