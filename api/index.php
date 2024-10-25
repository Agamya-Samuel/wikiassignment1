<?php

require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$userName = $_ENV['MW_USER_USERNAME'] ?? getenv('MW_USER_USERNAME');
$botUsername = $_ENV['MW_BOT_USERNAME'] ?? getenv('MW_BOT_USERNAME');
$botPassword = $_ENV['MW_BOT_PASSWORD'] ?? getenv('MW_BOT_PASSWORD');
$pageTitle = 'User:Agamyasamuel/wikiassignment';

$apiUrl = "https://test.wikipedia.org/w/api.php";

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

function editPage($apiUrl, $pageTitle, $wikiText, $csrfToken)
{
	$params = [
		'action' => 'edit',
		'title' => $pageTitle,
		'text' => $wikiText,
		'token' => $csrfToken,
		'bot' => true,
		'format' => 'json'
	];

	return makeApiRequest($apiUrl, $params);
}

function getCsrfToken($apiUrl)
{
	$params = [
		"action" => "query",
		"format" => "json",
		"meta" => "tokens",
		"formatversion" => "2",
		"type" => "csrf",
	];

	$response = makeApiRequest($apiUrl, $params);
	return $response['query']['tokens']['csrftoken'];
}

function getLoginToken($apiUrl)
{
	$params = [
		'action' => 'query',
		'meta' => 'tokens',
		'type' => 'login',
		'format' => 'json'
	];

	$response = makeApiRequest($apiUrl, $params);
	return $response['query']['tokens']['logintoken'];
}

function login($apiUrl, $botUsername, $botPassword)
{
	$loginToken = getLoginToken($apiUrl);
	// echo ($loginToken);

	$params = [
		'action' => 'clientlogin',
		'username' => $botUsername,
		'password' => $botPassword,
		'loginreturnurl' => 'https://test.wikipedia.org/',
		'logintoken' => $loginToken,
		'format' => 'json'
	];

	return makeApiRequest($apiUrl, $params);
}

function getRandomPageId()
{
	return rand(1, 100000);
}

function getRandomPageContent()
{
	$wikipediaApiUrl = "https://en.wikipedia.org/w/api.php";
	do {
		$pageId = getRandomPageId();
		echo "Trying Page ID: " . $pageId . "<br>";

		$params = [
			'action' => 'parse',
			'format' => 'json',
			'pageid' => $pageId,
			"prop" => "wikitext",
			'formatversion' => '2'
		];

		$response = makeApiRequest($wikipediaApiUrl, $params);

		if (isset($response['error'])) {
			echo "Error: " . $response['error']['info'] . "\n";
		} else {
			echo "Fetching Article fom Wikipedia Page: " . $response['parse']['title'] . "<br><br>";
			return $response['parse']['wikitext'];
		}
	} while (true);  // Continue retrying until a valid page is found
}

$wikiText = getRandomPageContent();


// Log in and make the edit
$loginResponse = login($apiUrl, $botUsername, $botPassword);
// print_r($loginResponse);
$csrfToken = getCsrfToken($apiUrl);
$response = editPage($apiUrl, $pageTitle, $wikiText, $csrfToken);

// Check if the edit was successful
if (isset($response['edit']['result']) && $response['edit']['result'] === 'Success') {
	$pageUrl = "https://test.wikipedia.org/wiki/" . urlencode($pageTitle);
	echo "Page edited successfully!\n";
	echo "You can view the Edited page here: <br><a href=\"$pageUrl\" target=\"_blank\">$pageUrl</a>";
} else {
	echo "There was an error editing the page.";
}
