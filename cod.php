<?php

$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://call-of-duty-modern-warfare.p.rapidapi.com/warzone-matches/Nanerottolo%2525232894966/uno",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"x-rapidapi-host: call-of-duty-modern-warfare.p.rapidapi.com",
		"x-rapidapi-key: 8b33a17131msh9d6deba69445fdfp1de439jsn051b86723103"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	echo $response;
}
