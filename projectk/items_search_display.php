<!DOCTYPE html>
<html>

<head>
  <title>Item Details</title>
  <link rel="stylesheet" type="text/css" href="style-item.css">
  <style>
table {
  border-collapse: collapse;
  border: 1px solid black;
}

th, td {
  border: 1px solid black;
  padding: 8px;
}

th {
  background-color: #f2f2f2;
}

/* Hide forms by default */
#add-stock-form-<?php echo $row["item_id"] ?>,
#remove-stock-form-<?php echo $row["item_id"] ?> {
display: none;
}
  </style>
</head>

<body>
  <h1>Item Details</h1>

  <form method="GET" action="">


  <label for="search">Search: </label>
  <input type="text" name="search" id="search" placeholder="Search items...">
  <button type="submit">Go</button>
</form>

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

  // Retrieve item data for a specific subcategory from database
//   require_once 'db.php';
//   $subcategory_id = $_GET['subcategory_id'];
  $search_value = isset($_GET['search']) ? $_GET['search'] : '';

  $sql = "SELECT items.*, items.category_id 
        FROM items
        WHERE items.item_name LIKE '%$search_value%'
        OR items.item_id LIKE '%$search_value%'
        OR items.place_stored LIKE '%$search_value%'
        OR items.details LIKE '%$search_value%'
        OR items.category_id LIKE '%$search_value%'
        OR items.subcategory_id LIKE '%$search_value%'
        OR items.current_stock LIKE '%$search_value%'";


  $result = mysqli_query($conn, $sql);


  // Check if there are any items in the subcategory
if (mysqli_num_rows($result) > 0) {
    // Loop through each item and display its details
  while ($row = mysqli_fetch_assoc($result)) {
    $item_id = $row["item_id"];
  ?>
      <div class='container'>
        <img src='/projectk/items/uploads/<?php echo $row["image"] ?>' alt='<?php echo $row["item_name"] ?>'><br>
        <div class='container-details' style="width:100%">
          <h2><?php echo $item_id ?></h2>
          <p>Category Id: <?php echo $row["category_id"] ?></p>
          <p>Subcategory Id: <?php echo $row["subcategory_id"] ?></p>

          <!--<p>Initial Stock: <?php echo $row["initial_stock"] ?></p>-->
          <p>Current Stock: <?php echo $row["current_stock"] ?></p>
          <p>Place Stored: <?php echo $row["place_stored"] ?></p>
          <p>Details: <?php echo $row["details"] ?></p>

          <!-- Display add/remove stock buttons -->
<div>
  <button id='add-stock-btn-<?php echo $row["item_id"] ?>' onclick='showAddStockForm("<?php echo $row["item_id"] ?>")'>Add Stock</button>
  <button id='remove-stock-btn-<?php echo $row["item_id"] ?>' onclick='showRemoveStockForm("<?php echo $row["item_id"] ?>")'>Remove Stock</button>
</div><br><br>

<!-- Add Stock Form -->
<div id='add-stock-form-<?php echo $row["item_id"] ?>' style='display:none'>
  <form method='POST' action='/projectk/items/add_stock.php' enctype='multipart/form-data'>
    
    <input type='hidden' name='item_id' value='<?php echo $row["item_id"] ?>'>
    <label for='manufacturer'>Name of Manufacturer:</label>
    <input type='text' id='manufacturer' name='manufacturer' required><br>
    <label for='purchase-date'>Date of Purchase:</label>
    <input type='date' id='purchase-date' name='purchase_date' required><br>
    <label for='price'>Price:</label>
    <input type='number' id='price' name='price' required><br>
    <label for='quantity'>Quantity:</label>
    <input type='number' id='quantity' name='quantity' required><br>
    <label for='invoice'>File of Invoice:</label>
    <input type='file' id='invoice' name='invoice' accept='.pdf, .jpg, .png'><br>
    <button type='submit' name='action' value='add'>Add Stock</button>
    <button type='button' onclick='hideAddStockForm("<?php echo $row["item_id"] ?>")'>Cancel</button>
  </form><br><br>
</div>

<!-- Remove Stock Form -->
<div id='remove-stock-form-<?php echo $row["item_id"] ?>' style='display:none'>
  <form method='POST' action='/projectk/items/remove_stock.php'>
    
    <input type='hidden' name='item_id' value='<?php echo $row["item_id"] ?>'>
    <label for='receiver'>Name of Receiver:</label>
    <input type='text' id='receiver' name='receiver' required><br>
    <label for='removal-date'>Date of Removal:</label>
    <input type='date' id='removal-date' name='removal_date' required><br>
    <label for='quantity'>Quantity:</label>
    <input type='number' id='quantity' name='quantity' required><br>
    <label for='purpose'>Purpose:</label>
    <input type='text' id='purpose' name='purpose' required><br>
    <label for='project'>Project for which it is being used:</label>
    <input type='text' id='project' name='project' required><br>
    <button type='submit' name='action' value='remove'>Remove Stock</button>
    <button type='button' onclick='hideRemoveStockForm("<?php echo $row["item_id"] ?>")'>Cancel</button>
  </form><br><br>
</div>

<?php
// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


// Retrieve data from database
$item_id = mysqli_real_escape_string($conn, $item_id); // escape the value

$stmt = $conn->prepare("SELECT * FROM addstock WHERE item_id = ?");
$stmt->bind_param("s", $item_id);
$stmt->execute();
$result_stock = $stmt->get_result();
$filepath='invoice/" .$row["invoice"] . "';
// header('Content-Disposition: inline; filename="'.basename($filepath).'"');


// Display data in HTML table
if (mysqli_num_rows($result_stock) > 0) {
  echo "<table>";
  echo "<tr>
  <th>Username</th>
  <th>Item ID</th>
  <th>Manufacturer</th>
  <th>Purchase Date</th>
  <th>Date the record added</th>
  <th>Price</th>
  <th>Quantity</th>
  <th>Invoice</th>

</tr>";
while($row = $result_stock->fetch_assoc()) {
  echo "<tr><td>" . $row["username"] . "</td>
        <td>" . $row["item_id"] . "</td>
        <td>" . $row["manufacturer"] . "</td>
        <td>" . $row["purchase_date"] . "</td>
        <td>" . $row["last_update"] . "</td>
        <td>" . $row["price"] . "</td>
        <td>" . $row["quantity"] . "</td>
        <td><a href='items/invoice/" .$row["invoice"] . "' target='_blank' rel='noopener noreferrer'><button>View Invoice</button></a></td></tr>";
}

  echo "</table>";
} else {
  echo "0 results";
}


?>


<script>
    function showAddStockForm(itemId) {
      document.getElementById("add-stock-form-" + itemId).style.display = "block";
    }

    function showRemoveStockForm(itemId) {
      document.getElementById("remove-stock-form-" + itemId).style.display = "block";
    }

    function hideAddStockForm(itemId) {
      document.getElementById("add-stock-form-" + itemId).style.display = "none";
    }

    function hideRemoveStockForm(itemId) {
      document.getElementById("remove-stock-form-" + itemId).style.display = "none";
    }


  </script>

        </div>
      </div>

  <?php
 } 
} else {
    echo "No items found in this subcategory";
  }

  mysqli_close($conn);
  ?>


</body>

</html>