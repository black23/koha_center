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
	require_once "../system/visits/DatabaseHandler.class.php";
	
	$databaseHandler = new DatabaseHandler($db);
	
	$defaultFrom = date("Y")."-01-01";
    $defaultTo = date("Y")."-12-31";
	
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php 
		$title = $text->yearly_visits;
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

.panel-violet {
    border-color: #8b3664;
}

.panel-violet .panel-heading {
    border-color: #8b3664;
    color: #fff;
    background-color: #8b3664;
}

.panel-violet a {
    color: #8b3664;
}

.panel-violet a:hover {
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
.panel-violet {
    border-color: #8b3664;
}

.panel-violet .panel-heading {
    border-color: #8b3664;
    color: #fff;
    background-color: #8b3664;
}

.panel-violet a {
    color: #8b3664;
}

.panel-violet a:hover {
    color: #8b2500;
}


.panel-lightviolet {
    border-color: #c5699b;
}

.panel-lightviolet .panel-heading {
    border-color: #c5699b;
    color: #fff;
    background-color: #c5699b;
}

.panel-lightviolet a {
    color: #c5699b;
}

.panel-lightviolet a:hover {
    color: #c5699b;
}



.panel-lightprimary {
    border-color: #63bcd4;
}

.panel-lightprimary .panel-heading {
    border-color: #63bcd4;
    color: #fff;
    background-color: #63bcd4;
}

.panel-lightprimary a {
    color: #63bcd4;
}

.panel-lightprimary a:hover {
    color: #63bcd4;
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
				<li class="active"><?php echo $text->yearly_visits; ?></li>
			</ol>
		</div>

        <!-- Features Section -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header"><?php echo $text->yearly_visits; ?></h2>
            </div>
        </div>
        <div class="row" >
            <div class="col-md-12">
                <form class="form-horizontal">

                    <div class="form-group">
                        <label class="control-label" for="selectbasic"><?php echo $text->select_department; ?></label>
						<select id="selectbasic" name="branchCode" class="form-control input-xxlarge department" style="width: 270px;">
                            <option value="allDepartments"><?php echo $text->allDepartments; ?></option>
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
						<input type='text' name="date" id="from" class="form-control date">
                        <label class="control-label" for="to"><?php echo $text->to_date; ?></label>
						<input type='text' name="date" id="to" class="form-control date">
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
                            <h3> <?php echo $text->total_unique_visits; ?>: <strong><span class="result_total">x</span></strong></h3>
                            <span id="pdf_yearly"><i class="fa fa-file-pdf-o  fa-2x"> PDF</i></span>
                        </div>
                    </div>
                </div>
				
				<div class="row statistics">
                    <div class="col-lg-4">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-money fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge result_paids">x</div>
                                        <div><?php echo $text->paid; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-red">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-user-plus fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge result_registrations">x</div>
                                        <div><?php echo $text->registered_itself; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-brown">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-mail-forward fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge result_borrows">x</div>
                                        <div><?php echo $text->borrowed; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row statistics">         
                    <div class="col-lg-4">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-mail-reply fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge result_returns">x</div>
                                        <div><?php echo $text->returned; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-refresh fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge result_prolongs">x</div>
                                        <div><?php echo $text->prolonged_offline; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-violet">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-bookmark fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge result_reservations">x</div>
                                        <div><?php echo $text->reserved_offline; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     
                </div>
                <!-- /.row -->
                
                <div class="row statistics">         
                    <div class="col-lg-4">

                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-lightprimary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-refresh fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge result_virtualProlongs">x</div>
                                        <div><?php echo $text->prolonged_online; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-lightviolet">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-bookmark fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge result_virtualReservations">x</div>
                                        <div><?php echo $text->reserved_online; ?></div>
                                    </div>
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

        <div class="row">
            <div class="col-md-12">
                <!--<div id="yearlyVisitsByDepartmentChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>-->
            </div>
        </div>
        
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
    
    <script src="http://code.highcharts.com/highcharts.js"></script>
	<script src="http://code.highcharts.com/modules/exporting.js"></script>

    <script type="text/javascript">
		
		$(document).ready(function(){
            
            var departmentText = "";
            
            var total = "";
            var registrations = "";
            var borrows = "";
            var reservations = "";
            var returns = "";
            var paids = "";
            var prolongs = "";
            var virtualProlongs = "";
            var virtualReservations = "";
            
            var date1 = "";

            $( "#from" ).datepicker({
     // defaultDate: "-1w",
      changeMonth: true,
      changeYear: true,
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
        date1 = selectedDate;
      }
    });
    $( "#to" ).datepicker({
      //defaultDate: "+1w",
      changeMonth: true,
      changeYear: true,
      minDate: date1, 
      maxDate: "+12M",
      onClose: function( selectedDate ) {
       // $( "#from" ).datepicker( "option", "maxDate", selectedDate );
      },
      
    });
    $( "#from" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
    $( "#to" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
    $( "#from" ).datepicker('setDate', "<?php echo $defaultFrom; ?>");
    $( "#to" ).datepicker('setDate', "<?php echo $defaultTo; ?>");
			
			$(".statistics").hide();
            $("#yearlyVisitsByDepartmentChart").hide();
    
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
					url: "../system/visits/YearlyVisitsByDepartment.ajax.php",
					type: "POST",
					data: { department: department, from: from, to: to},
					dataType: "json"
				}).always(function(){
					console.log("Sending: department: "+ department+" , from: "+ from+", to: "+to);
				}).done(function(results){
					updateResult(results, departmentText, friendlyFrom, friendlyTo);
				}).fail(function(jqXHR, textStatus){
					console.log( "Request failed: " + textStatus );
				});
				
			});
			
			var updateResult = function(results, departmentText, friendlyFrom, friendlyTo){

				console.log("Response: "+results);
                
                total = results.total;
                registrations = results.registrations;
                borrows = results.borrows;
                returns = results.returns;
                reservations = results.reservations;
                paids = results.paids;
                prolongs = results.prolongs;
                virtualProlongs = results.virtualProlongs;
                virtualReservations = results.virtualReservations;

				$(".result_total").text(moneyFormat(results.total));
				$(".result_registrations").text(moneyFormat(results.registrations));
				$(".result_borrows").text(moneyFormat(results.borrows));
				$(".result_returns").text(moneyFormat(results.returns));
                $(".result_reservations").text(moneyFormat(results.reservations));
				$(".result_paids").text(moneyFormat(results.paids));
                $(".result_prolongs").text(moneyFormat(results.prolongs));
				$(".result_virtualReservations").text(moneyFormat(results.virtualReservations));
                                $(".result_virtualProlongs").text(moneyFormat(results.virtualProlongs));
                $(".statistics").slideDown();
                $("#yearlyVisitsByDepartmentChart").slideDown();
                
				if(results.total == 0){
					$(".result_total").text("0");
                    $(".result_registrations").text("0");
                    $(".result_borrows").text("0");
                    $(".result_returns").text("0");
                    $(".result_reservations").text("0");
                    $(".result_paids").text("0");
                    $(".result_prolongs").text("0");
                    $(".result_virtualReservations").text("0");
                    $(".result_virtualProlongs").text("0");
				}
                
                /* Yearly visits by department Chart */

                $('#yearlyVisitsByDepartmentChart').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '<?php echo $text->yearly_visits; ?>'
                    },
                    subtitle: {
                        text: ''+departmentText+' '+friendlyFrom+' - '+friendlyTo+''
                    },
                    xAxis: {
                        categories: [
                            <?php echo $months; ?>
                        ]
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: '<?php echo $text->currency; ?>'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.1f} <?php echo $text->currency; ?></b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    colors: [
                        '#5cb85c',
                        '#d9534f',
                        '#854513',
                        '#f0ad4e',
                        '#337ab7',
                        '8b3664'
                    ],
                    series: [{
                        name: '<?php echo $text->registered_itself; ?>',
                        data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]
                    }, {
                        name: '<?php echo $text->borrowed; ?>',
                        data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

                    }, {
                        name: '<?php echo $text->reserved_offline; ?>',
                        data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]

                    }, {
                        name: '<?php echo $text->returned; ?>',
                        data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]

                    }, {
                        name: '<?php echo $text->paid; ?>',
                        data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]

                    }, {
                        name: '<?php echo $text->prolonged_offline; ?>',
                        data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]

                    }]
                });
               /* Yearly visits by department Chart END */
				
                $('html, body').animate({
        scrollTop: $("#main_board").offset().top
    }, 900);
                
			};
            
            $(".department").on("change", function(){
                $(".statistics").fadeOut();
                $("#yearlyVisitsByDepartmentChart").hide();
            });
			$("#from").on("change", function(){                
                $(".statistics").fadeOut();
                $("#yearlyVisitsByDepartmentChart").hide();
            });
            $("#to").on("change", function(){
                $(".statistics").fadeOut();
                $("#yearlyVisitsByDepartmentChart").hide();
            });
            
             /* PDF Yearly */
             $("#pdf_yearly").on("click", function(){
                
               console.log("Exporting yearly PDF");
               var department = $(".department").val();
			   var from = $("#from").val();
               var to = $("#to").val();
                
               window.open('yearlyPDF.php?department='+department+'&from='+from+'&to='+to+"&departmentText="+departmentText+"&total="+total+"&registrations="+registrations+"&borrows="+borrows+"&reservations="+reservations+"&returns="+returns+"&paids="+paids+"&prolongs="+prolongs);
               
            });
            /* PDF Yearly END*/
             
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
           
		});
    
    </script>
    
	<?php 
		require_once "../tpl/footer.php"; 
    ?>
