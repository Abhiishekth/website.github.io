<!-- Login page -->
<!DOCTYPE html>
<html>
  <head>
    <style>
      .login-container,
      .signup-container {
       width: 400px;
  margin: 100px auto;
  text-align: center;
  margin-top:200px;
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

input[type="submit"] {
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
  font-size: 20px;
}

    </style>
  </head>
  <body>
    <div class="login-container">
      <form action="" method="post">
        <h2>Login</h2>
        <input type="text" placeholder="Username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : ''; ?>" required>
        <input type="password" placeholder="Password" name="password" required>
        <input type="submit" name="submit"></input>
        <p>Not a member yet? <a href="signup.php">Sign up</a></p>
      </form>
    </div>

    <?php
        
          $conn = mysqli_connect("localhost", "root", "", "database");
          
          // Check for connection errors
          if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
          }
          
          if(isset($_POST['submit'])){
          // Get the entered username and password
          $username = $_POST['username'];
          $password = $_POST['password'];
    
          // Query the database for the entered username
          $query = "SELECT * FROM users WHERE username = '$username'";
          $result = mysqli_query($conn, $query);

          // If a matching username is found
          if (mysqli_num_rows($result) == 1) {
            // Get the password hash for the matching username
            $user = mysqli_fetch_assoc($result);
            $hash = $user['password'];

            // Verify the entered password against the password hash
            if (password_verify($password, $hash)) {
              // Start a session and redirect the user to the dashboard page
              session_start();
              $_SESSION['username'] = $username;
              header("Location: category\display.php");
              exit;
            } else {
              // Show an error message if the password is incorrect
              echo "<div class='error'>Incorrect password</div>";
            }
          } else {
            // Show an error message if the username is not found
            echo "<div class='error'>Incorrect username</div>";
          }
          mysqli_close($conn);
        } 
        ?>

  </body>
</html>

