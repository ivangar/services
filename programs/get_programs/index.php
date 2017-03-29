<?php
require_once("lib/get_programs.php");
/*  This file is for the "My Events" page */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mreckconnect programs</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">

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
    <script src="js/jquery.fileDownload.js"></script>
    <script src="js/image.js"></script>
</body>

</html>
