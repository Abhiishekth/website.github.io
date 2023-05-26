<?php
// Establish database connection
$servername = "localhost"; // replace with your server name if different
$username = "root"; // replace with your database username
$password = ""; // replace with your database password
$dbname = "database"; // replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$id = $_POST['id'];
$category_id = $_POST['category_id'];
$subcategory_id = $_POST['subcategory_id'];
$subcategory_name = $_POST['subcategory_name'];
$description = $_POST['description'];
$date = $_POST['date'];
$old_image = $_POST['old_image'];
$new_image = $_FILES['image']['name'];

// If a new image was uploaded, handle it
if ($_FILES['image']['size'] > 0) {
    // Delete old image
    if (file_exists("../subcategory/uploads/" . $old_image)) {
        unlink("../subcategory/uploads/" . $old_image);
    }
    
    // Upload new image
    $target_dir = "../subcategory/uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $extensions_arr = array("jpg","jpeg","png","gif");
    
    // Check file type
    if (in_array($imageFileType,$extensions_arr)) {
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $new_image = basename($_FILES["image"]["name"]);
    } else { 
        echo "Invalid file type";
        exit;
    }
} else {
    // Keep old image if no new image was uploaded
    $new_image = $old_image;
}

// Update database record
$sql = "UPDATE subcategories SET category_id='$category_id', subcategory_id='$subcategory_id', subcategory_name='$subcategory_name', description='$description', date='$date', image='$new_image' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
