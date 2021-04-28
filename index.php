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
	require('connect.php');
	$pol = mysqli_connect($host, $login, $password);
	if($pol) {
		$baza = mysqli_select_db($pol, $database);
		if($baza) {
			// DLA ARDUINO
			//pobieranie danych z GET i zapisywanie w bazie
			if(isset($_GET["temperatura"])) {
				$temperatura = $_GET["temperatura"];
				$wilgotnosc = $_GET["wilgotnosc"];
				$ustawiona = $_GET["ustawiona"];
				$dodawanie = "INSERT INTO dane (id_dane, data, temperatura, wilgotnosc, tempZadana, time, date) VALUES (NULL , CURRENT_TIMESTAMP, '$temperatura', '$wilgotnosc', '$ustawiona', now(), now())";
				$dodaj = mysqli_query($pol,$dodawanie);
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
						echo "<br><br><i>Ostatnie odebrane dane o: <b>" . $row['time'] . "</b><br>" . $row['date'] . "</i>";
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
					echo "
						<form action='./wykres.php' method='post'>
							<label for='numberOfSamples' style='margin: 5px 10px;'>Ilość ostatnich pomiarów:</label><br>
							<input type='number' min='5' max='99' id='numberOfSamples' name='numberOfSamples' style='margin: 5px 10px;'><br>
							<input type='submit' value='Pokaż wykres' style='margin: 5px 10px;'>
				  		</form><br>
					";
					echo "<table id ='dane'>
						<tr>
							<th>Lp.</th>
							<th>Temperatura</th>
							<th>Wilgotność</th>
							<th>Czas</th>
							<th>Data</th>
						</tr>";
					// wyświetlanie danych
					$zapytanie = "SELECT * FROM dane ORDER BY id_dane DESC";
					$zap = mysqli_query($pol,$zapytanie);
					while($row = $zap->fetch_assoc()) {
						echo "<tr>
						<td>" . $row['id_dane'] . "</td>
						<td>" . $row['temperatura'] . "</td>
						<td>" . $row['wilgotnosc'] . "</td>
						<td>" . $row['time'] . "</td>
						<td>" . $row['date'] . "</td>
						</tr>";
					}
					echo "</table>";
				}
				else {
					echo "<b>brak param</b><br>";
				}
			}
			else {
				echo "<b>Panel kontrolny</b> urządzenia sterującego temperaturą w terrarium.<br>
					<br><img id='zdjecie' src='files/zdjGotowe1.JPEG' alt='zdjecie arduino2' style='width: 350px; height: auto;'>
				";
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