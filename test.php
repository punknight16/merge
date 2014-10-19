<?php
 		 $urlRest = "http://dforce-hack.herokuapp.com/wrapper.php?zip=94101&date=10%20Oct,%202014&budget=500&api=restaurants";
        $urlEvents = "http://dforce-hack.herokuapp.com/wrapper.php?zip=94101&date=10%20Oct,%202014&budget=500&api=events";
        $urlEventsSports = "http://dforce-hack.herokuapp.com/wrapper.php?zip=94101&date=10%20Oct,%202014&budget=500&api=events_sports";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlRest);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);

        $concerts = json_decode($data);
        $concertsArray = array();
?>

<html>
<head>
		<title>Budgeted Search Results</title>
		<style type="text/css">body { font-family: arial,sans-serif;} </style>
</head>
<body>
		<h1>Budgeted Search Results</h1>
		<div id="results">
			<?php echo $data; ?>

		</div>
		
</body>
</html>