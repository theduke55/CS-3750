<!--Start the session-->
<?php session_start() ?>

<html>
	<!--This form will collect the players first and last name and submit it to the Game website-->
	<form action="/Game.php" target="_self" method="POST">
		First Name: <input type="text" name="firstName">
		Last Name: <input type="text" name="lastName"><br>
		
		<!--Two session variables are created: 1 for the random number to guess and 1 for the total number of guesses made by the player-->
		<?php 
			$_SESSION["randomNumber"] = rand(1, 100);
			$_SESSION["numberOfGuesses"] = 0;
		?>
		<input type="submit" name="submit">
	</form>
</html>