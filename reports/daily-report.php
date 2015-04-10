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

        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
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
                                                <input type='text' name="date" id="from" class="form-control date" value="<?php echo date("Y-m-d"); ?>" placeholder="<?php echo date("Y-m-d"); ?>">
                                                <label class="control-label" for="to"><?php echo $text->to_date; ?></label>
                                                <input type='text' name="date" id="to" class="form-control date" value="<?php echo date("Y-m-d"); ?>" placeholder="<?php echo date("Y-m-d"); ?>">
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

        </div>
        <!-- /.row -->

        <img src="../image/loading.gif" id="loading-indicator" style="display: none;">

        <hr>

        <!-- Footer -->
       <?php require_once '../tpl/footer.tpl.php'; ?>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="../js/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap-tooltip.js"></script>
    
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    
    <?php
        if (isset($langShortcut) AND ($langShortcut == "cs")) {
            echo "<script src='../js/datepicker-cs.js'></script>";
        }
    ?>
    
    <script>
        $( document ).ready(function() {
            
            $( "#from" ).datepicker({
                // defaultDate: "-1w",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: "yy-mm-dd",
                onClose: function( selectedDate ) {
                    //$( "#to" ).datepicker( "option", "minDate", selectedDate );
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).datepicker('setDate', new Date(year, month, 1));
                }
            });
            
            $( "#to" ).datepicker({
                //defaultDate: "+1w",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: "yy-mm-dd",
                onClose: function( selectedDate ) {
                    //$( "#from" ).datepicker( "option", "maxDate", selectedDate );
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).datepicker('setDate', new Date(year, month, 31));
                }
            });
            
            $( "#from" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
            $( "#to" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
            $( "#from" ).datepicker('setDate', "<?php echo $defaultFrom; ?>");
            $( "#to" ).datepicker('setDate', "<?php echo $defaultTo; ?>");
 
            
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

