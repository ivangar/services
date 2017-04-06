<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once("lib/get_programs.php");
/*  Adding cache statements in the header to disable page to cache information */
//unset($_SESSION['access']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>MerckConnect programs</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css?<?php echo time(); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
    <link href="css/main.css?<?php echo time(); ?>" rel="stylesheet">
</head>
<body>
<div id="password-dialog-form" title="Password access" style="display:none;">
    <p class="validateTips"></p>
    <p>Please enter the password to access this page</p>
    <form id="pwd-access">
        <input type='hidden' name='login_submitted' id='login_submitted' value='1' />
        <fieldset>
          <label for="pwd">Password:</label>
          <input type="password" name="pwd" id="pwd" class="text ui-widget-content ui-corner-all">
          <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
        </fieldset>
    </form>
</div>
<?php if(isset($_SESSION['access']) && $_SESSION['access']){?>
    <div id="wrapper">

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid" id="event-container">                
                <div class="row"  style="margin-top:20px;">
                    <div class="col-lg-12">
                        <h1 class="page-header">Merck programs</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <?php $programs->setTableContent(); ?>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
<?php }?>
    <!-- jQuery -->

    <script src="js/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <!--<script src="js/jquery.fileDownload.js"></script>-->
    <script src="js/programs.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        var access = <?php if(isset($_SESSION['access'])) {echo "true"; } else echo "false"; ?>;
        if(!access){ $( "#password-dialog-form" ).dialog( "open" );}
    });
    </script>
</body>

</html>
