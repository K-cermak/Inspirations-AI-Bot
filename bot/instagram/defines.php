<?php
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, URL_BASE . "?api=" . API_KEY . "&action=getVar&var=fbEndpoint");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	define('ENDPOINT_BASE', $result);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, URL_BASE . "?api=" . API_KEY . "&action=getVar&var=fbApiKey");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	define('ACCESS_TOKEN_INSTAGRAM', $result);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, URL_BASE . "?api=" . API_KEY . "&action=getVar&var=instagramId");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	define('INSTAGRAM_ACCOUNT_ID', $result);
?>