<?php
$headers = [
  'Authorization: Bearer J1dPDH7muIfaW2Zc1ZPUoMPeB48r4Aoa_p3LdBBS7yI'
];

include('safeTweet.php');
include('dbinfo.php');
header('Content-Type: text/html; charset=utf-8');

// Connect to applications DB
try {
	$bddApp = new PDO('mysql:host=' . $dbhost . ';dbname=' . $dbAppName, $dbAppLogin, $dbAppPassword, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	$bddApp->exec("SET CHARACTER SET utf8");
} catch (PDOException $e) {
	echo 'Echec de la connexion : ' . $e->getMessage();
	exit;
}

$appResponse = $bddApp->query("SELECT value FROM misc WHERE appName='botDuCul'");
$appResult = $appResponse->fetchAll();
$wordid = $appResult[0][0];
$appResponse->closeCursor();

if($wordid < 46209){
	try {
		$bdd = new PDO('mysql:host=' . $dbhost . ';dbname=' . $dbname, $dblogin, $dbpassword, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
		$bdd->exec("SET CHARACTER SET utf8");
	} catch (PDOException $e) {
		echo 'Echec de la connexion : ' . $e->getMessage();
		exit;
	}

	$queryWord = "SELECT DISTINCT lemme FROM lexique2 LIMIT 1 OFFSET " . $wordid;
	$response = $bdd->query($queryWord);
	$result = $response->fetchAll();
	$word = $result[0][0];
	$response->closeCursor();

	$tweet = $word . " du cul";

	$appResponse = $bddApp->query("UPDATE misc SET value = value + 1 WHERE appName = 'botDuCul'");
	$appResponse->closeCursor();

	if(safeTweet($tweet)){
		$status_data = array(
		"status" => $tweet,
		"language" => "fr",
		"visibility" => "public"
);
	$ch_status = curl_init();
curl_setopt($ch_status, CURLOPT_URL, "https://asstodon.social/api/v1/statuses");
curl_setopt($ch_status, CURLOPT_POST, 1);
curl_setopt($ch_status, CURLOPT_POSTFIELDS, $status_data);
curl_setopt($ch_status, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch_status, CURLOPT_HTTPHEADER, $headers);

$output_status = json_decode(curl_exec($ch_status));

curl_close ($ch_status);	
	}else{
		file_get_contents("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	}
}



?>
