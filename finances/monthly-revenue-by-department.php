<?php
# This file is part of Koha.
#
# Copyright (C) 2014  xkravec
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
	require_once "../system/finances/DatabaseHandler.class.php";
	
	$databaseHandler = new DatabaseHandler($db);
	
	$defaultFrom = date("Y-m-")."01";
    
    $lastDayInActualMonth = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y")); 
    $defaultTo = date("Y-m-").$lastDayInActualMonth;
	
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php 
		$title = $text->monthly_revenue;
		require_once "../tpl/head.php"; 
    ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <style>
    /* Custom Colored Panels */

.huge {
    font-size: 40px;
}

.panel-green {
    border-color: #5cb85c;
}

.panel-green .panel-heading {
    border-color: #5cb85c;
    color: #fff;
    background-color: #5cb85c;
}

.panel-green a {
    color: #5cb85c;
}

.panel-green a:hover {
    color: #3d8b3d;
}

.panel-red {
    border-color: #d9534f;
}

.panel-red .panel-heading {
    border-color: #d9534f;
    color: #fff;
    background-color: #d9534f;
}

.panel-red a {
    color: #d9534f;
}

.panel-red a:hover {
    color: #b52b27;
}

.panel-yellow {
    border-color: #f0ad4e;
}

.panel-yellow .panel-heading {
    border-color: #f0ad4e;
    color: #fff;
    background-color: #f0ad4e;
}

.panel-yellow a {
    color: #f0ad4e;
}

.panel-yellow a:hover {
    color: #df8a13;
}

.panel-brown {
    border-color: #854513;
}

.panel-brown .panel-heading {
    border-color: #854513;
    color: #fff;
    background-color: #854513;
}

.panel-brown a {
    color: #854513;
}

.panel-brown a:hover {
    color: #8b2500;
}

.date{
	width: 150px;
	margin-top: 6px;
}


.col-xs-5ths,
.col-sm-5ths,
.col-md-5ths,
.col-lg-5ths {
    position: relative;
    min-height: 1px;
    padding-right: 10px;
    padding-left: 10px;
    width: 20%;
    float: left;
}

