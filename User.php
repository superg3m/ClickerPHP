<?php

class User
{
  private $connection;
  public $username;
  public $currentCookieCount;

  public function __construct($connection)
  {
    $this->connection = $connection;
  }

  public function createUser($username, $password, $cookieCount)
  {
    $this->username = $username;
    $this->currentCookieCount = $cookieCount;

    $sql = "INSERT INTO users (username, password, cookie) VALUES (?, ?, ?)";
    $statement = $this->connection->prepare($sql);
    $statement->bind_param("ssi", $this->username, $password, $this->currentCookieCount);
    $statement->execute();
  }

  public function queryUser($username, $password)
  {
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $statement = $this->connection->prepare($sql);
    $statement->bind_param("ss", $username, $password);
    $statement->execute();
    $result = $statement->get_result();
    return $result->num_rows > 0;
  }

  public function queryCookieCount()
  {
    $sql = "SELECT cookie FROM users WHERE username = ?";
    $statement = $this->connection->prepare($sql);
    $statement->bind_param("s", $this->username);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['cookie'];
    } else {
      // User not found or has no cookies, return 0
      return 0;
    }
  }

  public function updateCookieCountInDatabase($username, $cookie)
  {
    $this->currentCookieCount = $cookie;
    $sql = "UPDATE users SET cookie = ? WHERE username = ?";
    $statement = $this->connection->prepare($sql);
    $statement->bind_param("is", $this->currentCookieCount, $username);
    $statement->execute();
  }

  public function deleteUser($userId)
  {
    $sql = "DELETE FROM users WHERE id = ?";
    $statement = $this->connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();
  }
}
