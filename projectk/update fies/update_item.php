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

// Get item ID and other values from form submission
$id = $_POST["id"];
$item_id = $_POST["item_id"];
$subcategory_id = $_POST["subcategory_id"];
$item_name = $_POST["item_name"];
$initial_stock = $_POST["initial_stock"];
$current_stock = $_POST["current_stock"];
$place_stored = $_POST["place_stored"];
$details = $_POST["details"];
$old_image = $_POST['old_image'];
$new_image = $_FILES['image']['name'];

// If a new image was uploaded, handle it
if ($_FILES['image']['size'] > 0) {
    // Delete old image
    if (file_exists("../items/uploads/" . $old_image)) {
        unlink("../items/uploads/" . $old_image);
    }
    
    // Upload new image
    $target_dir = "../items/uploads/";
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


// Update the item record in the database
$sql = "UPDATE items SET item_id='$item_id', subcategory_id='$subcategory_id', item_name='$item_name', initial_stock='$initial_stock', current_stock='$current_stock', place_stored='$place_stored', details='$details', image='$new_image' WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating item: " . $conn->error;
}
$conn->close();
?>
