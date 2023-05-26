<!-- Sign up page -->
<!DOCTYPE html>
<html>
  <head>
    <style>
  .signup-container {
  width: 400px;
  margin: 100px auto;
  text-align: center;
}

form {
  background-color: #f2f2f2;
  padding: 40px;
  border-radius: 10px;
  box-shadow: 0 0 10px 0 #ccc;
}

input[type="text"],
input[type="email"],
input[type="password"]
{
  width: 100%;
  padding: 10px;
  margin-bottom: 20px;
  border: none;
  border-radius: 5px;
  background-color: #fff;
  box-shadow: 0 0 5px 0 #ccc;
}

button[type="submit"] {
  width: 100%;
  padding: 10px;
  border: none;
  border-radius: 5px;
  background-color: #007bff;
  color: #fff;
  cursor: pointer;
  font-size: 18px;
}

.error{
  text-align: center;
  color: red;
  font-size:20px;
}
.success{
  text-align: center;
  color: green;
  font-size:20px;
}

    </style>
  </head>
  <body>
    <div class="signup-container">
      <form action="" method="post">
        <h2>Sign Up</h2>
        <input type="text" placeholder="Username" name="username"value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : ''; ?>" required>
        <input type="email" placeholder="Email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : ''; ?>" required>
        <input type="text" placeholder="Phone number" name="number" value="<?php echo isset($_POST['number']) ? htmlspecialchars($_POST['number'], ENT_QUOTES) : ''; ?>" required>
        <input type="password" placeholder="Password" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES) : ''; ?>" required>
        <input type="password" placeholder="Confirm Password" name="conpassword" value="<?php echo isset($_POST['conpassword']) ? htmlspecialchars($_POST['conpassword'], ENT_QUOTES) : ''; ?>" required>
        <button type="submit">Submit</button>
        <p>Already a member? <a href="login.php">Login</a></p>
      </form>
    </div>

    <?php
if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"])) {
  // Retrieve form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $number = $_POST['number'];
  $confirm_password = $_POST['conpassword'];
  
  // Connect to database
  $conn = mysqli_connect("localhost", "abhii", "Abhiishekth@1", "database");
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Check if the email already exists
  $check_email_query = "SELECT email FROM users WHERE email='$email'";
  $result = mysqli_query($conn, $check_email_query);
  if (mysqli_num_rows($result) > 0) {
    echo "<div class = 'error'>Email already exists. Please use a different email.</div>";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $insert_query = "INSERT INTO users (username, email, phonenumber, password) VALUES ('$username', '$email', '$number', '$hashed_password')";
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo "<div class = 'error'>Error: Invalid email format.</div>";
  }
  
  // Validate username
  elseif (!preg_match("/^[a-zA-Z0-9.]+$/", $username)) {
      echo "<div class = 'error'>Error: Invalid username format. Only letters, a dot and numbers are allowed.</div>";
  }
  
  // Validate password
  elseif (strlen($password) < 8) {
      echo "<div class = 'error'>Error: Password must be at least 8 characters long.</div>";
  } elseif (!preg_match("#[0-9]+#", $password)) {
      echo "<div class = 'error'>Error: Password must include at least one number.</div>";
  } elseif (!preg_match("#[a-zA-Z]+#", $password)) {
      echo "<div class = 'error'>Error: Password must include at least one letter.</div>";
  } elseif (!preg_match("#\W+#", $password)) {
      echo "<div class = 'error'>Error: Password must include at least one special symbol.</div>";
  } 

  //password confirmation
    elseif (empty($username) || empty($password) || empty($confirm_password)) {
      echo "<div class = 'error'>Please fill all fields.</div>";
  } else if ($password !== $confirm_password) {
      echo "<div class = 'error'>Passwords do not match.</div>";
  }

  // validate phone number
  
  elseif (!preg_match("/^\d{10}$/", $number)) {
    echo "<div class='error'>Invalid phone number, start with country code.</div>";
  }
    else {
      if (mysqli_query($conn, $insert_query)) {
      echo "<div class = 'success'>Sign up successful. You can now <a href = 'login.php'>Login</a></div>";
      echo "<a href = 'login.php'>Login</a>";
    } else {
      echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
    }
    
  }
  
  }

  // Close the database connection
  mysqli_close($conn);
}
?>

