<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
    <link rel="stylesheet" href="styledisplay.css">
    <link rel="shortcut icon" href="favicon.io.avif" type="iscientific logo.png">


</head>
<body>

<div class="header" style="box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.2);">
    <div class="navbar">
      <img src="iscientific logo.png" alt="Logo" class="logo">
      <h1 class="company-name">ISTL INVENTORY</h1>
      <ul>
        <li><a href="https://writrz.com/">Home</a></li>
        <li><a href="https://www.pexels.com/">Bedroom</a></li>
        <li><a href="#">Dining</a></li>
        <li><a href="#">Kitchen</a></li>
        <li><a href="#">Backyard</a></li>
      </ul>
    </div>
  </div>


  <form method="GET" action="../items_search_display.php">

 

  <label for="search">Search: </label>
  <input type="text" name="search" id="search" placeholder="Search items...">
  <button type="submit">Go</button>
</form>



<div id="category-list"></div>



<?php
// Retrieve category data from database
require_once('db.php');
$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);
?>

<!-- Display category data in boxes -->
<?php while($row = mysqli_fetch_assoc($result)): ?>
  
  <div class="category-box">
    <img src="../uploads/<?php echo $row['image']; ?>">
    <h2><?php echo $row['category_name']; ?></h2>
    <p><?php echo $row['description']; ?></p>
    <!--<p>Number of items: <?php echo $row['num_items']; ?></p>-->
    <a href="../subcategory/display-subcategories.php?category_id=<?php echo $row['category_id']; ?>">View Subcategories</a>
  </div>
<?php endwhile; ?>

<?php mysqli_close($conn); ?>

<div class="category-box">
    <!-- <img src="../uploads/<?php echo $row['image']; ?>"> -->
    <h2>Boxes</h2>
    <p>This is extra boxes</p>
    <!--<p>Number of items: <?php echo $row['num_items']; ?></p>-->
    <a href="../activity_display.php">View Subcategories</a>

</body>
</html>