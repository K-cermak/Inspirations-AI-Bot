<?php
	include 'defines.php';

	function publishInstagram($text, $imageUrl) {
		$imageMediaObjectEndpoint = ENDPOINT_BASE . INSTAGRAM_ACCOUNT_ID . '/media';
		$imageMediaObjectEndpointParams = array(// POST 
			'image_url' => $imageUrl,
			'caption' => $text,
			'access_token' => ACCESS_TOKEN_INSTAGRAM
		);
		$imageMediaObjectResponseArray = makeApiCall($imageMediaObjectEndpoint, 'POST', $imageMediaObjectEndpointParams);
	
		// set status to in progress
		$imageMediaObjectStatusCode = 'IN_PROGRESS';
	
		while ($imageMediaObjectStatusCode != 'FINISHED') { // keep checking media object until it is ready for publishing
			$imageMediaObjectStatusEndpoint = ENDPOINT_BASE . $imageMediaObjectResponseArray['id'];
			$imageMediaObjectStatusEndpointParams = array( // endpoint params
				'fields' => 'status_code',
				'access_token' => ACCESS_TOKEN_INSTAGRAM
			);
			$imageMediaObjectResponseArray = makeApiCall( $imageMediaObjectStatusEndpoint, 'GET', $imageMediaObjectStatusEndpointParams );
			$imageMediaObjectStatusCode = $imageMediaObjectResponseArray['status_code'];
			sleep(5);
		}
	
		// publish imagei
		$imageMediaObjectId = $imageMediaObjectResponseArray['id'];
		$publishImageEndpoint = ENDPOINT_BASE . INSTAGRAM_ACCOUNT_ID . '/media_publish';
		$publishEndpointParams = array(
			'creation_id' => $imageMediaObjectId,
			'access_token' => ACCESS_TOKEN_INSTAGRAM
		);
		$publishImageResponseArray = makeApiCall($publishImageEndpoint, 'POST', $publishEndpointParams);
	
		print_r($imageMediaObjectResponseArray);
		print_r($publishImageResponseArray);
	}
	

	function getApiLimit() {
		$limitEndpoint = ENDPOINT_BASE . INSTAGRAM_ACCOUNT_ID . '/content_publishing_limit';
		$limitEndpointParams = array( // get params
			'fields' => 'config,quota_usage',
			'access_token' => ACCESS_TOKEN_INSTAGRAM
		);
		$limitResponseArray = makeApiCall($limitEndpoint, 'GET', $limitEndpointParams);
				
		print_r($limitResponseArray);
	}


	function makeApiCall($endpoint, $type, $params) {
		$ch = curl_init();

		if ('POST' == $type) {
			curl_setopt($ch, CURLOPT_URL, $endpoint);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params) );
			curl_setopt($ch, CURLOPT_POST, 1);
		} else if ('GET' == $type) {
			curl_setopt($ch, CURLOPT_URL, $endpoint . '?' . http_build_query($params));
		}

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		curl_close($ch);

		return json_decode($response, true);
	}

	
?>