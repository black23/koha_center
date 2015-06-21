<?php

    session_start();

	if (!headers_sent()) {
		header('Content-Type: text/html; charset=utf-8');
	}
	
	require_once "../system/config.php";
        
        use Tracy\Debugger;
        
        $langFile = $root."/tpl/lang/".$langShortcut.".reports.json";
        $langData = file_get_contents($langFile);
        $report_text = json_decode($langData);
	
?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <?php 
            $title = $title = $text->KOHA." ".$title = $text->statistical_reports;
            require_once "../tpl/head.php"; 
            
            $defaultFrom = date("Y")."-01-01";
            $defaultTo = date("Y")."-12-31";
            
        ?>

        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <style>
            .ui-datepicker-calendar {
                display: none;
            }
        </style>
    </head>

    <body>

    <?php 
		require_once "../tpl/nav.php"; 
    ?>

    <!-- Page Content -->
    <div class="container">
        <div class="row">
                <ol class="breadcrumb">
                        <li><a href="../index.php"><?php echo $text->home; ?></a></li>
                        <li class="active"><?php echo $text->statistical_reports; ?></li>
                </ol>
        </div>

        <div class="row">
        
               <div class="col-md-12" id="sendForm">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="background-color: #b7b7b7!important">
                            <h4><?php echo $text->statistical_reports; ?> </h4>
                        </div>
                        <div class="panel-body ">
                            <form>
                                <div class='row'>
                                    <div class='col-md-6'>
                                          <div class="form-group" id="timeDiv">
                                            <label for="time" class="control-label"><?= $text->choose_range ?></label>
                                            <div class="form-group">
                                                <label class="control-label" for="from"><?php echo $text->from_date; ?></label>
                                                <input type='text' name="date" id="from" class="form-control date">
                                                <label class="control-label" for="to"><?php echo $text->to_date; ?></label>
                                                <input type='text' name="date" id="to" class="form-control date">
                                            </div>
                                          </div>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-primary rightButton exportButton XLS">XLS</button>
                                </div>
                            </form>
                        </div>
                    </div>
               </div>

        </div>
        <!-- /.row -->

        <img src="../image/loading.gif" id="loading-indicator" style="display: none;">

        <hr>

        <!-- Footer -->
       <?php require_once '../tpl/footer.tpl.php'; ?>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <!--<script src="../js/jquery.min.js"></script>-->
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap-tooltip.js"></script>
    
    <!--<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>-->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    
    <script>
        jQuery( document ).ready(function( $ ) {
            
            $( "#from, #to" ).datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: "yy-mm-dd",
                onClose: function(dateText, inst) { 
                  var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                  var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                  var date = new Date(year, month, 2);
                  $(this).datepicker('setDate', date)
                         .data('date', date); // remember the selected date
                }
            });
            
            $( "#from" ).datepicker("setDate", '<?= $defaultFrom ?>');
            $( "#to" ).datepicker("setDate", '<?= $defaultTo ?>');

            $(".XLS").on("click", function(event){
                event.preventDefault();
                
                var from = $("#from").val();
                var to = $("#to").val();
                
                window.open('reportToXLS.php?from='+from+'&to='+to);
            });
                
        });
        
    </script>

    <?php 
        require_once "../tpl/footer.php"; 
    ?>

