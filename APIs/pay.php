<?php 
	if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
 
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
 
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
 
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
 
        exit(0);
    }



if(isset($_GET['txn_ref'])){
$pactpay_ref = $_GET['txn_ref'];
$merchant_id = '001';
//fetch transaction reference appended by PactPay from URL  
//get response from pactpay 
    
//your PactPay MerchantID 
$txn_details = "https://secure.pactpay.com/get_transaction_full_details.php?merchant_id=$merchant_id &txn_ref=$pactpay_ref";//construct the transaction check URL query to incorporate your merchant_id and the txn_ref that was fetched from the URL  
$txn_stat = file_get_contents($txn_details);//Fetch the constructed URL content  to a variable  
if(strpos($txn_stat,"|")){ 
$txn = explode('|',$txn_stat);//split the response using “|” into an array,  

$txn_status = $txn['0']; //STATUS 

$txn_amount = $txn['1'];//amount 

$site_order_id = $txn['2'];//site_reference 

$payee_email = $txn['3'];//customer_email 

$payee = $txn['4'];//name 

$time_initiated = $txn['5'];//time_initiated 

$product_name = $txn['6'];//product_name 
$package = $product_name;

$description = $txn['7'];//product_description 

$amount_available_to_merchant = $txn['8'];//amount_available_to_merchant  

$conv_fee = $txn['9'];//conv_fee  

if($txn_status=='APPROVED SUCCESSFUL'){//check if transcation status is successful before giving value 

// run code that gives user the service he paid for 
$today = date();
$sixmonths = time() + (6 * 30 * 24 * 60 * 60);
$expire_day = date('Y-m-d',$sixmonths);
$channel = "packpay";
    
$addto_paid = mysqli_query($dbc,"INSERT INTO `paid`(owner,expiry,package) VALUES('$payee_email','$expire_day','$package')");
$addto_paid;
    
$update_payments = mysqli_query($dbc,"INSERT INTO `payments`(owner,when_paid,order_id,txn_ref,txn_status,amount,package,channel) VALUES('$payee_email','$today','$site_order_id','$pactpay_ref','$txn_status','$amount_available_to_merchant','$package','$channel')");
    
$update_payments;
$data = array('status'=>0,'details'=>"Payment was successful");
$response = json_encode($data);
echo $response ;

}else{ 
//do not offer user the service, as payment was not successful , ask user to try again or contact u if any questions 
$data = array('status'=>0,'details'=>"Error, payment was not successful. Please contact admin");
$response = json_encode($data);
echo $response ;
}
    
}else{
$data = array('status'=>0,'details'=>"Error, payment was not successful");
$response = json_encode($data);
echo $response ;
}
    
}
else{
$data = array('status'=>0,'details'=>"Error, needed parameters not complete");
$response = json_encode($data);
echo $response ;
}
    
?>