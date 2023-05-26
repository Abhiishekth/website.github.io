<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Subcategories</title>
 
  <style>
    /* Set styles for the subcategory boxes */
.subcategory-box {
  display: inline-block;
            flex-direction: column;
            align-items: right;
            justify-content: center;
            width: 300px;
            
            margin: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            padding: 15px;
            background-color: #fff;
            text-align: center;
}

.subcategory-box img {
  width: 50%;
  height: 200px;
  object-fit: fill;
  border-radius: 5px;
  margin-right: 20px;
}

/* Set styles for the subcategory box headings */
.subcategory-box h2 {
  margin-top: 0;
  margin-bottom: 10px;
  font-size: 24px;
  font-weight: bold;
}

/* Set styles for the subcategory box paragraphs */
.subcategory-box p {
  margin: 0;
  font-size: 16px;
  line-height: 1.5;
  object-fit: cente;
            margin-bottom: 10px;
}

/* Set styles for the subcategory box link */
.subcategory-box a {
  display: inline-block;
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
}


/* Set styles for the subcategory box link on hover */
.subcategory-box a:hover {
  text-decoration: underline;
  background-color: #444;
}

  </style>
</head>
<body>

<h1>Subcategories</h1>

  <form method="GET" action="/projectk/subcategory/display-subcategories.php?category_id=<?php echo $_GET['category_id']; ?>">

  <!-- add a hidden input field for the subcategory_id parameter -->
  <input type="hidden" name="category_id" value="<?php echo $_GET['category_id']; ?>">

  <label for="search">Search: </label>
  <input type="text" name="search" id="search" placeholder="Search items...">
  <button type="submit">Go</button>
</form>

<?php
// Retrieve subcategory data and item count for a specific category from database
require_once('db.php');
$category_id = $_GET['category_id'];
$search_value = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT s.*, SUM(i.current_stock) as item_count 
        FROM subcategories s 
        LEFT JOIN items i ON s.subcategory_id = i.subcategory_id 
        WHERE s.category_id = '$category_id' 
          AND (s.subcategory_name LIKE '%$search_value%'
            OR s.subcategory_id LIKE '%$search_value%'
            OR s.description LIKE '%$search_value%'
            OR s.date LIKE '%$search_value%')
        GROUP BY s.subcategory_id";

$result = mysqli_query($conn, $sql);

// Check if there are any subcategory in the category
if (mysqli_num_rows($result) > 0) {

//Display subcategory data in boxes 
while($row = mysqli_fetch_assoc($result)){
  ?>
  <div class="subcategory-box">
  <img src='uploads/<?php echo $row["image"] ?>' alt='<?php echo $row["subcategory_id"] ?>'><br>
    <h2>ID: <?php echo $row['subcategory_id']; ?></h2>
    <h2>Name: <?php echo $row['subcategory_name']; ?></h2>
    <p>Description: <?php echo $row['description']; ?></p>
    <p>Category ID: <?php echo $row['category_id']; ?></p>
    <p>Item Count: <?php echo $row['item_count']; ?></p>
    <a href="../items/items_display.php?subcategory_id=<?php echo $row['subcategory_id']; ?>">View Items</a>
  </div>
  <?php
}
} else {
  echo "No subcategories found in this category";
}

mysqli_close($conn);
?>
</body>
</html>
