<?php
// Endpoint to fetch item IDs based on the selected category and subcategory
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

// Retrieve the selected category and subcategory IDs from the query string
$categoryId = $_GET["categoryId"];
$subcategoryId = $_GET["subcategoryId"];
$items = array();



if ($categoryId !== "all" && $subcategoryId !== "all") {
    // Fetch subcategories based on the selected category ID
    $sql = "SELECT item_id, item_name FROM items WHERE category_id = '$categoryId' AND subcategory_id = '$subcategoryId'";




    // Fetch item IDs based on the selected category and subcategory
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch each item ID and store it in the array
        while ($row = $result->fetch_assoc()) {
            $item = array(
                "item_id" => $row["item_id"],
                "item_name" => $row["item_name"]
            );
            array_push($items, $item);
        }
    }
}
// Close database connection
$conn->close();

// Return the item IDs as JSON
header("Content-Type: application/json"); // Set the response header to indicate JSON content type
echo json_encode($items);
?>
