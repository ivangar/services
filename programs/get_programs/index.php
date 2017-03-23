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
                        
                        <div id='content'>
                              <table class='table table-striped table-hover' id='sortable'>
                                <thead>
                                  <tr>
                                    <th class='col-sm-1'>program #ID</th>
                                    <th class='col-sm-1'>program title</th>
                                    <th class='col-sm-2'>program subtitle</th>
                                    <th class='col-sm-2'>program description</th>
                                    <th class='col-sm-1'>language</th>
                                    <th class='col-sm-1'>authors</th>
                                    <th class='col-sm-1'>url</th>
                                    <th class='col-sm-1'>launch date</th>
                                    <th class='col-sm-1'>expiration date</th>
                                    <th class='col-sm-1' style="padding-left: 25px;">image</th>
                                  </tr>
                                </thead>
                                <tbody id='events'>
                                     <tr>
                                        <td>HPV_01</td>
                                        <td>Are we on the right track? HPV-Related Cancer & Disease Prevention</td>
                                        <td>Learning from Canada’s experience</td>
                                        <td>Module 1: HPV Update in Adult Women & Module 2:Communication Concepts This program reviews latest data on the burden and prevention of HPV-related diseases, and how to communicate with patients and/or caregivers about this important issue.</td>
                                        <td>English</td>
                                        <td>Dr. Vivien Brown, Dr. Albert Schumacher and Dr. Marc Steben</td>
                                        <td>https://www.dxlink.ca/programs/hpv/combined/hpv.php#tab1</td>
                                        <td>2015-04-30</td>
                                        <td>2016-04-17</td>
                                        <td><a href="#" class="btn btn-default">download</a></td>
                                    </tr>                                                                                                                                                                                
                                </tbody>
                              </table> 
                        </div> 


                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
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
</body>

</html>
