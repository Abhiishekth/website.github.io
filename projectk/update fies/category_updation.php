<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    
<h2>Item Details</h2>

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

// Define number of records to display per page
$records_per_page = 10;

// Get current page number from URL parameter
if (isset($_GET["page"])) {
    $current_page = $_GET["page"];
} else {
    $current_page = 1;
}

// Calculate the limit and offset for the SQL query
$limit = $records_per_page;
$offset = ($current_page - 1) * $records_per_page;

// SQL query to retrieve all records from the "categories" table
$sql = "SELECT * FROM categories LIMIT $limit OFFSET $offset";

// Execute the SQL query
$result = $conn->query($sql);

// Check if there are any records
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        ?>
        
<form action="update_category.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
  <label for="category_id">Category ID:</label>
  <input type="text" name="category_id" id="category_id" value="<?php echo $row['category_id']; ?>"><br>
  <label for="category_name">Category Name:</label>
  <input type="text" name="category_name" id="category_name" value="<?php echo $row['category_name']; ?>"><br>
  <label for="description">Description:</label>
  <textarea name="description" id="description"><?php echo $row['description']; ?></textarea><br>
  <input type="hidden" name="old_image" value="<?php echo $row['image']; ?>">
  <label for="image">Image:</label>
  <input type="file" name="image"><br><br>
  <input type="submit" value="Update">
</form>
<hr>

        <?php
    }
} else {
    echo "No records found"; // Output message if there are no records
}

// Calculate the total number of pages
$sql = "SELECT COUNT(*) AS total_records FROM categories";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_records = $row["total_records"];
$total_pages = ceil($total_records / $records_per_page);

// Output pagination links
if ($total_pages > 1) {
    echo "<div>";
    if ($current_page > 1) {
        echo "<a href='category_updation.php?page=" . ($current_page - 1) . "'>Prev</a> ";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            echo "<strong>$i</strong> ";
            } else {
            echo "<a href='category_updation.php?page=$i'>$i</a> ";
            }
            }
            if ($current_page < $total_pages) {
            echo "<a href='category_updation.php?page=" . ($current_page + 1) . "'>Next</a>";
            }
            echo "</div>";
            }
            
            // Close database connection
            $conn->close();
            ?>


</body>
</html>
