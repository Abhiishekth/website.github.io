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
$ID = $_POST['category_id'];
$name = $_POST['category_name'];
$description = $_POST['description'];
//$items = $_POST['num_items'];


// Save image to server
$image_name = $_FILES['image']['name'];
$image_tmp_name = $_FILES['image']['tmp_name'];
$upload_dir = "../uploads/";

if (move_uploaded_file($image_tmp_name, $upload_dir . $image_name)) {
  // Insert product details into database
  $sql = "INSERT INTO categories (category_id, category_name, description, image)
        VALUES ('$ID','$name', '$description', '$image_name')";

  if (mysqli_query($conn, $sql)) {
    echo "<div class = 'success'>New category created successfully. To add another category <a href = 'category-form.php'>Click Here</a></div>";
    
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
} else {
  echo "Error uploading file";
}

mysqli_close($conn);
?>
