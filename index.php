<?php

	$budget = 300;
	$duration  = 3;

	if(isset($_GET)) {
        if(isset($_GET['budget'])) {
            $budget = $_GET['budget'];
        } else {
            $budget = 300;
        }
        if(isset($_GET['duration'])) {
            $duration = $_GET['duration'];
        } else {
            $duration = 3;
        }
    }

	$urlRest = "http://dforce-hack.herokuapp.com/wrapper.php?zip=94101&date=10%20Oct,%202014&budget=".$budget."&api=restaurants";
    $urlEvents = "http://dforce-hack.herokuapp.com/wrapper.php?zip=94101&date=10%20Oct,%202014&budget=".$budget."&api=events";
    $urlEventsSports = "http://dforce-hack.herokuapp.com/wrapper.php?zip=94101&date=10%20Oct,%202014&budget=".$budget."&api=events_sports";
    

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlRest);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);

	function getPackages($budget, $duration, $json_in)
        {
            $output = json_decode($json_in, true);
            $pkg_file_length = count($output);
            for ($i = 0; $i < $pkg_file_length; $i++) {
                $price_arr[$i] = $output[$i]['price'];
            }
            $sorted = arsort($price_arr);

            $pack_arr = array();
            if ($budget > 50 && $duration > 2) {
                //if true run two packages run
                $pack_arr = twoPackages($price_arr, $budget);
            } else {

                $pack_arr[0] = pickLast($price_arr, $budget);
            }
            //print_r($pack_arr);

            foreach ($pack_arr as $key => $value) {

                $cool_arr[$key] = $output[$value];
            }
            //print_r($cool_arr);
            $json_out = json_encode($cool_arr);


            //$output['package'][$pack_arr[$value]];
            return $json_out;
        }


        function twoPackages($p_arr, $b)
        {

            $package_arr = array();

            $first_random_package = pickFirst($p_arr, $b);
            //echo $first_random_package;
            $package_arr[0] = $first_random_package;
            //print_r($package_arr);
            //print("awesome");
            //print($p_arr[$first_random_package]);
            //print("<br />");
            $new_budget = $b - $p_arr[$first_random_package];
            //print("b:". $b . "v new budget" . $new_budget);
            unset($p_arr[$first_random_package]);
            //$new_budget = $budget - $output['package'][$first_random_package]['price'];
            $last_random_package = pickLast($p_arr, $new_budget);
            $package_arr[1] = $last_random_package;
            return $package_arr;
        }

        function pickLast($trunc_sorted_price_arr, $budg)
        {
            $retrunc_sorted_price_arr = array();
            //truncate to get only prices near budget

            foreach ($trunc_sorted_price_arr as $key => $value) {

                if ($budg >= $value && $value >= (.7 * $budg)) {
                    $retrunc_sorted_price_arr[$key] = $value;
                }
            }
            //pick random from trunc 100%-70% price list
            $second_package = array_rand($retrunc_sorted_price_arr);
            //print_r($retrunc_sorted_price_arr);
            $length = count($retrunc_sorted_price_arr);
            //if can't find near budget truncate to find any price below budget
            if ($length == 0) {

                foreach ($trunc_sorted_price_arr as $key => $value) {
                    if ($budg >= $value && $value >= (.4 * $budg)) {
                        $retrunc_sorted_price_arr[$key] = $value;
                    }
                }
                //pick random from trunc 100%-40% price list
                $second_package = array_rand($retrunc_sorted_price_arr);
                $length = count($retrunc_sorted_price_arr);
            }
            if ($length == 0) {
                //printf("Nothing above 40");
                foreach ($trunc_sorted_price_arr as $key => $value) {
                    if ($budg >= $value) {
                        $retrunc_sorted_price_arr[$key] = $value;
                    }
                }
                //pick random from trunc price list
                $second_package = array_rand($retrunc_sorted_price_arr);
            }
            //print("test: " . $second_package);
            return $second_package;
        }

        function pickFirst($sorted_price_arr, $budge)
        {
            $truncate_sorted_price_arr = array();
            //truncate sorted price array
            foreach ($sorted_price_arr as $key => $value) {
                if ($budge >= $value) {
                    $truncate_sorted_price_arr[$key] = $value;
                }
            }
            //random value from truncated and sorted price array
            $first_package = array_rand($truncate_sorted_price_arr);

            return $first_package;
        }
	

		$test = getPackages($budget, $duration, $data);
		print_r($test);
?>