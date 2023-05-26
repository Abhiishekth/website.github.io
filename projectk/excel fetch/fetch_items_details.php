<?php
// Endpoint to fetch item details based on the selected category, subcategory, and item ID
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

// Retrieve the selected category, subcategory, and item IDs from the query string
$categoryId = $_GET["categoryId"];
$subcategoryId = $_GET["subcategoryId"];
$itemId = $_GET["itemId"];
$items = array();

// Construct the SQL query based on the selected IDs
$sql = "SELECT * FROM items WHERE 1";

if ($categoryId !== "all") {
    $sql .= " AND category_id = '$categoryId'";
}

if ($subcategoryId !== "all") {
    $sql .= " AND subcategory_id = '$subcategoryId'";
}

if ($itemId !== "all") {
    $sql .= " AND item_id = '$itemId'";
}

// Fetch items based on the selected category, subcategory, and item IDs
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch each item and store it in the array
    while ($row = $result->fetch_assoc()) {
        // Store the entire row in the items array
        $items[] = $row;
    }
}

// Close database connection
$conn->close();

// Return the items as JSON
header("Content-Type: application/json"); // Set the response header to indicate JSON content type
echo json_encode($items);
?>
