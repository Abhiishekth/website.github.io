<?php
// Endpoint to fetch subcategories based on the selected category
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

// Retrieve the selected category ID from the query string
$categoryId = $_GET["categoryId"];

$subcategories = array();

if ($categoryId !== "all") {
    // Fetch subcategories based on the selected category ID
    $sql = "SELECT subcategory_id, subcategory_name FROM subcategories WHERE category_id = '$categoryId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch each subcategory and store it in the array
        while ($row = $result->fetch_assoc()) {
            $subcategory = array(
                "subcategory_id" => $row["subcategory_id"],
                "subcategory_name" => $row["subcategory_name"]
            );
            array_push($subcategories, $subcategory);
        }
    }
}

// Close database connection
$conn->close();

// Return the subcategories as JSON
echo json_encode($subcategories);
?>

