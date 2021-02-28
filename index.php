<!DOCTYPE html>
<html lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<meta name="Description" content= "opis" />
	<meta name="Keywords" content="slowa kluczowe" />
	<title>praca</title>
	<link href="./files/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" href="files\image1.jpg"/>
</head>
<body>	
	<ul id="menu">
		<a href="./index.php"><li>Strona główna</li></a>
		<a href="?param=1"><li>Terrarium</li></a>
		<a href="?param=2"><li>Historia danych</li></a>
	</ul>
	<div id="main">
	<?php
	//łączenie z bazą danych SQL
	$pol = mysqli_connect('db.zut.edu.pl','pj41491','OqSSzSFt');
	if($pol) {
		$baza = mysqli_select_db($pol,'pj41491');
		if($baza) {
			// DLA ARDUINO
			//pobieranie danych z GET i zapisywanie w bazie
			if(isset($_GET["temperatura"])) {
				$temperatura = $_GET["temperatura"];
				$wilgotnosc = $_GET["wilgotnosc"];
				$dodawanie = "INSERT INTO dane (id_dane, data, temperatura, wilgotnosc) VALUES (NULL , CURRENT_TIMESTAMP, '$temperatura', '$wilgotnosc')";
				$dodaj = mysqli_query($pol,$dodawanie);
				$ustawiona = $_GET["ustawiona"];
				$aktualizuj = "UPDATE temperaturaZadana SET temperatura='$ustawiona', dataZmiany=CURRENT_TIMESTAMP WHERE id=1";
				$dodaj = mysqli_query($pol,$aktualizuj);
			}

			// PANEL KONTROLNY HTTP
			if(isset($_GET["param"])) {
				if($_GET["param"] == 1) {
					if(isset($_POST["wyslij"])) {
						if($_POST["tempUst"] != "" && $_POST["haslo"] != "") {
							$zapytanie = "SELECT * FROM temperaturaZadana WHERE id=1";
							$zap = mysqli_query($pol,$zapytanie);
							$haslo = "";
							while($row = $zap->fetch_assoc()) {
								$haslo = $row["haslo"];
							}
							if($_POST["haslo"] == $haslo) {
								$ustawiona = $_POST["tempUst"];
								$aktualizuj = "UPDATE temperaturaZadana SET temperatura='$ustawiona', dataZmiany=CURRENT_TIMESTAMP WHERE id=1";
								$dodaj = mysqli_query($pol,$aktualizuj);
							}
							else {
								$_POST["wyslij"] = "bledne haslo";
							}
							
						}
						else {
							$_POST["wyslij"] = "error";
						}
						
					}
					$zapytanie = "SELECT * FROM dane ORDER BY data DESC LIMIT 0, 1";
					$zap = mysqli_query($pol,$zapytanie);
					while($row = $zap->fetch_assoc()) {
						echo "<div id='info'><b>Temperatura: </b>" . $row['temperatura'] . " °C";
						echo "<br><br><b>Wilgotność: </b>" . $row['wilgotnosc'] . " %";
						echo "<br><br><i>Ostatnie odebrane dane o: " . $row['data'] . "</i>";
					}
					$zapytanie = "SELECT * FROM temperaturaZadana WHERE id=1";
					$zap = mysqli_query($pol,$zapytanie);
					while($row = $zap->fetch_assoc()) {
						echo "<br><br><b>Temperatura zadana: </b>" . $row['temperatura'] . " °C";
					}
					echo "<br><br><br><table align='center'><form method='POST'>
						<tr><td>Temperatura:</td><td><input type='number' placeholder='°C' name='tempUst' min='15' max='40'></td></tr>
						<tr><td>Hasło:</td><td><input type='password' placeholder='haslo' name='haslo'></td></tr>
						<tr><td><input type='submit' name='wyslij' value='Ustaw temperature'></td></tr>
						</form></table>";
					if(isset($_POST["wyslij"])) {
						if($_POST["wyslij"] == "Ustaw temperature") {
							echo "<br><i>Ustawiono!</i></div>";
						}
						else if($_POST["wyslij"] == "error"){
							echo "<br><i>Niewypełniony formularz!</i></div>";
						}
						else {
							echo "<br><i>Błędne hasło!</i></div>";
						}
					}
					
				}
				elseif($_GET["param"] == 2) {
					echo "<table id ='dane'>
						<tr>
							<th>Lp.</th>
							<th>Data</th>
							<th>Temperatura</th>
							<th>Wilgotność</th>
						</tr>";
					// wyświetlanie danych
					$zapytanie = "SELECT * FROM dane ORDER BY id_dane DESC";
					$zap = mysqli_query($pol,$zapytanie);
					while($row = $zap->fetch_assoc()) {
						echo "<tr>
						<td>" . $row['id_dane'] . "</td>
						<td>" . $row['data'] . "</td>
						<td>" . $row['temperatura'] . "</td>
						<td>" . $row['wilgotnosc'] . "</td>
						</tr>";
					}
					echo "</table>";
				}
				else {
					echo "<b>brak param</b><br>";
				}
			}
			else {
				echo "<b>Witam, oto mój projekt. :)</b><br>
					<br><img id='zdjecie' src='files/image2.JPEG' alt='zdjecie arduino' style='width: 350px; height: auto;'>";
			}
			/*if(tr) {
				mysqli_close($pol);
			}
			else {
				echo 'kom'.mysqli_error($pol);
			}*/
			mysqli_close($pol);
		}
		else {
			echo 'kom'.mysqli_error($baza);
		}
	}
	else {
		echo 'kom'.mysqli_error($pol);
	}
	?>
	</div>
</body>
</html> 