<?php
// sleep(5);
$data = file_get_contents("php://input");
$data = json_decode($data,true);
error_log(print_r($data,true),0);

    $metaData= [
    'MerchantRequestID'=>$data['Body']['stkCallback']['MerchantRequestID'],
    'CheckoutRequestID'=>$data['Body']['stkCallback']['CheckoutRequestID'],
    'ResultCode'=>$data['Body']['stkCallback']['ResultCode'],
    'ResultDesc'=>$data['Body']['stkCallback']['ResultDesc'],
    ];
    error_log(print_r($metaData,true),0);
    $resultCode = $data['Body']['stkCallback']['ResultCode'];

    // logic to handle
    switch($resultCode){
        case 0:
            $Item = $data['Body']['stkCallback']['CallbakMetadata']['Item'];
            $mpesaData= array_column($Item,'Value',"Name");
             $metaData= [
            'MerchantRequestID'=>$data['Body']['stkCallback']['MerchantRequestID'],
            'CheckoutRequestID'=>$data['Body']['stkCallback']['CheckoutRequestID'],
            'ResultCode'=>$data['Body']['stkCallback']['ResultCode'],
            'ResultDesc'=>$data['Body']['stkCallback']['ResultDesc'],
            ];
            $mpesaDetails = array_merge($metaData,$mpesaData);
            error_log(print_r($mpesaDetails,true),0);
            echo "Payment Received Successfully Thank You Purchase Again From us";
            error_log(print_r($mpesaData,true),0);
            break;
        case 17:
            echo "Dear Customer Wait there is process executing";
            break;
        case 2001;
            echo "Dear Customer Mpesa Details Entered are Incorrect";
            break;
        case 1032:
            echo "Dear Customer You Cancelled the transaction";
            break;
        case 1;
            echo "Dear Customer You balance is too low than your Purchase";
            break;
        default:
            echo "Unexpected Error Occurred Try Again";
    }

echo "{
    'ResponseCode':0,
    'ResponseDesc':'Accept Service
}";
return $data;
