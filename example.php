<?php

	require_once("includes/class.apiClient.php");

	$key = '';
	$secret = '';
	$host = 'http://api.arv3.local';
	
	$apiClient = New apiClient($host, $key, $secret);
	$apiClient->init();
	$result = $apiClient->doRequest("/api/get");
	var_dump($result);

?>
