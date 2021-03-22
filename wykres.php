<!DOCTYPE html>
<html lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<meta name="Description" content= "opis" />
	<meta name="Keywords" content="slowa kluczowe" />
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {packages: ['corechart', 'line']});
        google.charts.setOnLoadCallback(drawCurveTypes);

        function drawCurveTypes() {
            var data = new google.visualization.DataTable();
            data.addColumn('number', 'X');
            data.addColumn('number', 'Temp');
            data.addColumn('number', 'Zadana');

            data.addRows([
                <?php
                $pol = mysqli_connect('db.zut.edu.pl','pj41491','OqSSzSFt');
                if($pol) {
                    $baza = mysqli_select_db($pol,'pj41491');
                    if($baza) {
                        //$zapytanie = "SELECT * FROM dane ORDER BY data DESC LIMIT 0, 10";
                        $zapytanie = "SELECT * FROM dane WHERE id_dane > (SELECT MAX(id_dane) - 20 FROM dane)";
                        $zap = mysqli_query($pol,$zapytanie);
                        $i = 0;
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
                hAxis: {
                title: 'Ostatnie 20 próbek'
                },
                vAxis: {
                title: 'Temperatura'
                },
                series: {
                1: {curveType: 'function'}
                }
            };

            var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>

    <div id="chart_div" style="height: 600px;"></div>


</body>
</html> 