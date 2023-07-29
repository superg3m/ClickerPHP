<?php
// Database Class for managing database operations
class Database
{
  private $connection;

  public function __construct($server, $username, $password, $dbname)
  {
    $this->connection = new mysqli($server, $username, $password, $dbname);

    if ($this->connection->connect_error) {
      die("Connection failed: " . $this->connection->connect_error);
    }
  }

  public function getConnection()
  {
    return $this->connection;
  }

  public function closeConnection()
  {
    $this->connection->close();
  }
  // Add this method to the User class to delete a user by ID
}
