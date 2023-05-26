<!DOCTYPE html>
<html>
<head>
    <title>Add New Item</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Add New Item</h1>
    <form method="POST" action="save_item.php" enctype="multipart/form-data">
        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" onchange="getSubcategories()">
            <option value="">Select a category</option>
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
                echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
            }

            mysqli_close($conn);
            ?>
        </select><br><br>

        <label for="subcategory_id">Subcategory:</label>
        <select name="subcategory_id" id="subcategory_id">
        <option value="">Select a subcategory</option>
        </select>
       

        <label for="item_id">Item ID:</label>
        <input type="text" id="item_id" name="item_id" required><br><br>

        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" required><br><br>

        <label for="initial_stock">Initial Stock:</label>
        <input type="number" id="initial_stock" name="initial_stock" required><br><br>

        <label for="image">Image:</label>
        <input type="file" name="image" id="image" required><br><br>

        <label for="place_stored">Place Stored:</label>
        <input type="text" id="place_stored" name="place_stored" required><br><br>

        <label for="details">Details:</label>
        <textarea id="details" name="details" required></textarea><br><br>

        <button type="submit">Submit</button>
    </form>

    <script>
        function getSubcategories() {
    var category_id = document.getElementById("category_id").value;
    var subcategory_id = document.getElementById("subcategory_id");

    // Clear existing options
    while (subcategory_id.options.length > 1) {
        subcategory_id.remove(1);
    }

    // Get subcategories for selected category
    if (category_id !== "") {
        // Establish database connection
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Parse JSON response
                var subcategories = JSON.parse(this.responseText);

                // Add subcategory options to dropdown
                subcategories.forEach(function(subcategory) {
                    var option = document.createElement("option");
                    option.value = subcategory.subcategory_id;
                    option.text = subcategory.subcategory_name;
                    subcategory_id.add(option);
                });
            } else {
                console.log("Error retrieving subcategories");
            }
        };

        // Send request to server to retrieve subcategories for selected category
        xmlhttp.open("GET", "get_subcategories.php?category_id=" + category_id, true);
        xmlhttp.send();
    }
}
function searchSubcategories() {
    var input = document.getElementById("subcategory_search");
    var filter = input.value.toUpperCase();
    var subcategory_id = document.getElementById("subcategory_id");

    // Loop through all options in the dropdown and hide those that don't match the search query
    for (var i = 1; i < subcategory_id.options.length; i++) {
        var txtValue = subcategory_id.options[i].text;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            subcategory_id.options[i].style.display = "";
        } else {
            subcategory_id.options[i].style.display = "none";
        }
    }
}
</script>
</body>
</html>