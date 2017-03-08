<?php
if(!isset($_SESSION)){session_start();}  
require_once($_SERVER['DOCUMENT_ROOT'] . '/fr/inc/config/config_dxlink_env.php');

$url = FIXED_SSL_URL.$_SERVER['HTTP_HOST'].'/programs/vaccine/index.php?email=ivang@sta.ca&merckConnect=true';

header('Location: ' . $url); 

?>