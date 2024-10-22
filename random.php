<?php

$wikipediaApiUrl = "https://en.wikipedia.org/w/api.php";

function makeApiRequest($apiUrl, $params)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $apiUrl);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');  // Save cookies
	curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
	$response = curl_exec($ch);
	curl_close($ch);

	return json_decode($response, true);
}

function getRandomPageId()
{
	return rand(1, 100000);
}

// Fetch the content of a random page by ID
function getRandomPageContent()
{
	$wikipediaApiUrl = "https://en.wikipedia.org/w/api.php";
	do {
		$pageId = getRandomPageId();
		echo "Trying Page ID: " . $pageId . "\n";

		$params = [
			'action' => 'parse',
			'format' => 'json',
			'pageid' => $pageId,
			'formatversion' => '2'
		];

		$response = makeApiRequest($wikipediaApiUrl, $params);

		// Check if there is an error
		if (isset($response['error'])) {
			echo "Error: " . $response['error']['info'] . "\n";
			// If there's an error (like 'nosuchpageid'), retry with a new ID
		} else {
			// No error, return the page content
			return $response['parse']['text'];
		}
	} while (true);  // Continue retrying until a valid page is found
}

// Fetch and display a random page
$wikiText = getRandomPageContent();

echo $wikiText;
