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

// Retrieve subcategories for selected category
$category_id = $_GET['category_id'];
$sql = "SELECT * FROM subcategories WHERE category_id = '$category_id'";
$result = mysqli_query($conn, $sql);

// Loop through each row in the result set and add subcategory options to JSON array
$subcategories = array();
while ($row = mysqli_fetch_assoc($result)) {
    $subcategory = array(
        "subcategory_id" => $row['subcategory_id'],
        "subcategory_name" => $row['subcategory_name']
    );
    array_push($subcategories, $subcategory);
}

mysqli_close($conn);

// Return subcategories in JSON format
echo json_encode($subcategories);
?>
