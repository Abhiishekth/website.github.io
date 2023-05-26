<?php
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

// Process form data
$category_id = $_POST['category_id'];
$subcategory_id = $_POST['subcategory_id'];
$subcategory_name = $_POST['subcategory_name'];
$description = $_POST['description'];
//$date = $_POST['date'];

// Save image to server
$image_name = $_FILES['image']['name'];
$image_tmp_name = $_FILES['image']['tmp_name'];
$upload_dir = "uploads/";

if (move_uploaded_file($image_tmp_name, $upload_dir . $image_name)) {
  // Insert product details into database

// Insert subcategory details into database
$sql = "INSERT INTO subcategories (category_id, subcategory_id, subcategory_name, description, image)
      VALUES ('$category_id', '$subcategory_id', '$subcategory_name', '$description', '$image_name')";

if (mysqli_query($conn, $sql)) {
  echo "<div class = 'success'>New subcategory created successfully. To add another subcategory <a href = 'subcategory-form.php'>Click Here</a></div>";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
}else {
  echo "Error uploading file";
}
mysqli_close($conn);
?>
