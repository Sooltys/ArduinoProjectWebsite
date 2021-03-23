<!DOCTYPE html>
<html lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<meta name="Description" content= "opis" />
	<meta name="Keywords" content="slowa kluczowe" />
    <title>praca</title>
	<link rel="icon" href="files\image1.jpg"/>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {packages: ['corechart', 'line']});
        google.charts.setOnLoadCallback(drawCurveTypes);

        function drawCurveTypes() {
            var data = new google.visualization.DataTable();
            data.addColumn('number', 'X');
            data.addColumn('number', 'Temperatura odczytana');
            data.addColumn('number', 'Temperatura zadana');

            data.addRows([
                <?php
                $pol = mysqli_connect('db.zut.edu.pl','pj41491','OqSSzSFt');
                if($pol) {
                    $baza = mysqli_select_db($pol,'pj41491');
                    if($baza) {
                        $numberOfSamples = '30';
                        if(isset($_POST["numberOfSamples"]) && $_POST["numberOfSamples"] != 0) {
                            $numberOfSamples = $_POST["numberOfSamples"];
                        }
                        //$zapytanie = "SELECT * FROM dane ORDER BY data DESC LIMIT 0, 10";
                        $zapytanie = "SELECT * FROM dane WHERE id_dane > (SELECT MAX(id_dane) - ". $numberOfSamples ." FROM dane)";
                        $zap = mysqli_query($pol,$zapytanie);
                        $i = 1;
                        while($row = $zap->fetch_assoc()) {
                            echo "[" 
                            . $i . ", " 
                            . $row['temperatura'] . ", " 
                            . $row['tempZadana'] 
                            . "], ";
                            $i += 1;
                        }
                        mysqli_close($pol);
                    }
                }
                ?>
            ]);

            var options = {
                height: 600,
                hAxis: {
                title: 'Ostatnie pomiary temperatury ( najnowsza po prawej )'
                },
                vAxis: {
                title: 'Temperatura ( °C )'
                }
            };

            var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>

    <a href="./index.php?param=2" style="margin: 30px;">
        <img src="files/home.png" style="width: 45px; ">
    </a>

    <h3>
        <b>Liczba próbek na wykresie:</b> <?php 
            $numberOfSamples = '30';
            if(isset($_POST["numberOfSamples"]) && $_POST["numberOfSamples"] != 0) {
                $numberOfSamples = $_POST["numberOfSamples"];
            } 
            echo $numberOfSamples;
        ?>
    </h3>

    <div id="chart_div" ></div>

</body>
</html> 