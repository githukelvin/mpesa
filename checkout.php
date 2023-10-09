<?php
require_once "./connection.php";
require 'vendor/autoload.php';
use Carbon\Carbon;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try{

  $sql = "SELECT AccountReference,Transaction_type,Business_code FROM `transactions`;";

  $business=mysqli_query($conn,$sql)or die('query failed');
  $Business =mysqli_fetch_assoc($business);
$ch = curl_init("https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest");

curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Authorization: Bearer '."OgPBwO9DUBDqkADUuQAQZMkhjg9n",
  'Content-Type: application/json'
]);

$date = Carbon::now("Africa/Nairobi");


// Create a DateTime object from the input string
$dateTime = new DateTime($date);

// Format the DateTime object as per your desired format
$formattedDateTime = $dateTime->format("YmdHis");
$Desc= "Shoes Purchase";
$phoneNumber= 254700349970;
$till =$Business["Business_code"];
$passkey ="bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
$combined= $till.$passkey.$formattedDateTime;
$Password = base64_encode($combined);
$data = array(
  "BusinessShortCode" => $till,
  "Password" => $Password,
  "Timestamp" => "$formattedDateTime",
  "TransactionType" => $Business["Transaction_type"],
  "Amount" => 100,
  "PartyA" => 254708374149,
  "PartyB" => 174379,
  "PhoneNumber" => $phoneNumber,
  "CallBackURL" => "https://f691-2c0f-fe38-2206-94b5-41e6-5878-d783-7c5f.ngrok-free.app/safcallbackurl.php",
  "AccountReference" => $Business["AccountReference"],
  "TransactionDesc" => $Desc
);
$jsonData = json_encode($data);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response =  curl_exec($ch);

curl_close($ch);
$responseData= json_decode($response, true);
$checkoutid= $responseData["CheckoutRequestID"];
sleep(5);
if($checkoutid === null){
        echo "request failed";
    }
else{
    $sqlInsert="INSERT INTO `payments` (`PhoneNumber`, `Amount`,`CheckOut_ID`, `Timestamp`, `Password`, `TransactionDesc`) VALUES ('$phoneNumber', '1','$checkoutid', '$formattedDateTime', '$Password', '$Desc')";
    if ($conn->query($sqlInsert) === TRUE) {
         echo "Record inserted successfully";
    } else {
         echo "Error: " . $sqlInsert . "<br>" . $conn->error;
    }
    sleep(2);
    require "./safcallbackurl.php";

}
}catch(Exception $e){
  printf("Caught exception",$e);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pay me now</title>
</head>
<body>
  <a href="/confirmPayment.php">Umenilipa</a>
</body>
</html>