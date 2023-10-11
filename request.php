<?php
// Initialize cURL session
$ch = curl_init();

// Set the URL you want to request
$url = "https://6756-41-89-56-2.ngrok-free.app/safcallbackurl.php";

// Set cURL options for the GET request
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);

// Execute the request asynchronously
curl_exec($ch);

// Close the cURL session (this does not wait for the request to complete)
curl_close($ch);

// You can continue with other tasks without waiting for the response
?>