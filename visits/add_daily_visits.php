<?php
# This file is part of Koha.
#
# Copyright (C) 2014  MartinKravec
#
# Koha is free software; you can redistribute it and/or modify it
# under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 3 of the License, or
# (at your option) any later version.
#
# Koha is distributed in the hope that it will be useful, but
# WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Koha; if not, see <http://www.gnu.org/licenses>.

    session_start();

	if (!headers_sent()) {
		header('Content-Type: text/html; charset=utf-8');
	}
	
	require_once "../system/config.php";
        
        use Tracy\Debugger;
	
?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <?php 
            $title = $text->KOHA." ".$text->add_daily_visits;
            require_once "../tpl/head.php"; 
            
            $defaultDate = date("Y-m-d");
            
        ?>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
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
                                <li class="active"><?php echo $text->add_daily_visits; ?></li>
			</ol>
		</div>

        <div class="row">

            <div class="row">
               <div class="col-md-12" id="sendForm">
                    <div class="panel panel-default">
                        <div class="panel-body ">
                            <form>
                                <div class='row'>
                                    <div class='col-md-6'>
                                          <div class="form-group" id="timeDiv">
                                            <label for="time" class="control-label"><?= $text->select_date ?></label>
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input id="date" type="text" class="form-control">
                                                </div>
                                          </div>
                                        <div class="form-group" id="timeDiv">
                                            <label for="time" class="control-label"><?= $text->department ?></label>
                                            <div class='input-group date' id='datetimepicker1'>
                                                <select id="branchCode">
                                                    <?php
                                                    
                                                        require_once "../system/visits/DatabaseHandler.class.php";
	
                                                        $databaseHandler = new DatabaseHandler($db);
                                                    
                                                        $branches = $databaseHandler->getBranches();
                                                        foreach ($branches as $arr) {
								echo "<option value=\"".$arr['branchcode']."\">".$arr['branchname']."</option>\n";
							}
                                                    ?>
                                                </select>
                                                </div>
                                          </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="internet" class="control-label"><?= $text->internet ?></label>
                                            <div>
                                                <input type="number" min="0" id="internet" value="0">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="internet" class="control-label"><?= $text->study_room ?></label>
                                            <div>
                                                <input type="number" min="0" id="studyRoom" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              
                                <div>
                                    <button class="btn btn-primary rightButton addDailyVisits"><?= $text->add_daily_visits ?></button>
                                </div>
                                
                            </form>
                        </div>
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
    <script src="../js/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    
    <?php
        if (isset($langShortcut) AND ($langShortcut == "cs")) {
            echo "<script src='../js/datepicker-cs.js'></script>";
        }
    ?>
    <script type="text/javascript" src="../js/bootstrap-multiselect.js"></script>
    <script>
        $( document ).ready(function() {
            

            $( "#date" ).datepicker();
            $( "#date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
            $( "#date" ).datepicker('setDate', "<?php echo $defaultDate; ?>");

            
            $(".addDailyVisits").on("click", function(event){
                
                event.preventDefault();
                
                var date = $("#date").val();
                var internet = $("#internet").val();
                var studyRoom = $("#studyRoom").val();
                var branchCode = $("#branchCode").val();
                
                $.ajax({
                    url: "addDailyVisits.ajax.php",
                    type: "POST",
                    data: { date: date, internet: internet, studyRoom: studyRoom, branchCode: branchCode },
                    dataType: "json"
                }).always(function(){
                    $("#loading-indicator").show();
                    console.log("Adding: date:"+date+" internet:"+internet+" studyRoom:"+studyRoom+ " branchCode: "+branchCode);
                }).done(function(results){
                    $("#loading-indicator").hide();
                    console.log(results);
                    if(results.result === "ok"){
                        alert("<?= $text->added_successfully ?>");
                        $("#internet").val("0");
                        $("#studyRoom").val("0");
                    }
                    else{
                        alert(results.result);
                    }
                }).fail(function(jqXHR, textStatus){
                    $("#loading-indicator").hide();
                    console.log( "Request failed: " + textStatus );
                });
                    
            });
                
        });
        
    </script>

	<?php 
		require_once "../tpl/footer.php"; 
    ?>

