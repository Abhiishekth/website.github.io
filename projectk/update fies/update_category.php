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

// Get form submission data
$id = $_POST["id"];
$category_id = $_POST["category_id"];
$category_name = $_POST["category_name"];
$description = $_POST["description"];
$old_image = $_POST['old_image'];
$new_image = $_FILES['image']['name'];

// If a new image was uploaded, handle it
if ($_FILES['image']['size'] > 0) {
    // Delete old image
    if (file_exists("../uploads/" . $old_image)) {
        unlink("../uploads/" . $old_image);
    }
    
    // Upload new image
    $target_dir = "../uploads/";
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


// SQL query to update category details in the "categories" table
$sql = "UPDATE categories SET  category_id='$category_id', category_name='$category_name', description='$description', image='$new_image' WHERE id='$id'";

// Execute the SQL query
if ($conn->query($sql) === TRUE) {
    echo "Category details updated successfully";
} else {
    echo "Error updating category details: " . $conn->error;
}

// Close database connection
$conn->close();
?>