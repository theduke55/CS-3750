<html>
	<form action="/MainScreen.html" target="_self" method="POST">
		<input type="submit" name="mainScreen" value="Main Screen"><br>
		<?php echo "First Name | Last Name | Score" ?><br>
		
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
		$sql = "SELECT * FROM HighScoresTable ORDER BY Score LIMIT 10";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			 echo $row["First"] . " | " . $row["Last"] . " | " . $row["Score"]. "<br>";
		}
		
		$conn->close();
		
		?>
	</form>
</html>