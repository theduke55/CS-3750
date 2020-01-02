<!DOCTYPE html>

<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
// Login stuff -- see the README for more about how this all works.
$requestTokenExists = isset($_REQUEST['token']);
$savedTokenExists = isset($_SESSION['token']);
if ($requestTokenExists && $savedTokenExists) {
  $savedTokenMatchesRequestToken = $_REQUEST['token'] ===  $_SESSION['token'];
}
// First, a quick check for an error condition: if there is no token
// coming in the request AND none saved in the session either,
// that means the user is hitting this page for the first time,
// yet they aren't coming from the sign in page. That is an
// error.
if (! $requestTokenExists && ! $savedTokenExists) {
  echo '<p>ERROR: It appears that you have never logged in.</p>' .
      '<p>Please log in via the sign-up page before trying to play the game.</p>';
  exit();
}
if ($requestTokenExists && (!$savedTokenExists || !$savedTokenMatchesRequestToken)) {
  // A token has come in the request, and either there is no saved token (i.e.
  // the user is visiting for the first time) OR there is a token saved but
  // it doesn't match the one arriving in the request (i.e., the user was already
  // signing in and is now signing in again).
  //
  // We save the new token into the session (this will override the old one,
  // if it was there) and record that a new token has been received.
  $_SESSION['token'] = $_REQUEST['token'];
  $_SESSION['tokenIsNew'] = true;
}  
?>

<html>
<head>
<title>Minesweeper</title>
<link rel="stylesheet" href="styles.css"></style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="main.js"></script>
</head>
<body>

    <form method="POST" action="/LoginScreen.php">
    <button type="submit" id="Login">Log Out</button>
	<input type="hidden" id="decision" name="decision" value="logout">
    </form> 
<div id="topDiv">
  <div id="flagCount"></div>
  <div id="won"></div>
<div id="lost"></div>
  <div id="timer">00:00:00</div>
</div>
<div id = "mainDiv">
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 0, 0)" data-x="0" data-y="0"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 1, 0)" data-x="1" data-y="0"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 2, 0)" data-x="2" data-y="0"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 3, 0)" data-x="3" data-y="0"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 4, 0)" data-x="4" data-y="0"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 5, 0)" data-x="5" data-y="0"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 6, 0)" data-x="6" data-y="0"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 7, 0)" data-x="7" data-y="0"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 8, 0)" data-x="8" data-y="0"></button>


  <button type="button" class="mineButton" onclick="handleCellClicked(this, 0, 1)" data-x="0" data-y="1"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 1, 1)" data-x="1" data-y="1"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 2, 1)" data-x="2" data-y="1"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 3, 1)" data-x="3" data-y="1"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 4, 1)" data-x="4" data-y="1"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 5, 1)" data-x="5" data-y="1"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 6, 1)" data-x="6" data-y="1"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 7, 1)" data-x="7" data-y="1"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 8, 1)" data-x="8" data-y="1"></button>



  <button type="button" class="mineButton" onclick="handleCellClicked(this, 0, 2)" data-x="0" data-y="2"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 1, 2)" data-x="1" data-y="2"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 2, 2)" data-x="2" data-y="2"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 3, 2)" data-x="3" data-y="2"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 4, 2)" data-x="4" data-y="2"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 5, 2)" data-x="5" data-y="2"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 6, 2)" data-x="6" data-y="2"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 7, 2)" data-x="7" data-y="2"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 8, 2)" data-x="8" data-y="2"></button>



  <button type="button" class="mineButton" onclick="handleCellClicked(this, 0, 3)" data-x="0" data-y="3"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 1, 3)" data-x="1" data-y="3"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 2, 3)" data-x="2" data-y="3"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 3, 3)" data-x="3" data-y="3"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 4, 3)" data-x="4" data-y="3"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 5, 3)" data-x="5" data-y="3"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 6, 3)" data-x="6" data-y="3"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 7, 3)" data-x="7" data-y="3"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 8, 3)" data-x="8" data-y="3"></button>


  <button type="button" class="mineButton" onclick="handleCellClicked(this, 0, 4)" data-x="0" data-y="4"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 1, 4)" data-x="1" data-y="4"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 2, 4)" data-x="2" data-y="4"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 3, 4)" data-x="3" data-y="4"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 4, 4)" data-x="4" data-y="4"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 5, 4)" data-x="5" data-y="4"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 6, 4)" data-x="6" data-y="4"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 7, 4)" data-x="7" data-y="4"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 8, 4)" data-x="8" data-y="4"></button>


  <button type="button" class="mineButton" onclick="handleCellClicked(this, 0, 5)" data-x="0" data-y="5"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 1, 5)" data-x="1" data-y="5"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 2, 5)" data-x="2" data-y="5"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 3, 5)" data-x="3" data-y="5"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 4, 5)" data-x="4" data-y="5"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 5, 5)" data-x="5" data-y="5"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 6, 5)" data-x="6" data-y="5"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 7, 5)" data-x="7" data-y="5"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 8, 5)" data-x="8" data-y="5"></button>



  <button type="button" class="mineButton" onclick="handleCellClicked(this, 0, 6)" data-x="0" data-y="6"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 1, 6)" data-x="1" data-y="6"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 2, 6)" data-x="2" data-y="6"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 3, 6)" data-x="3" data-y="6"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 4, 6)" data-x="4" data-y="6"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 5, 6)" data-x="5" data-y="6"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 6, 6)" data-x="6" data-y="6"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 7, 6)" data-x="7" data-y="6"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 8, 6)" data-x="8" data-y="6"></button>



  <button type="button" class="mineButton" onclick="handleCellClicked(this, 0, 7)" data-x="0" data-y="7"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 1, 7)" data-x="1" data-y="7"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 2, 7)" data-x="2" data-y="7"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 3, 7)" data-x="3" data-y="7"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 4, 7)" data-x="4" data-y="7"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 5, 7)" data-x="5" data-y="7"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 6, 7)" data-x="6" data-y="7"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 7, 7)" data-x="7" data-y="7"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 8, 7)" data-x="8" data-y="7"></button>




  <button type="button" class="mineButton" onclick="handleCellClicked(this, 0, 8)" data-x="0" data-y="8"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 1, 8)" data-x="1" data-y="8"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 2, 8)" data-x="2" data-y="8"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 3, 8)" data-x="3" data-y="8"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 4, 8)" data-x="4" data-y="8"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 5, 8)" data-x="5" data-y="8"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 6, 8)" data-x="6" data-y="8"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 7, 8)" data-x="7" data-y="8"></button>
  <button type="button" class="mineButton" onclick="handleCellClicked(this, 8, 8)" data-x="8" data-y="8"></button>
</div>
<button type="button" class="flipping" id="flips" onclick="flipFlagging(this)">Flag</button>

<div id="toType"></div>

</body>
</html>