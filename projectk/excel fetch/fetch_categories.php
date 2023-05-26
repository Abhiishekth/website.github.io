<?php
// Endpoint to fetch categories from the database
// Replace with appropriate database connection code

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT category_id, category_name FROM categories";
$result = $conn->query($sql);

$categories = array();

if ($result->num_rows > 0) {
    // Fetch each category and store it in the array
    while ($row = $result->fetch_assoc()) {
        $category = array(
            "category_id" => $row["category_id"],
            "category_name" => $row["category_name"]
        );
        array_push($categories, $category);
    }
}

// Close database connection
$conn->close();

// Return the categories as JSON
echo json_encode($categories);
?>