@media (min-width: 768px) {
    .col-sm-5ths {
        width: 20%;
        float: left;
    }
}
@media (min-width: 992px) {
    .col-md-5ths {
        width: 20%;
        float: left;
    }
}
@media (min-width: 1200px) {
    .col-lg-5ths {
        width: 20%;
        float: left;
    }
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
				<li><a href="../"><?php echo $text->Home; ?></a></li>
				<!--<li><a href="finances/index.php">Finances</a></li>-->
				<li class="active"><?php echo $text->monthly_revenue; ?></li>
			</ol>
		</div>

        <!-- Features Section -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header"><?php echo $text->monthly_revenue; ?></h2>
            </div>
        </div>
        <div class="row" >
            <div class="col-md-12">
                <form class="form-horizontal">

                    <div class="form-group">
                        <label class="control-label" for="selectbasic"><?php echo $text->select_department; ?></label>
						<select id="selectbasic" name="branchCode" class="form-control input-xxlarge department" style="width: 270px;">
						  <?php
						  
							$branches = $databaseHandler->getBranches();
						    foreach ($branches as $arr) {
								echo "<option value=\"".$arr['branchcode']."\">".$arr['branchname']."</option>\n";
							}
						  ?>
						</select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="from"><?php echo $text->from_date; ?></label>
						<input type='text' name="date" id="from" class="form-control date" value="<?php echo date("Y-m-d"); ?>" placeholder="<?php echo date("Y-m-d"); ?>">
                        <label class="control-label" for="to"><?php echo $text->to_date; ?></label>
						<input type='text' name="date" id="to" class="form-control date" value="<?php echo date("Y-m-d"); ?>" placeholder="<?php echo date("Y-m-d"); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="button">&nbsp;</label>
                        <input type="button" class="btn btn-default update" value="<?php echo $text->show; ?>">
                    </div>


				</form>
				
				<hr>
				
				<div class="row statistics" id="main_board">
                    <div class="col-lg-12">
						<div>
                            <h2><span id="department_name"></span><span id="date_range"></span></h2>
						</div>
                        <div class="alert alert-info">
                            <h3> <?php echo $text->total; ?>: <strong><span class="result_total">x</span></strong> <?php echo $text->currency; ?></h3>
                            <span id="pdf_monthly"><i class="fa fa-file-pdf-o  fa-2x"> PDF</i></span>
                        </div>
                    </div>
                </div>
				
				<div class="row statistics">
                    <div class="col-md-5ths col-xs-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-user fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge result_registrations">x</div>
                                        <div><?php echo $text->registrace; ?></div>
                                    </div>
                                </div>
                            </div>
                            <a href="#registrations_names">
                                <div class="panel-footer registrations_more">
                                    <span class="pull-left"><?php echo $text->view_details; ?></span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-5ths col-xs-6">
                        <div class="panel panel-red">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-warning fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge result_fines">x</div>
                                        <div><?php echo $text->fines; ?></div>
                                    </div>
                                </div>
                            </div>
                                <a href="#fines_detail"><div class="panel-footer view_fines_detail">
                                    <span class="pull-left"><?php echo $text->view_details; ?></span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div></a>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-5ths col-xs-6">
                        <div class="panel panel-brown">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-bookmark fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge result_res">x</div>
                                        <div><?php echo $text->reservations; ?></div>
                                    </div>
                                </div>
                            </div>
                            <a href="#reservations_detail">
                                <div class="panel-footer view_reservations_detail">
                                    <span class="pull-left"><?php echo $text->view_details; ?></span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-5ths col-xs-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-print fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge result_prints">x</div>
                                        <div><?php echo $text->prints; ?></div>
                                    </div>
                                </div>
                            </div>
                            <a href="#prints_detail">
                                <div class="panel-footer view_prints_detail">
                                    <span class="pull-left"><?php echo $text->view_details; ?></span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-5ths col-xs-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-cubes fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge result_other">x</div>
                                        <div><?php echo $text->other; ?></div>
                                    </div>
                                </div>
                            </div>
                            <a href="#others_detail">
                                <div class="panel-footer view_others_detail">
                                    <span class="pull-left"><?php echo $text->view_details; ?></span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                     
                </div>
                <!-- /.row -->

                 
                 <div class="row">            
                     <div class="col-lg-8" id="registrations_names">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user-plus"></i> <?php echo $text->registered; ?></h3>
                                <span id="remove_registered"><i class="fa fa-times"></i></span>
                                <span id="pdf_registered"><i class="fa fa-file-pdf-o"> PDF</i></span>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <p><?php echo $text->number_of_users; ?>: <span id="number_of_registered_users">x</span></p>
                                    <table class="table table-bordered table-hover table-striped registered">
                                        <thead>
                                            <tr>
                                                <th><?php echo $text->name; ?></th>
                                                <th><?php echo $text->surname; ?></th>
                                                <th><?php echo $text->price; ?> (<?php echo $text->currency; ?>)</th>
                                                <th><?php echo $text->time; ?></th>
                                                <th><?php echo $text->link; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="registered_body">

                                        </tbody>
                                    </table>
                                    <ul class="registered_pagination pagination">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
                <!-- /.row -->
                
                <div class="row">            
                     <div class="col-lg-8" id="fined_names">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user-plus"></i> <?php echo $text->fines; ?></h3>
                                <span id="remove_fined"><i class="fa fa-times"></i></span>
                                <span id="pdf_fined"><i class="fa fa-file-pdf-o"> PDF</i></span>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <p><?php echo $text->number_of_users; ?>: <span id="number_of_fined_users">x</span></p>
                                    <table class="table table-bordered table-hover table-striped fined">
                                        <thead>
                                            <tr>
                                                <th><?php echo $text->name; ?></th>
                                                <th><?php echo $text->surname; ?></th>
                                                <th><?php echo $text->price; ?> (<?php echo $text->currency; ?>)</th>
                                                <th><?php echo $text->time; ?></th>
                                                <th><?php echo $text->link; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="fined_body">

                                        </tbody>
                                    </table>
                                    <ul class="fined_pagination pagination">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
                <!-- /.row -->
                <div class="row">            
                     <div class="col-lg-8" id="reservationed_names">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user-plus"></i> <?php echo $text->reservations; ?></h3>
                                <span id="remove_reservationed"><i class="fa fa-times"></i></span>
                                <span id="pdf_reservationed"><i class="fa fa-file-pdf-o"> PDF</i></span>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <p><?php echo $text->number_of_users; ?>: <span id="number_of_reservationed_users">x</span></p>
                                    <table class="table table-bordered table-hover table-striped reservationed">
                                        <thead>
                                            <tr>
                                                <th><?php echo $text->name; ?></th>
                                                <th><?php echo $text->surname; ?></th>
                                                <th><?php echo $text->price; ?> (<?php echo $text->currency; ?>)</th>
                                                <th><?php echo $text->time; ?></th>
                                                <th><?php echo $text->link; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="reservationed_body">

                                        </tbody>
                                    </table>
                                    <ul class="reservationed_pagination pagination">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
                <!-- /.row -->
                
                <div class="row">            
                     <div class="col-lg-8" id="printed_names">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user-plus"></i> <?php echo $text->prints; ?></h3>
                                <span id="remove_printed"><i class="fa fa-times"></i></span>
                                <span id="pdf_printed"><i class="fa fa-file-pdf-o"> PDF</i></span>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <p><?php echo $text->number_of_users; ?>: <span id="number_of_printed_users">x</span></p>
                                    <table class="table table-bordered table-hover table-striped printed">
                                        <thead>
                                            <tr>
                                                <th><?php echo $text->name; ?></th>
                                                <th><?php echo $text->surname; ?></th>
                                                <th><?php echo $text->price; ?> (<?php echo $text->currency; ?>)</th>
                                                <th><?php echo $text->time; ?></th>
                                                <th><?php echo $text->link; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="printed_body">

                                        </tbody>
                                    </table>
                                    <ul class="printed">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
                <!-- /.row -->
                
                <div class="row">            
                     <div class="col-lg-8" id="othered_names">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user-plus"></i> <?php echo $text->other; ?></h3>
                                <span id="remove_othered"><i class="fa fa-times"></i></span>
                                <span id="pdf_othered"><i class="fa fa-file-pdf-o"> PDF</i></span>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <p><?php echo $text->number_of_users; ?>: <span id="number_of_othered_users">x</span></p>
                                    <table class="table table-bordered table-hover table-striped othered">
                                        <thead>
                                            <tr>
                                                <th><?php echo $text->name; ?></th>
                                                <th><?php echo $text->surname; ?></th>
                                                <th><?php echo $text->price; ?> (<?php echo $text->currency; ?>)</th>
                                                <th><?php echo $text->time; ?></th>
                                                <th><?php echo $text->link; ?></th>
                                                <th><?php echo $text->accounttype; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="othered_body">

                                        </tbody>
                                    </table>
                                    <ul class="othered_pagination pagination">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
                <!-- /.row -->
                
            </div>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <?php require_once '../tpl/footer.tpl.php'; ?>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="../js/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    
    <?php
        if (isset($langShortcut) AND ($langShortcut == "cs")) {
            echo "<script src='../js/datepicker-cs.js'></script>";
        }
    ?>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

    <script type="text/javascript">
        
        var registeredResults = [];
        var finedResults = [];
        var reservationedResults = [];
        var printedResults = [];
        var otheredResults = [];
		
		$(document).ready(function(){
            
            var departmentText = "";
            
            var total = "";
            var registrations = "";
            var fines = "";
            var reservations = "";
            var prints = "";
            
            $("#registrations_names").hide();
            $("#fined_names").hide();
            $("#reservationed_names").hide();
            $("#printed_names").hide();
            $("#othered_names").hide();

            $( "#from" ).datepicker({
     // defaultDate: "-1w",
      changeMonth: true,
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#to" ).datepicker({
      //defaultDate: "+1w",
      changeMonth: true,
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
    $( "#from" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
    $( "#to" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
    $( "#from" ).datepicker('setDate', "<?php echo $defaultFrom; ?>");
    $( "#to" ).datepicker('setDate', "<?php echo $defaultTo; ?>");
			
			$(".statistics").hide();
    
			$(".update").on("click", function(event){
				
				event.preventDefault();

				var department = $(".department").val();
				var from = $("#from").val();
                var to = $("#to").val();
                
                /* Update text and date in H3*/
                departmentText = $(".department option[value='"+department+"']").text();
                $("#department_name").text(departmentText);
                var friendlyFrom = $.datepicker.formatDate('dd.mm.yy', new Date(from));
                var friendlyTo = $.datepicker.formatDate('dd.mm.yy', new Date(to));
                $("#date_range").text(friendlyFrom+" - "+friendlyTo);
                /**/
				
				$.ajax({
					url: "../system/finances/MonthlyRevenueByDepartment.ajax.php",
					type: "POST",
					data: { department: department, from: from, to: to},
					dataType: "json"
				}).always(function(){
					console.log("Sending: department: "+ department+" , from: "+ from+", to: "+to);
				}).done(function(results){
					updateResult(results);
				}).fail(function(jqXHR, textStatus){
					console.log( "Request failed: " + textStatus );
				});
				
			});
			
			var updateResult = function(results){

				console.log("Response: "+results);
                
                total = results.total;
                registrations = results.registrations;
                fines = results.fines;
                prints = results.prints;
                reservations = results.res;
                others = results.other;

				$(".result_total").text(moneyFormat(results.total));
				$(".result_registrations").text(moneyFormat(results.registrations));
				$(".result_fines").text(moneyFormat(results.fines));
				$(".result_prints").text(moneyFormat(results.prints));
                $(".result_res").text(moneyFormat(results.res));
				$(".result_other").text(moneyFormat(results.other));
				
                $(".statistics").show();
                
				if(results.total == 0){
					$(".result_total").text("0");
                    $(".result_registrations").text("0");
                    $(".result_fines").text("0");
                    $(".result_prints").text("0");
                    $(".result_res").text("0");
                    $(".result_other").text("0");
				}
                
                $('html, body').animate({
        scrollTop: $("#main_board").offset().top
    }, 900);
				
			};
            
            $(".department").on("change", function(){
                $("#registrations_names").slideUp();
                $("#fined_names").slideUp();
                $("#reservationed_names").slideUp();
                $("#printed_names").slideUp();
                $("#othered_names").slideUp();
                $(".statistics").slideUp();
                
                $("#registered_body").find("tr").remove();
                
            });
			$("#from").on("change", function(){
                $("#registrations_names").slideUp();
                $("#fined_names").slideUp();
                $("#reservationed_names").slideUp();
                $("#printed_names").slideUp();
                $("#othered_names").slideUp();
                
                $(".statistics").slideUp();
                
                $("#registered_body").find("tr").remove();
                
            });
            $("#to").on("change", function(){
                $("#registrations_names").slideUp();
                $("#fined_names").slideUp();
                $("#reservationed_names").slideUp();
                $("#printed_names").slideUp();
                $("#othered_names").slideUp();
                $(".statistics").slideUp();
                
                $("#registered_body").find("tr").remove();
                
            });
            
            /* Registrations */
            
            $(".registrations_more").on("click", function(){
               
               console.log("Clicking on Registrations More");
               var department = $(".department").val();
			   var from = $("#from").val();
               var to = $("#to").val();
               
               $.ajax({
					url: "../system/finances/monthly_registrations.ajax.php",
					type: "POST",
					data: { department: department, from: from, to: to},
					dataType: "json"
				}).always(function(){
					$("#registrations_names").slideDown();
                    console.log("Getting registrated names...");
				}).done(function(results){
					addRegistered(results);
                    console.log("Paraada Tome");
				}).fail(function(jqXHR, textStatus){
					console.log( "Request failed: " + textStatus );
				});
                
            });
            
            var addRegistered = function(results){
                
                registeredResults = results;
              
                $("#registered_body").find("tr").remove();
                console.log(results);
              
                var dayFrom = results.dayFrom;
                var dayTo = results.dayTo;
                var rows = results.rows;
                var number_of_registered_users = results.number_of_users;
                
                $("#number_of_registered_users").text(number_of_registered_users);
              
                if (number_of_registered_users <= 10) {
                    $.each(rows, function(i, item) {
                        $('.registered').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    });
                }
                else {
                    // print 10
                    for(var i = 0; i < 10; i++) {
                        $('.registered').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                    // print paginator
                    printPagination("registered_pagination", number_of_registered_users, "changeRegisteredPage", 1);
                }
                
               $('html, body').animate({
        scrollTop: $("#registrations_names").offset().top
    }, 900);
                
            };
            
            $("#pdf_registered").on("click", function(){
                
               console.log("Exporting registered to PDF");
               var department = $(".department").val();
			   var from = $("#from").val();
               var to = $("#to").val();
                
               window.open('monthlyRegisteredToPDF.php?department='+department+'&from='+from+'&to='+to+"&departmentText="+departmentText);
               
            });
            
            /* Registrations end */
            
            /* Fines */
            $(".view_fines_detail").on("click", function(){
               
               console.log("Clicking on Fines More");
               var department = $(".department").val();
			   var from = $("#from").val();
               var to = $("#to").val();
               
               $.ajax({
					url: "../system/finances/monthly_fines.ajax.php",
					type: "POST",
					data: { department: department, from: from, to: to},
					dataType: "json"
				}).always(function(){
					$("#fined_names").slideDown();
                    console.log("Getting fines...");
				}).done(function(results){
					addFines(results);
                    console.log("Paraada Tome");
				}).fail(function(jqXHR, textStatus){
					console.log( "Request failed: " + textStatus );
				});
                
            });
            
            var addFines = function(results){
                
                finedResults = results;
              
                $("#fined_body").find("tr").remove();
                console.log(results);
              
                var dayFrom = results.dayFrom;
                var dayTo = results.dayTo;
                var rows = results.rows;
                var number_of_fined_users = results.number_of_users;
                
                $("#number_of_fined_users").text(number_of_fined_users);
              
                if (number_of_fined_users <= 10) {
                    $.each(rows, function(i, item) {
                        $('.fined').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    });
                }
                else {
                    // print 10
                    for(var i = 0; i < 10; i++) {
                        $('.fined').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                    // print paginator
                    printPagination("fined_pagination", number_of_fined_users, "changeFinedPage", 1);
                }
                
               $('html, body').animate({
        scrollTop: $("#fined_names").offset().top
    }, 900);
                
            };
            
            $("#pdf_fined").on("click", function(){
                
               console.log("Exporting fined to PDF");
               var department = $(".department").val();
			   var from = $("#from").val();
               var to = $("#to").val();
                
               window.open('monthlyFinedToPDF.php?department='+department+'&from='+from+'&to='+to+"&departmentText="+departmentText);
               
            });
            
             /* Fines END */
             
             /* Reservations */
            $(".view_reservations_detail").on("click", function(){
               
               console.log("Clicking on Reservations More");
               var department = $(".department").val();
			   var from = $("#from").val();
               var to = $("#to").val();
               
               $.ajax({
					url: "../system/finances/monthly_reservations.ajax.php",
					type: "POST",
					data: { department: department, from: from, to: to},
					dataType: "json"
				}).always(function(){
					$("#reservationed_names").slideDown();
                    console.log("Getting fines...");
				}).done(function(results){
					addReservations(results);
                    console.log("Paraada Tome");
				}).fail(function(jqXHR, textStatus){
					console.log( "Request failed: " + textStatus );
				});
                
            });
            
            var addReservations = function(results){
                
                reservationedResults = results;
              
                $("#reservationed_body").find("tr").remove();
                console.log(results);
              
                var dayFrom = results.dayFrom;
                var dayTo = results.dayTo;
                var rows = results.rows;
                var number_of_reservationed_users = results.number_of_users;
                
                $("#number_of_reservationed_users").text(number_of_reservationed_users);
              
                if (number_of_reservationed_users <= 10) {
                    $.each(rows, function(i, item) {
                        $('.reservationed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    });
                }
                else {
                    // print 10
                    for(var i = 0; i < 10; i++) {
                        $('.reservationed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                    // print paginator
                    printPagination("reservationed_pagination", number_of_reservationed_users, "changeReservationedPage", 1);
                }
                
               $('html, body').animate({
        scrollTop: $("#reservationed_names").offset().top
    }, 900);
                
            };
            
            $("#pdf_reservationed").on("click", function(){
                
               console.log("Exporting reservationed to PDF");
               var department = $(".department").val();
			   var from = $("#from").val();
               var to = $("#to").val();
                
               window.open('monthlyReservationedToPDF.php?department='+department+'&from='+from+'&to='+to+"&departmentText="+departmentText);
               
            });
            
             /* Reservations END */
             
             /* Prints */
            $(".view_prints_detail").on("click", function(){
               
               console.log("Clicking on Prnts More");
               var department = $(".department").val();
			   var from = $("#from").val();
               var to = $("#to").val();
               
               $.ajax({
					url: "../system/finances/monthly_prints.ajax.php",
					type: "POST",
					data: { department: department, from: from, to: to},
					dataType: "json"
				}).always(function(){
					$("#printed_names").slideDown();
                    console.log("Getting prints...");
				}).done(function(results){
					addPrints(results);
                    console.log("Paraada Tome");
				}).fail(function(jqXHR, textStatus){
					console.log( "Request failed: " + textStatus );
				});
                
            });
            
            var addPrints = function(results){
                
                printedResults = results;
              
                $("#printed_body").find("tr").remove();
                console.log(results);
              
                var dayFrom = results.dayFrom;
                var dayTo = results.dayTo;
                var rows = results.rows;
                var number_of_printed_users = results.number_of_users;
                
                $("#number_of_printed_users").text(number_of_printed_users);
              
               if (number_of_printed_users <= 10) {
                    $.each(rows, function(i, item) {
                        $('.printed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    });
                }
                else {
                    // print 10
                    for(var i = 0; i < 10; i++) {
                        $('.printed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                    // print paginator
                    printPagination("printed_pagination", number_of_printed_users, "changePrintedPage", 1);
                }
                
               $('html, body').animate({
        scrollTop: $("#printed_names").offset().top
    }, 900);
                
            };
            
            $("#pdf_printed").on("click", function(){
                
               console.log("Exporting printed to PDF");
               var department = $(".department").val();
			   var from = $("#from").val();
               var to = $("#to").val();
                
               window.open('monthlyPrintedToPDF.php?department='+department+'&from='+from+'&to='+to+"&departmentText="+departmentText);
               
            });
            
             /* prints END */
             
             /* Others */
            $(".view_others_detail").on("click", function(){
               
               console.log("Clicking on others More");
               var department = $(".department").val();
			   var from = $("#from").val();
               var to = $("#to").val();
               
               $.ajax({
					url: "../system/finances/monthly_others.ajax.php",
					type: "POST",
					data: { department: department, from: from, to: to},
					dataType: "json"
				}).always(function(){
					$("#othered_names").slideDown();
                    console.log("Getting others...");
				}).done(function(results){
					addOthers(results);
                    console.log("Paraada Tome");
				}).fail(function(jqXHR, textStatus){
					console.log( "Request failed: " + textStatus );
				});
                
            });
            
            var addOthers = function(results){
                
                otheredResults = results;
              
                $("#othered_body").find("tr").remove();
                console.log(results);
              
                var dayFrom = results.dayFrom;
                var dayTo = results.dayTo;
                var rows = results.rows;
                var number_of_othered_users = results.number_of_users;
                
                $("#number_of_othered_users").text(number_of_othered_users);
              
                if (number_of_othered_users <= 10) {
                    $.each(rows, function(i, item) {
                        $('.othered').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td><td>"+rows[i].accounttype+"</td></tr>");
                    });
                }
                else {
                    // print 10
                    for(var i = 0; i < 10; i++) {
                        $('.othered').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td><td>"+rows[i].accounttype+"</td></tr>");
                    }
                    // print paginator
                    printPagination("othered_pagination", number_of_othered_users, "changeOtheredPage", 1);
                }
                
               $('html, body').animate({
        scrollTop: $("#othered_names").offset().top
    }, 900);
                
            };
            
            $("#pdf_othered").on("click", function(){
                
               console.log("Exporting othered to PDF");
               var department = $(".department").val();
			   var from = $("#from").val();
               var to = $("#to").val();
                
               window.open('monthlyOtheredToPDF.php?department='+department+'&from='+from+'&to='+to+"&departmentText="+departmentText);
               
            });
            
             /* others END */
             
             /* PDF Monthly */
             $("#pdf_monthly").on("click", function(){
                
               console.log("Exporting monthly PDF");
               var department = $(".department").val();
			   var from = $("#from").val();
               var to = $("#to").val();
                
               window.open('monthlyPDF.php?department='+department+'&from='+from+'&to='+to+"&departmentText="+departmentText+"&total="+total+"&registrations="+registrations+"&fines="+fines+"&reservations="+reservations+"&prints="+prints+"&others="+others);
               
            });
            /* PDF Monthly END*/
             
            
            
            /* Close details */
            
            $("#remove_registered").on("click", function(){
                $("#registrations_names").slideUp();
            });
            $("#remove_fined").on("click", function(){
                $("#fined_names").slideUp();
            });
            $("#remove_reservationed").on("click", function(){
                $("#reservationed_names").slideUp();
            });
            $("#remove_printed").on("click", function(){
                $("#printed_names").slideUp();
            });
            $("#remove_othered").on("click", function(){
                $("#othered_names").slideUp();
            });
                
            /* Close details END */
            
           

		}); //document ready END
        
         /* Paginating */
            
            var changeRegisteredPage = function(goToPage){
               console.log("Clicking on Go To Page: "+goToPage);
               $(".registered_pagination li").removeClass("active");
               $(".registered_pagination li a#"+goToPage).addClass("active");
               $("#registered_body").find("tr").remove();
               var rows = registeredResults.rows;
               var fromRecord = (goToPage*10)-10;
               
                var lastPage = Math.floor(registeredResults.number_of_users / 10);
                var rest = registeredResults.number_of_users % 10;
                if (rest > 0){
                    lastPage = lastPage + 1;
                }
                
                if (goToPage == lastPage) {
                    var toRecord = fromRecord + rest;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.registered').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                else {
                    var toRecord = fromRecord + 10;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.registered').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                
                printPagination("registered_pagination", registeredResults.number_of_users, "changeRegisteredPage", goToPage);

            };
            
            var changeFinedPage = function(goToPage){
               console.log("Clicking on Go To Page: "+goToPage);
               $(".fined_pagination li").removeClass("active");
               $(".fined_pagination li a#"+goToPage).addClass("active");
               $("#fined_body").find("tr").remove();
               var rows = finedResults.rows;
               var fromRecord = (goToPage*10)-10;
               
                var lastPage = Math.floor(finedResults.number_of_users / 10);
                var rest = finedResults.number_of_users % 10;
                if (rest > 0){
                    lastPage = lastPage + 1;
                }
                
                if (goToPage == lastPage) {
                    var toRecord = fromRecord + rest;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.fined').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                else {
                    var toRecord = fromRecord + 10;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.fined').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                
                printPagination("fined_pagination", finedResults.number_of_users, "changeFinedPage", goToPage);

            };
            
            var changeReservationedPage = function(goToPage){
               console.log("Clicking on Go To Page: "+goToPage);
               $(".reservationed_pagination li").removeClass("active");
               $(".reservationed_pagination li a#"+goToPage).addClass("active");
               $("#reservationed_body").find("tr").remove();
               var rows = reservationedResults.rows;
               var fromRecord = (goToPage*10)-10;
               
                var lastPage = Math.floor(reservationedResults.number_of_users / 10);
                var rest = reservationedResults.number_of_users % 10;
                if (rest > 0){
                    lastPage = lastPage + 1;
                }
                
                if (goToPage == lastPage) {
                    var toRecord = fromRecord + rest;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.reservationed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                else {
                    var toRecord = fromRecord + 10;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.reservationed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                
                printPagination("reservationed_pagination", reservationedResults.number_of_users, "changeReservationedPage", goToPage);

            };
            
            var changePrintedPage = function(goToPage){
               console.log("Clicking on Go To Page: "+goToPage);
               $(".printed_pagination li").removeClass("active");
               $(".printed_pagination li a#"+goToPage).addClass("active");
               $("#printed_body").find("tr").remove();
               var rows = printedResults.rows;
               var fromRecord = (goToPage*10)-10;
               
                var lastPage = Math.floor(printedResults.number_of_users / 10);
                var rest = printedResults.number_of_users % 10;
                if (rest > 0){
                    lastPage = lastPage + 1;
                }
                
                if (goToPage == lastPage) {
                    var toRecord = fromRecord + rest;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.printed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                else {
                    var toRecord = fromRecord + 10;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.printed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                
                printPagination("printed_pagination", printedResults.number_of_users, "changePrintedPage", goToPage);

            };
            
            var changeOtheredPage = function(goToPage){
               console.log("Clicking on Go To Page: "+goToPage);
               $(".othered_pagination li").removeClass("active");
               $(".othered_pagination li a#"+goToPage).addClass("active");
               $("#othered_body").find("tr").remove();
               var rows = otheredResults.rows;
               var fromRecord = (goToPage*10)-10;
               
                var lastPage = Math.floor(otheredResults.number_of_users / 10);
                var rest = otheredResults.number_of_users % 10;
                if (rest > 0){
                    lastPage = lastPage + 1;
                }
                
                if (goToPage == lastPage) {
                    var toRecord = fromRecord + rest;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.othered').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td><td>"+rows[i].accounttype+"</td></tr>");
                    }
                }
                else {
                    var toRecord = fromRecord + 10;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.othered').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].value)+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td><td>"+rows[i].accounttype+"</td></tr>");
                    }
                }
                
                printPagination("othered_pagination", otheredResults.number_of_users, "changeOtheredPage", goToPage);

            };
            /* paginating END */
            
            /* Money Format*/
            var moneyFormat = function(amount) {
                return amount.toString().replace(/.(?=(?:.{3})+$)/g, '$& ');
            };
            /* Money Format END*/
            
            /* Print pagination */
            var printPagination = function(paginationClass, number_of_users, paginationHandler, currentPage)
            {
                
                $('.'+paginationClass+'').find("li").remove();
                
                var nobuttons = Math.floor(number_of_users / 10) + 1;                    
                    
                var min = currentPage;
                var max = currentPage;
                var active = currentPage;
                    
                if((active + 2) <= nobuttons ) {max = active + 2; }
                else if((active + 1) <= nobuttons ) {max = active + 1; }
                else {max = active; }
                    
                if((active - 2) >= 1 ) {min = active - 2; }
                else if((active -1) >= 1 ) {min = active - 1; }
                else {min = active; }
                    
                var first = 1;
                
                if(active == 1){
                    previous = active;
                }
                else{
                    var previous = active - 1;
                }
                    
                if(active == nobuttons){
                    next = active;
                }
                else{
                    var next = active + 1;
                }    
                
                var last = nobuttons;
                    
                $('.'+paginationClass+'').append("<li><a id='1' onclick='"+paginationHandler+"(1);' class='"+paginationHandler+" first' href='#1'>&laquo;</a></li>");
                $('.'+paginationClass+'').append("<li><a id='"+previous+"' onclick='"+paginationHandler+"("+previous+");' class='"+paginationHandler+" previous' href='#"+previous+"'>&larr;</a></li>");
                $('.'+paginationClass+'').append("<li><a id='"+next+"' onclick='"+paginationHandler+"("+next+");' class='"+paginationHandler+" next' href='#"+next+"'>&rarr;</a></li>");
                $('.'+paginationClass+'').append("<li><a id='"+nobuttons+"' onclick='"+paginationHandler+"("+nobuttons+");' class='"+paginationHandler+" last' href='#"+nobuttons+"'>&raquo;</a></li>");
                    
                var order = 0;
                for(var i = min; i <= max; i++) {
                    order = i + 1;
                    if (i == active) {
                        $('.'+paginationClass+' > li:nth-child('+(order)+')').after("<li class='active'><a id='"+i+"' onclick='"+paginationHandler+"("+i+");' class='"+paginationHandler+"' href='#"+i+"'>"+i+"</a></li>");
                    }
                    else{
                        $('.'+paginationClass+' > li:nth-child('+(order)+')').after("<li><a id='"+i+"' onclick='"+paginationHandler+"("+i+");' class='"+paginationHandler+"' href='#"+i+"'>"+i+"</a></li>");
                    }
                }
                    
                if(active > 1){
                    $('.'+paginationClass+' .first').parent().removeClass("disabled");
                    $('.'+paginationClass+' .previous').parent().removeClass("disabled");
                }
                else {
                    $('.'+paginationClass+' .first').parent().addClass("disabled");
                    $('.'+paginationClass+' .previous').parent().addClass("disabled");
                }
                    
                if(active < nobuttons){
                    $('.'+paginationClass+' .next').parent().removeClass("disabled");
                    $('.'+paginationClass+' .last').parent().removeClass("disabled");
                }
                else{
                    $('.'+paginationClass+' .next').parent().addClass("disabled");
                    $('.'+paginationClass+' .last').parent().addClass("disabled");
                }
                
            };
            /* Print pagination END */
    
    </script>
    
	<?php 
		require_once "../tpl/footer.php"; 
    ?>
