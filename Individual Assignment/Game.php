<!--Start the session-->
<?php session_start(); ?>

<html>
	<body>
		<?php
			//Assign the first name session variable from the UserInformation webpage.
			$_SESSION["firstName"] = $_POST['firstName'];
			
			//Assign the last name session variable from the UserInformation webpage.
			$_SESSION["lastName"] = $_POST['lastName'];
			
			//Assign a local variable to store the users current guess.
			$usersGuess = $_POST['guess'];
			
			//Instantiate a local variable for the correct answer.
			//$answer = NULL;
			
			//Instantiate a session variable to identify when the user has guessed the correct number.
			$_SESSION["gameOver"] = false;
			
			
			//If the user submitted a guess and the guess is less than the random number...
			if($usersGuess != NULL && $usersGuess < $_SESSION["randomNumber"])
			{
				//Change the font to blue and give a low warning to the user.
				echo "<font color = blue>Your guess was: Too Low</font>";
				
				//Increment the number of times the user has guessed.
				$_SESSION["numberOfGuesses"] += 1;
			}
			//If the user submitted a guess and the guess was greater than the random number... 
			elseif($usersGuess != NULL && $usersGuess > $_SESSION["randomNumber"])
			{
				//Change the font to red and give a high warning to the user.
				echo "<font color = red>Your guess was: Too High</font>";
				
				//Increment the number of times the user has guessed.
				$_SESSION["numberOfGuesses"] += 1;
			}
			//If the user submitted a guess and it was the same as the random number...
			elseif($usersGuess != NULL && $usersGuess == $_SESSION["randomNumber"])
			{
				//Change the font color to green and notify the user of the win.
				echo "<font color = green>Your guess was: CORRECT!!</font>";
				
				++$_SESSION["numberOfGuesses"];
				//Change the gameOver to identify the user selected the correct number.
				$_SESSION["gameOver"] = true;
			}
		?>
		
		<!--If the gameOver variable is set to true then display the button to view the HighScores webpage.-->
		<?php if($_SESSION["gameOver"]) : ?>
			<form action="/HighScores.php" method="POST">
				Congratulations <?php echo $_SESSION["firstName"] . " " .$_SESSION["lastName"] ?>!<br>
				You won!<br>
				<input type="submit" value="See High Scores"/>
				
				<?php
				// service side code
					$servername = "sql211.epizy.com";
					$username = "epiz_23878166";
					$password = "mm2OVpxtfruY";
					$dbname = "epiz_23878166_3750Barker";
					
				// Create connection
				$conn = new mysqli($servername, $username, $password, $dbname);
				// Check connection
				if($conn->connect_error){
					die("Connection failed: " . $conn->connect_error);
				}
				$sql = "INSERT INTO HighScoresTable (First, Last, Score) VALUES ('{$_SESSION["firstName"]}', '{$_SESSION["lastName"]}', {$_SESSION["numberOfGuesses"]})";
				$result = $conn->query($sql);
				
				$sql = 
					"SELECT * FROM (
						SELECT *, (@rownum := @rownum + 1) AS position
						FROM HighScoresTable hst
						JOIN (SELECT @rownum := 0) r
						ORDER BY hst.First, hst.Last
					)x	
					WHERE x.First = '{$_SESSION["First"]}' and x.Last = '{$_SESSION["Last"]}'
					LIMIT 1";
					
				$result = $conn->query($sql);
				$result = $result->fetch_assoc();
				
				$sql = "SELECT COUNT(*) AS num FROM HighScoresTable";
				$totalPlayers = $conn->query($sql);
				$totalPlayers = $totalPlayers->fetch_assoc();
				
				
				echo "You are rank: {$result["position"]} out of {$totalPlayers["num"]}";
				
				$conn->close();
				
				?>
					</form>
				<?php endif;?>
		
		<!--If the gameOver variable is set to false then allow the user to continue playing.-->
		<?php if(!$_SESSION["gameOver"]) : ?>
		<form method="POST">
			Welcome <?php echo $_SESSION["firstName"] . " " .$_SESSION["lastName"] ?><br>
			Guess a number between 1-100: <input type="text" name="guess">
			<input type="submit" name="submit"><br>
			You guessed: <?php echo $usersGuess ?><br>
			Number of guesses: <?php echo $_SESSION["numberOfGuesses"] ?><br>
			Random number: <?php echo $_SESSION["randomNumber"] ?>
			<!--Pass the first and last names to the next form upon submission-->
			<input type="hidden" name="firstName" value="<?= $_POST['firstName'] ?>">
			<input type="hidden" name="lastName" value="<?= $_POST['lastName'] ?>">
		</form>
		<?php endif;?>
	</body>
</html>