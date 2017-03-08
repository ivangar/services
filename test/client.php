<?php
if(!isset($_SESSION)){session_start();}  
/* require_once($_SERVER['DOCUMENT_ROOT'] . '/test/config_dxlink_env.php'); */

$doctor = array("first_name" => "Ivan", "last_name" => "Garzon", "email" => "garzon861@gmail.com", "password" => "pass", "country" => "", "province" => "", "postal_code" => "H3OS9W", "profession" => "Physician", "specialty" => "", "language" => "English", "matricule" => 0, "sta_agreement" => true);                                                                    
$doctor_string = json_encode($doctor);     
$username = "STA";
$password = "B!#h5(d?4_u4}8N";

echo "Json request<br> <span style='padding-left:90px;'>{</span> <br>";
foreach ($doctor as $key => $value)
{	if(is_string($value)) echo "<span style='padding-left:100px;'> \"$key\":\"$value\"</span><br>"; else echo "<span style='padding-left:100px;'> \"$key\":$value</span><br>";}
echo "<span style='padding-left:90px;'>}</span><br>";

/*
$headers = array(
    'Content-Type: application/json',
    'Authorization: Basic '. base64_encode("$username:$password")
);
*/

$headers = array(
    'Content-Type: application/json'
);

// USE HTTPS FOR REMOTE DXLINK SERVER /
$base_url = "https://dxlink.ca/curl/curl.php";

// Get cURL resource
$curl = curl_init($base_url);

curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_HEADER, 1);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $doctor_string);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);

$result = curl_exec($curl);

if( !$result ){
	echo "ERROR  \n";
	$curl_info = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	echo "HTTP Response Code: $curl_info \n";
	echo "\nAuthorized Connection Status: Disconnected\nError: " . curl_error($curl) . " - Code: " . curl_errno($curl);
	echo "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n";
}

else {
	/*header('Location: http://www.dxlink.ca/programs/hpv/combined/hpv.php#tab1');*/
	
	echo 'Eureka';
  
	$curl_info = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	echo "<br>HTTP Response Code: $curl_info <br>";
	echo "<br>RESPONSE:<br>";
	$json_response = json_decode($result,true);
	echo $json_response;
	
}



// Close request to clear up some resources
curl_close($curl);

?>