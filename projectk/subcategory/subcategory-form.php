<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-subcategory.css">

    <title>Form Subcategory</title>
</head>
<body>
<h1>Add Subcategory</h1>
<form action="process_subcategory.php" method="POST" enctype="multipart/form-data">
  
  <label for="category_id">Category ID:</label>
  <select name="category_id" id="category_id">
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

    // Retrieve categories from database
    $sql = "SELECT * FROM categories";
    $result = mysqli_query($conn, $sql);

    // Loop through each row in the result set and add category options to dropdown
    while ($row = mysqli_fetch_assoc($result)) {
      echo '<option value="' . $row['category_id'] . '">' . $row['category_id'] . '</option>';
    }

    mysqli_close($conn);
    ?>
  </select>

  <label for="subcategory_id">Subcategory ID:</label>
  <input type="text" name="subcategory_id" id="subcategory_id" required>

  <label for="subcategory_name">Subcategory Name:</label>
  <input type="text" name="subcategory_name" id="subcategory_name" required>

  <label for="description">Description:</label>
  <textarea name="description" id="description" required></textarea>

  <label for="image">Image:</label>
  <input type="file" name="image" id="image" >

  <!--<label for="date">Date:</label>
  <input type="date" name="date" id="date" required>-->

  <button type="submit">Submit</button>
</form>

</body>
</html>


