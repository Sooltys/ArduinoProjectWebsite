<?php
	//łączenie z bazą danych SQL
	$pol = mysqli_connect('db.zut.edu.pl','pj41491','OqSSzSFt');
	if($pol) {
		$baza = mysqli_select_db($pol,'pj41491');
		if($baza) {
			// wyświetlanie danych
			$zapytanie = "SELECT * FROM temperaturaZadana WHERE id=1";
            $zap = mysqli_query($pol,$zapytanie);
            echo "?";
			while($row = $zap->fetch_assoc()) {
				echo $row['temperatura'];
            }
			mysqli_close($pol);
		}
		else {
			//echo 'kom'.mysqli_error($baza);
		}
	}
	else {
		//echo 'kom'.mysqli_error($pol);
	}
	?>