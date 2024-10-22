<?php

// Include the Composer autoloader
require 'vendor/autoload.php';

// Load the environment variables using phpdotenv
use Dotenv\Dotenv;

// Create a new instance of Dotenv and load the .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Access the environment variables using $_ENV or getenv
$userName = $_ENV['MW_USER_USERNAME'] ?? getenv('MW_USER_USERNAME');
$botUserName = $_ENV['MW_BOT_USERNAME'] ?? getenv('MW_BOT_USERNAME');
$botPassword = $_ENV['MW_BOT_PASSWORD'] ?? getenv('MW_BOT_PASSWORD');
$pageTitle = 'Agamyasamuel/wikiassignment';

// $endPoint = 'https://test.wikipedia.org/w/api.php?action=parse&page=User:Agamyasamuel/wikiassignment&format=json';

$endPoint = "https://en.wikipedia.org/w/api.php";

$params = [
	"action" => "parse",
	"page" => "Nelson Mandela",
	"format" => "json"
];


// Send a GET request to the API
$url = $endPoint . "?" . http_build_query($params);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
curl_close($ch);

$result = json_decode($output, true);

echo ($result["parse"]["text"]["*"]);

// //! Decoding JSON

// // Check if decoding was successful
// if (json_last_error() === JSON_ERROR_NONE) {
// 	// Extract the 'contact' subsection
// 	$parsedData = $result['parse']['text']['*'];

// 	// Display the extracted subsection
// 	echo "parsedData: " . $parsedData . "<br>";
// } else {
// 	echo "Error decoding JSON: " . json_last_error_msg();
// }



































//! Curl Implementation

// // API URL
// $apiUrl = "https://api.example.com/endpoint";

// // Initialize a cURL session
// $curl = curl_init();

// // Set the cURL options
// curl_setopt($curl, CURLOPT_URL, $apiUrl);  // Set the URL for the request
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // Return the response as a string
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  // Skip SSL certificate verification

// // Execute the GET request
// $response = curl_exec($curl);

// // Check if an error occurred
// if(curl_errno($curl)) {
//     echo "cURL Error: " . curl_error($curl);
//     exit;
// }

// // Close the cURL session
// curl_close($curl);

// // Decode the JSON response into a PHP array
// $data = json_decode($response, true);

// // Display the response (as an example)
// echo "Response: ";
// print_r($data);
