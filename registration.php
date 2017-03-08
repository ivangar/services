<?php
ob_start();
if(!isset($_SESSION)){session_start();}  
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/register.php');

$response = 0;
$code = 0;
$process_state = '';
$transaction_info = '';
$record = fopen('api-log.txt','a+');
$log = "REST API CLIENT CONNECTION: \nDate: ".date("Y-m-d, g:i a", time())."\n\n";

//Read Request body
$inputJSON = file_get_contents('php://input');
$input= json_decode( $inputJSON, TRUE ); //convert JSON into array

$process = new ProcessHTTP();

if($process->parseHTTPRequest($input)){
	$process_state = $process->processRequest();
}

$message = $process->getMessage();
$code = $process->getResponseCode();
$status = $process->getStatusPhrase();
$header = "HTTP/1.1 $code $status";

$log .= $inputJSON . "\n\n";

foreach ($message as $k => $v) {
    $transaction_info .= $v;
}

$log .= "Response Code: HTTP/1.1 $code $status\n\nprocessed state = $process_state \n\nmessage: $transaction_info\n\n" . DELIMITER . "\n\n";

fwrite($record, $log);
fclose($record);

header($header, true, $code);
header('Content-Type: application/json');
echo json_encode($message);


$process->Close_DB_connection();
ob_end_flush();

?>