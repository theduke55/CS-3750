<html>
	<body>
		<p>
		<?php
		// service side code
			$servername = "sql108.epizy.com";
			$username = "epiz_23868825";
			$password = "1oRXmaYsb";
			$dbname = "epiz_23868825_3750peterson";
			
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if($conn->connect_error){
			die("Connection failed: " . $conn->connect_error);
		}
		$sql = "SELECT ID, First, Last FROM MyFirstTable";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			echo "Got something";
		} else{
			echo "Got nothing";
		}
		
		$conn->close();
		
		?>
		</p>
	</body>
</html>