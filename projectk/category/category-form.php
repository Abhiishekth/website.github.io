<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Category</title>
  <style>
    /* category form css start */
/* Set styles for the form container */

h1 {
  text-align: center;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
  }
form {
  margin: 20px auto;
    max-width: 600px;
    background-color: #f7f7f7;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
  }
  
  /* Set styles for the form labels */
  label {
    display: block;
    margin-bottom: 5px;
  }
  
  /* Set styles for the form input fields */
  input[type="text"],  input[type="number"], textarea, input[type="file"] {
    display: block;
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  }
  
  /* Set styles for the form button */
  button[type="submit"] {
    display: block;
    margin: 20px auto;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  /* Set styles for the form button on hover */
  button[type="submit"]:hover {
    background-color: #45a049;
  }
  
  /* Set styles for the form button on focus */
  button[type="submit"]:focus {
    outline: none;
  }
  
  /* Set styles for the form input fields when in focus */
  input:focus, textarea:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
  }
  
 
  /* category form css finish */

  </style>
</head>
<body>
<h1>Add Category</h1>

<form action="process_form.php" method="POST" enctype="multipart/form-data">
  

  <label for="category_id">Category ID:</label>
  <input type="text" name="category_id" id="category_id" required>

  <label for="category_name">Category Name:</label>
  <input type="text" name="category_name" id="category_name" required>

  <label for="description">Description:</label>
  <textarea name="description" id="description" required></textarea>

  <label for="image">Image:</label>
  <input type="file" name="image" id="image" >

  <!--<label for="num_items">Number of Items:</label>
  <input type="number" name="num_items" id="num_items" required>-->

  <button type="submit">Submit</button>
</form>

</body>
</html>