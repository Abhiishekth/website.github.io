<?php
// Replace with your database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

// Get the selected headers, category ID, subcategory ID, and item ID from the request
$selectedHeaders = json_decode($_GET['selectedHeaders']);
$categoryId = $_GET['categoryId'];
$subcategoryId = $_GET['subcategoryId'];
$itemId = $_GET['itemId'];

// Create a PDO connection to the database
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Prepare the SQL query based on the selected category, subcategory, and item
if (empty($selectedHeaders)) {
    // If no headers are selected, fetch all columns
    $sql = "SELECT * FROM items WHERE 1";
} else {
    // If specific headers are selected, fetch those columns only
    $sql = "SELECT " . implode(", ", $selectedHeaders) . " FROM items WHERE 1";
}
if ($categoryId !== "all") {
    $sql .= " AND category_id = :categoryId";
}
if ($subcategoryId !== "all") {
    $sql .= " AND subcategory_id = :subcategoryId";
}
if ($itemId !== "all") {
    $sql .= " AND item_id = :itemId";
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if ($categoryId !== "all") {
    $stmt->bindParam(':categoryId', $categoryId);
}
if ($subcategoryId !== "all") {
    $stmt->bindParam(':subcategoryId', $subcategoryId);
}
if ($itemId !== "all") {
    $stmt->bindParam(':itemId', $itemId);
}
$stmt->execute();

// Fetch all the rows as associative arrays
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close the database connection
$conn = null;

// Send the data as JSON response
header('Content-Type: application/json');
echo json_encode($rows);
?>