<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";
$conn = new mysqli($servername, $username, $password, $dbname);

// Redirect to the page without the search query if the page was refreshed without a search query
if (empty($_GET['search']) && !empty($_SERVER['QUERY_STRING'])) {
  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}

// Clear the search query if the page was refreshed
if (empty($_SERVER['QUERY_STRING'])) {
  $_GET['search'] = '';
}

// Define the search query
$search_query = "";
if (isset($_GET['search'])) {
  $search_query = $_GET['search'];
  $search_query = mysqli_real_escape_string($conn, $search_query); // Sanitize the input
  $search_query = '%' . $search_query . '%'; // Add wildcards to the query
}

// Fetch the records from the database
$sql = "SELECT * FROM activity_log";
if ($search_query != "") {
  $sql .= " WHERE username LIKE '$search_query' OR item_id LIKE '$search_query' OR receiver LIKE '$search_query' OR previous_stock LIKE '$search_query' OR new_stock LIKE '$search_query' OR quantity LIKE '$search_query' OR purpose LIKE '$search_query' OR project LIKE '$search_query'";
}
$sql .= " ORDER BY timestamp DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Activity Log</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Activity Log</h1>
  
  <!-- Search form -->
  <form method="get">
    <label for="search"></label>
    <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($_GET['search'] ?? '') ?>">
    <input type="submit" value="Search">
  </form>
  
  <!-- Table of records -->
  <table>
    <thead>
      <tr>
        <th>Username</th>
        <th>Item ID</th>
        <th>Removal Date</th>
        <th>Timestamp</th>
        <th>Previous Stock</th>
        <th>New Stock</th>
        <th>Quantity</th>
        <th>Receiver</th>
        <th>Purpose</th>
        <th>Project</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Loop through the records and display them in the table
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["item_id"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["removal_date"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["timestamp"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["previous_stock"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["new_stock"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["quantity"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["receiver"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["purpose"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["project"]) . "</td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='7'>No records found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
  
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
