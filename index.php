<!DOCTYPE html>
<html>

<head>
  <title>Cookie Clicker Game</title>
  <link rel="stylesheet" href="./style.css" />
</head>

<body>
  <div class="titleContainer">
    <h1>Cookie Clicker Game</h1>
    <p>Click the cookie to earn points!</p>
    <p>Points: <span id="score"><?php echo isset($_COOKIE['score']) ? $_COOKIE['score'] : 0; ?></span></p>
  </div>
  <div class="cookieContainer">
    <button class="cookie-button" onclick="updateScore()"></button>
  </div>

  <script>
    // Java Script Scope
    // isset is used to see if a variable is null or not set for the most part
    var score = <?php
                if (isset($_COOKIE['score'])) {
                  echo $_COOKIE['score'];
                } else {
                  echo 0;
                }
                ?>;
    var scoreElement = document.getElementById('score');

    function updateScore() {
      score++;
      scoreElement.textContent = score;

      document.cookie = "score=" + score + "; path=/";
    }
  </script>
</body>

</html>

<?php
setcookie("score", 0, time() + (86400 * 1), "/");
?>