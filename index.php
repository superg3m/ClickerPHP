<!DOCTYPE html>
<html>

<head>
  <title>Cookie Clicker Game</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="./style.css" />
</head>

<body>
  <?php
  require_once "Database.php";
  require_once "User.php";

  // Database Configuration
  $server = "localhost";
  $username = "root";
  $password = "P@55word";
  $databaseName = "CookieBase";

  // Establish your database connection
  $databaseObject = new Database($server, $username, $password, $databaseName);
  $connection = $databaseObject->getConnection();

  // User Management
  $userObject = new User($connection);

  // Check if a user ID is submitted for deletion
  if (isset($_POST["delete"])) {
    $userIdToDelete = $_POST["delete"];
    $userObject->deleteUser($userIdToDelete);
  }

  $username = isset($_POST["username"]) ? $_POST["username"] : null;

  if ($username) {
    $userObject->username = $username;
  ?>
    <p>Logged in as <?php echo $userObject->username; ?></p>
  <?php
  } else {
  ?>
    <p>Logged in as Guest</p>
  <?php
  }
  ?>

  <!-- User Login Form -->
  <form method="post">
    <label for="Username">Username</label>
    <input type="text" name="username" id="Username" required />
    <label for="Password">Password</label>
    <input type="password" name="password" id="Password" required />
    <button type="submit" name="submit">Submit</button>
  </form>
  <?php
  if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if a user with the given username already exists
    if ($username && $password && !$userObject->queryUser($username, $password)) {
      $userObject->createUser($username, $password, 0);

      // Optionally, you can redirect the user to a new page after successful submission
      // header("Location: new_page.php");
    } else {
      echo "Username already exists or invalid username/password. Please choose a different username or check your input.";
    }
  }
  ?>

  <div class="titleContainer">
    <h1>Cookie Clicker Game</h1>
    <p>Click the cookie to earn points!</p>
    <p>Points: <span id="score">
        <?php
        // Assuming $userObject is an object with the property currentCookieCount
        echo $userObject->currentCookieCount ?? 0;
        ?>
      </span></p>
    <button class="cookie-button" onclick="updateCookieCount()">Click me!</button>
  </div>

  <script>
    function updateCookieCount() {
      var currentScore = parseInt(document.getElementById('score').innerText, 10);
      currentScore++;
      document.getElementById('score').innerText = currentScore;

      var username = '<?php echo $userObject->username; ?>'; // Set the username here
      var cookieCount = currentScore + <?php echo $userObject->queryCookieCount(); ?>

      // Send AJAX request to update the cookie count to the database
      $.ajax({
        url: "save_cookie_count.php", // Replace with the correct path to your PHP script for saving the cookie count
        type: "POST",
        data: {
          cookie: cookieCount,
          username: username
        },
        success: function(response) {
          console.log('Cookie count saved to the database.');
        },
        error: function(xhr, status, error) {
          console.error('Error saving cookie count: ' + error);
        }
      });
    }
  </script>


  <?php
  // Fetch data from the "users" table
  $sql = "SELECT * FROM users";
  $result = mysqli_query($connection, $sql);

  // Check if there are any records
  if (mysqli_num_rows($result) > 0) {
    // Output table header
    echo "<table>";
    echo "<tr>
            <th>ID</th>
            <th>Username</th>
            <th>Password</th>
            <th>Cookie</th>
            <th>Action</th>
          </tr>";

    // Loop through each row and display the data
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td>" . $row["id"] . "</td>";
      echo "<td>" . $row["username"] . "</td>";
      echo "<td>" . $row["password"] . "</td>";
      echo "<td>" . $row["cookie"] . "</td>";
      echo "<td>
              <form method='post'>
                <button type='submit' name='delete' value='" . $row["id"] . "'>Delete</button>
              </form>
            </td>";
      echo "</tr>";
    }

    // Close the table
    echo "</table>";
  } else {
    // No records found
    echo "No records found.";
  }

  // Close the connection and free resources
  $databaseObject->closeConnection();
  ?>

</body>

</html>