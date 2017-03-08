<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/fr/inc/config/config_dxlink_env.php');
$url = FIXED_SSL_URL.$_SERVER['HTTP_HOST'].'/programs/HZ2Rev/zoster.php#tab1';
echo $url;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>test Post</title>
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script>
$(document).ready(function () {
	var email = 'ivang@sta.ca';
	var merck = true;
	var url = '<?php echo $url; ?>';
	var form = $('<form action="' + url + '" method="post" >' +
	  '<input type="text" name="email" value="' + email + '" />' +
	  '<input type="text" name="merckConnect" value="' + merck + '" />' +
	  '</form>');
	$('body').append(form);
	form.submit(); 

});//end document.ready
</script>
</head>
<body>

</body>
</html>