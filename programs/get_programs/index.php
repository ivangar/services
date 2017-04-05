<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once("lib/get_programs.php");
/*  Adding cache statements in the header to disable page to cache information */
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
    <link href="css/main.css?<?php echo time(); ?>" rel="stylesheet">

</head>
<body>
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

    <!-- jQuery -->



    <script src="js/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!--<script src="js/jquery.fileDownload.js"></script>
    <script src="js/image.js"></script>-->
</body>

</html>
