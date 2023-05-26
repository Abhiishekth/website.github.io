<!DOCTYPE html>
<html>
<head>
  <title>Items Display</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body onload="populateCategories()">
  <h1>Items Display</h1>

  <label for="categoryDropdown">Category:</label>
  <select id="categoryDropdown" onchange="populateSubcategories()">
    <option value="all">All Categories</option>
    <!-- Populate categories dynamically -->
  </select>

  <!-- Subcategory Dropdown -->
  <label for="subcategoryDropdown">Subcategory:</label>
  <select id="subcategoryDropdown" onchange="populateItems()">
    <!-- Add an option for selecting all subcategories -->
    <option value="all">All</option>
  </select>

  <!-- Item Dropdown -->
  <label for="itemDropdown">Item:</label>
  <select id="itemDropdown" onchange="displayItems()">
    <!-- Add an option for selecting all items -->
    <option value="all">All</option>
  </select>

  <!-- Display Table -->
  <table id="displayTable">
    <!-- The fetched data will be inserted here -->
  </table>

  <!-- Button to trigger displaying the items -->
  <button onclick="displayItems()">Display Items</button>

  <!-- Button to export the selected data to Excel -->
  <button onclick="exportToExcel()">Export to Excel</button>


  <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
  <script lang="javascript">
    function populateCategories() {
      // Fetch the categories from the database and populate the category dropdown
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "fetch_categories.php", true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          var categories = JSON.parse(xhr.responseText);

          var categoryDropdown = document.getElementById("categoryDropdown");
          categoryDropdown.innerHTML = ""; // Clear existing options

          // Add an option for selecting all categories
          var allOption = document.createElement("option");
          allOption.value = "all";
          allOption.text = "All";
          categoryDropdown.add(allOption);

          // Add categories as options in the dropdown
          categories.forEach(function(category) {
            var option = document.createElement("option");
            option.value = category.category_id;
            option.text = category.category_name;
            categoryDropdown.add(option);
          });

          // Populate subcategories based on the initially selected category
          populateSubcategories();
        }
      };
      xhr.send();
    }

    function populateSubcategories() {
      var categoryId = document.getElementById("categoryDropdown").value;
      var subcategoryDropdown = document.getElementById("subcategoryDropdown");
      subcategoryDropdown.innerHTML = ""; // Clear existing options

      // Add an option for selecting all subcategories
      var allOption = document.createElement("option");
      allOption.value = "all";
      allOption.text = "All";
      subcategoryDropdown.add(allOption);

      if (categoryId !== "all") {
        // Fetch the subcategories based on the selected category from the database
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch_subcategories.php?categoryId=" + categoryId, true);
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            var subcategories = JSON.parse(xhr.responseText);

            console.log("Fetched subcategories:", subcategories);

            // Add subcategories as options in the dropdown
            subcategories.forEach(function(subcategory) {
              var option = document.createElement("option");
              option.value = subcategory.subcategory_id;
              option.text = subcategory.subcategory_name;
              subcategoryDropdown.add(option);
            });

            // Populate items based on the initially selected subcategory
            populateItems();
          }
        };
        xhr.send();
      } else {
        // Populate items with all items since all categories are selected
        populateItems();
      }
    }

    function populateItems() {
      var categoryId = document.getElementById("categoryDropdown").value;
      var subcategoryId = document.getElementById("subcategoryDropdown").value;
      var itemDropdown = document.getElementById("itemDropdown");
      itemDropdown.innerHTML = ""; // Clear existing options

      // Add an option for selecting all items
      var allOption = document.createElement("option");
      allOption.value = "all";
      allOption.text = "All";
      itemDropdown.add(allOption);

      if (categoryId !== "all" || subcategoryId !== "all") {
        // Fetch the items based on the selected category and subcategory from the database
        var xhr = new XMLHttpRequest();
        var url = "fetch_items_ids.php?categoryId=" + categoryId + "&subcategoryId=" + subcategoryId;
        xhr.open("GET", url, true);
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            var items = JSON.parse(xhr.responseText);

            console.log("Fetched items:", items);

            // Add items as options in the dropdown
            items.forEach(function(item) {
              var option = document.createElement("option");
              option.value = item.item_id;
              option.text = item.item_name;
              itemDropdown.add(option);
            });
          }
        };
        xhr.send();
      }
    }

    function displayItems() {
      var categoryId = document.getElementById("categoryDropdown").value;
      var subcategoryId = document.getElementById("subcategoryDropdown").value;
      var itemId = document.getElementById("itemDropdown").value;

      var xhr = new XMLHttpRequest();
      var url = "fetch_items_details.php?categoryId=" + categoryId + "&subcategoryId=" + subcategoryId + "&itemId=" + itemId;
      xhr.open("GET", url, true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);

          console.log("Fetched item details:", response);

          var table = document.getElementById("displayTable");
          table.innerHTML = ""; // Clear existing table

          if (response.length > 0) {
            var headerRow = document.createElement("tr");
            var rowIndex = 0;

            // Create an array to store the checkbox elements
            var checkboxArray = [];

            Object.keys(response[0]).forEach(function(key) {
              var header = document.createElement("th");
              var checkbox = document.createElement("input");
              checkbox.type = "checkbox";
              checkbox.className = "header-checkbox";
              checkbox.value = key;

              checkboxArray.push(checkbox); // Store the checkbox element

              var headerText = document.createTextNode(key);
              header.appendChild(checkbox);
              header.appendChild(headerText);
              headerRow.appendChild(header);
            });

            table.appendChild(headerRow);

            response.forEach(function(item) {
              var row = document.createElement("tr");

              // Add checkboxes in the first row
              if (rowIndex === 0) {
                var checkboxRow = document.createElement("tr");

                checkboxArray.forEach(function(checkbox) {
                  var cell = document.createElement("td");
                  cell.appendChild(checkbox);
                  checkboxRow.appendChild(cell);
                });

                table.appendChild(checkboxRow);
              }

              // Add the item details
              Object.keys(item).forEach(function(key) {
                var cell = document.createElement("td");

                // Check if it's an even row index and add background color
                if (rowIndex % 2 === 0) {
                  cell.style.backgroundColor = "#f2f2f2";
                }

                var cellText = document.createTextNode(item[key]);
                cell.appendChild(cellText);
                row.appendChild(cell);
              });

              table.appendChild(row);

              rowIndex++;
            });
          } else {
            var noDataRow = document.createElement("tr");
            var noDataCell = document.createElement("td");
            noDataCell.colSpan = response.length;
            var noDataText = document.createTextNode("No items found");
            noDataCell.appendChild(noDataText);
            noDataRow.appendChild(noDataCell);
            table.appendChild(noDataRow);
          }
        }
      };
      xhr.send();
    }

    function exportToExcel() {
      var headerCheckboxes = document.getElementsByClassName("header-checkbox");
      var selectedHeaders = [];

      // Iterate over the header checkboxes and add the selected headers to the array
      for (var i = 0; i < headerCheckboxes.length; i++) {
        if (headerCheckboxes[i].checked) {
          selectedHeaders.push(headerCheckboxes[i].value);
        }
      }

      // Fetch the data to be exported based on the selected headers
      var categoryId = document.getElementById("categoryDropdown").value;
      var subcategoryId = document.getElementById("subcategoryDropdown").value;
      var itemId = document.getElementById("itemDropdown").value;

      var xhr = new XMLHttpRequest();
      var url = "fetch_export_data.php?categoryId=" + categoryId + "&subcategoryId=" + subcategoryId + "&itemId=" + itemId + "&selectedHeaders=" + JSON.stringify(selectedHeaders);
      xhr.open("GET", url, true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);

          console.log("Fetched export data:", response);

          // If no data is available, return
          if (response.length === 0) {
            alert("No data available to export.");
            return;
          }

          // Convert the data to the Excel workbook format
          var workbook = XLSX.utils.book_new();
          var worksheet = XLSX.utils.json_to_sheet(response);
          XLSX.utils.book_append_sheet(workbook, worksheet, "Exported Data");

          // Save the workbook as a file
          var filename = "exported_data.xlsx";
          XLSX.writeFile(workbook, filename);
        }
      };
      xhr.send();
    }

  </script>
</body>
</html>
