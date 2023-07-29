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

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cookie"]) && isset($_POST["username"])) {
  $cookieCount = $_POST["cookie"];
  $username = $_POST["username"];

  // Update the cookie count in the database
  $userObject->updateCookieCountInDatabase($username, $cookieCount);

  // Respond to the AJAX request (optional)
  echo 'Cookie count saved successfully';
}

// Close the connection and free resources
$databaseObject->closeConnection();
