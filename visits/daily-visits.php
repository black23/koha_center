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
	
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php 
		$title = $text->daily_visits;
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
				<!--<li><a href="visits/index.php">Visits</a></li>-->
				<li class="active"><?php echo $text->daily_visits; ?></li>
			</ol>
		</div>

        <!-- Features Section -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header"><?php echo $text->daily_visits; ?></h2>
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
                        <label class="control-label" for="selectdate"><?php echo $text->select_date; ?></label>
						<input type='text' name="date" id="datepicker" class="form-control date" value="<?php echo date("Y-m-d"); ?>" placeholder="<?php echo date("Y-m-d"); ?>">
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
                            <span id="pdf_daily"><i class="fa fa-file-pdf-o  fa-2x"> PDF</i></span>
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
                            <a href="#paids_names">
                                <div class="panel-footer view_paids_detail">
                                    <span class="pull-left"><?php echo $text->view_details; ?></span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
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
                                <a href="#registrations_detail"><div class="panel-footer view_registrations_detail">
                                    <span class="pull-left"><?php echo $text->view_details; ?></span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div></a>
                            </a>
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
                            <a href="#borrows_detail">
                                <div class="panel-footer view_borrows_detail">
                                    <span class="pull-left"><?php echo $text->view_details; ?></span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
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
                            <a href="#returns_detail">
                                <div class="panel-footer view_returns_detail">
                                    <span class="pull-left"><?php echo $text->view_details; ?></span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
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
                            <a href="#prolongs_detail">
                                <div class="panel-footer view_prolongs_detail">
                                    <span class="pull-left"><?php echo $text->view_details; ?></span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
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
                            <a href="#reservations_detail">
                                <div class="panel-footer view_reservations_detail">
                                    <span class="pull-left"><?php echo $text->view_details; ?></span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
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
                            <a href="#prolongs_detail">
                                <div class="panel-footer view_virtualProlongs_detail">
                                    <span class="pull-left"><?php echo $text->view_details; ?></span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
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
                            <a href="#reservations_detail">
                                <div class="panel-footer view_virtualReservations_detail">
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
                                <span id="remove_registered" class="remove"><i class="fa fa-times"></i></span>
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
                     <div class="col-lg-8" id="borrowed_names">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user-plus"></i> <?php echo $text->borrowed; ?></h3>
                                <span id="remove_borrowed" class="remove"><i class="fa fa-times"></i></span>
                                <span id="pdf_borrowed"><i class="fa fa-file-pdf-o pdf"> PDF</i></span>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <p><?php echo $text->number_of_users; ?>: <span id="number_of_borrowed_users">x</span></p>
                                    <table class="table table-bordered table-hover table-striped borrowed">
                                        <thead>
                                            <tr>
                                                <th><?php echo $text->name; ?></th>
                                                <th><?php echo $text->surname; ?></th>
                                                <th><?php echo $text->count; ?></th>
                                                <th><?php echo $text->time; ?></th>
                                                <th><?php echo $text->link; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="borrowed_body">

                                        </tbody>
                                    </table>
                                    <ul class="borrowed_pagination pagination">
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
                                <h3 class="panel-title"><i class="fa fa-user-plus"></i> <?php echo $text->reserved_offline; ?></h3>
                                <span id="remove_reservationed" class="remove"><i class="fa fa-times"></i></span>
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
                     <div class="col-lg-8" id="paid_names">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user-plus"></i> <?php echo $text->paid; ?></h3>
                                <span id="remove_paid" class="remove"><i class="fa fa-times"></i></span>
                                <span id="pdf_paid"><i class="fa fa-file-pdf-o pdf"> PDF</i></span>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <p><?php echo $text->number_of_users; ?>: <span id="number_of_paid_users">x</span></p>
                                    <table class="table table-bordered table-hover table-striped paid">
                                        <thead>
                                            <tr>
                                                <th><?php echo $text->name; ?></th>
                                                <th><?php echo $text->surname; ?></th>
                                                <th><?php echo $text->time; ?></th>
                                                <th><?php echo $text->type; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="paid_body">

                                        </tbody>
                                    </table>
                                    <ul class="paid_pagination pagination">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
                <!-- /.row -->
                
                <div class="row">            
                     <div class="col-lg-8" id="returned_names">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user-plus"></i> <?php echo $text->returned; ?></h3>
                                <span id="remove_returned" class="remove"><i class="fa fa-times"></i></span>
                                <span id="pdf_returned"><i class="fa fa-file-pdf-o pdf"> PDF</i></span>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <p><?php echo $text->number_of_users; ?>: <span id="number_of_returned_users">x</span></p>
                                    <table class="table table-bordered table-hover table-striped returned">
                                        <thead>
                                            <tr>
                                                <th><?php echo $text->name; ?></th>
                                                <th><?php echo $text->surname; ?></th>
                                                <th><?php echo $text->count; ?></th>
                                                <th><?php echo $text->time; ?></th>
                                                <th><?php echo $text->link; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="returned_body">

                                        </tbody>
                                    </table>
                                    <ul class="returned_pagination pagination">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
                <!-- /.row -->
                
                <div class="row">            
                     <div class="col-lg-8" id="prolonged_names">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user-plus"></i> <?php echo $text->prolonged_offline; ?></h3>
                                <span id="remove_prolonged" class="remove"><i class="fa fa-times"></i></span>
                                <span id="pdf_prolonged"><i class="fa fa-file-pdf-o pdf"> PDF</i></span>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <p><?php echo $text->number_of_users; ?>: <span id="number_of_prolonged_users">x</span></p>
                                    <table class="table table-bordered table-hover table-striped prolonged">
                                        <thead>
                                            <tr>
                                                <th><?php echo $text->name; ?></th>
                                                <th><?php echo $text->surname; ?></th>
                                                <th><?php echo $text->count; ?></th>
                                                <th><?php echo $text->time; ?></th>
                                                <th><?php echo $text->link; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="prolonged_body">

                                        </tbody>
                                    </table>
                                    <ul class="prolonged_pagination pagination">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
                <!-- /.row -->
                
                <div class="row">            
                     <div class="col-lg-8" id="virtualProlonged_names">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user-plus"></i> <?php echo $text->prolonged_online; ?></h3>
                                <span id="remove_virtualProlonged" class="remove"><i class="fa fa-times"></i></span>
                                <span id="pdf_virtualProlonged"><i class="fa fa-file-pdf-o pdf"> PDF</i></span>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <p><?php echo $text->number_of_users; ?>: <span id="number_of_virtualProlonged_users">x</span></p>
                                    <table class="table table-bordered table-hover table-striped virtualProlonged">
                                        <thead>
                                            <tr>
                                                <th><?php echo $text->name; ?></th>
                                                <th><?php echo $text->surname; ?></th>
                                                <th><?php echo $text->count; ?></th>
                                                <th><?php echo $text->time; ?></th>
                                                <th><?php echo $text->link; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="virtualProlonged_body">

                                        </tbody>
                                    </table>
                                    <ul class="virtualProlonged_pagination pagination">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
                <!-- /.row -->
                
               <div class="row">            
                     <div class="col-lg-8" id="virtualReservationed_names">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user-plus"></i> <?php echo $text->reserved_online; ?></h3>
                                <span id="remove_virtualReservationed" class="remove"><i class="fa fa-times"></i></span>
                                <span id="pdf_virtualReservationed"><i class="fa fa-file-pdf-o pdf"> PDF</i></span>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <p><?php echo $text->number_of_users; ?>: <span id="number_of_virtualReservationed_users">x</span></p>
                                    <table class="table table-bordered table-hover table-striped virtualReservationed">
                                        <thead>
                                            <tr>
                                                <th><?php echo $text->name; ?></th>
                                                <th><?php echo $text->surname; ?></th>
                                                <th><?php echo $text->time; ?></th>
                                                <th><?php echo $text->link; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="virtualReservationed_body">

                                        </tbody>
                                    </table>
                                    <ul class="virtualReservationed_pagination pagination">
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

    <script type="text/javascript">
        
        var registeredResults = [];
        var borrowedResults = [];
        var reservationedResults = [];
        var returnedResults = [];
        var paidResults = [];
        var prolongedResults = [];
        var virtualProlongedResults = [];
        var virtualReservationedResults = [];
		
		$(document).ready(function(){
            
            var departmentText = "";
            
            var total = "";
            var registrations = "";
            var borrows = "";
            var reservations = "";
            var returns = "";
            var paids = "";
            var prolongs = "";
            var virtualReservations = "";
            var virtualProlongs = "";
            
            $("#registrations_names").hide();
            $("#borrowed_names").hide();
            $("#reservationed_names").hide();
            $("#returned_names").hide();
            $("#paid_names").hide();
            $("#prolonged_names").hide();
            $("#virtualProlonged_names").hide();
            $("#virtualReservationed_names").hide();
            	
            $( "#datepicker" ).datepicker();
			$( "#datepicker" ).datepicker('setDate', new Date());
			$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			
			$(".statistics").hide();
    
			$(".update").on("click", function(event){
				
				event.preventDefault();

				var department = $(".department").val();
				var date = $(".date").val();
                
                /* Update text and date in H3*/
                departmentText = $(".department option[value='"+department+"']").text();
                $("#department_name").text(departmentText);
                var friendlyDate = $.datepicker.formatDate('dd.mm.yy', new Date(date));
                $("#date_range").text(friendlyDate);
                /**/
				
				$.ajax({
					url: "../system/visits/TodayVisitsByDepartment.ajax.php",
					type: "POST",
					data: { department: department, date: date},
					dataType: "json"
				}).always(function(){
					console.log("Sending: department: "+ department+" , date: "+ date);
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
                $(".result_prolongs").text(moneyFormat(results.prolongs));
				$(".result_paids").text(moneyFormat(results.paids));
                                $(".result_virtualProlongs").text(moneyFormat(results.virtualProlongs));
                $(".result_virtualReservations").text(moneyFormat(results.virtualReservations));
				
                $(".statistics").show();
                
				if(results.total == 0){
					$(".result_total").text("0");
                    $(".result_registrations").text("0");
                    $(".result_borrows").text("0");
                    $(".result_returns").text("0");
                    $(".result_reservations").text("0");
                    $(".result_paids").text("0");
                    $(".result_prolongs").text("0");
                    $(".result_virtualProlongs").text("0");
                    $(".result_virtualReservations").text("0");
				}
                
                /* Yearly revenue by department Chart */

                $('#yearlyRevenueByDepartmentChart').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '<?php echo $text->yearly_revenue; ?>'
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
                        '#8b3664'
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
               /* Yearly revenue by department Chart END */
                
                $('html, body').animate({
        scrollTop: $("#main_board").offset().top
    }, 900);
				
			};
            
            $(".department").on("change", function(){
                $("#registrations_names").slideUp();
                $("#borrowed_names").slideUp();
                $("#reservationed_names").slideUp();
                $("#returned_names").slideUp();
                $("#paid_names").slideUp();
                $("#prolonged_names").slideUp();
                $(".statistics").slideUp();
                $("#virtualReservationed_names").slideUp();
                $("#virtualProlonged_names").slideUp();
                
                $("#registered_body").find("tr").remove();
                
            });
			$(".date").on("change", function(){
                $("#registrations_names").slideUp();
                $("#borrowed_names").slideUp();
                $("#reservationed_names").slideUp();
                $("#returned_names").slideUp();
                $("#paid_names").slideUp();
                $("#prolonged_names").slideUp();
                $(".statistics").slideUp();
                $("#virtualReservationed_names").slideUp();
                $("#virtualProlonged_names").slideUp();
                
                $("#registered_body").find("tr").remove();
                
            });
            
            /* Registrations */
            $(".view_registrations_detail").on("click", function(){
               
               console.log("Clicking on Registrations More");
               var department = $(".department").val();
			   var date = $(".date").val();
               
               $.ajax({
					url: "../system/visits/registrations.ajax.php",
					type: "POST",
					data: { department: department, date: date},
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
              
                var day = results.day;
                var rows = results.rows;
                var number_of_registered_users = results.number_of_users;
                
                $("#number_of_registered_users").text(number_of_registered_users);
              
                if (number_of_registered_users <= 10) {
                    $.each(rows, function(i, item) {
                        $('.registered').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    });
                }
                else {
                    // print 10
                    for(var i = 0; i < 10; i++) {
                        $('.registered').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
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
			   var date = $(".date").val(); 
                
               window.open('registeredToPDF.php?department='+department+'&date='+date+"&departmentText="+departmentText);
               
            });
            
             /* Registrations END */
             
            /* Borrows */
            $(".view_borrows_detail").on("click", function(){
               
               console.log("Clicking on Borrows More");
               var department = $(".department").val();
			   var date = $(".date").val();
               
               $.ajax({
					url: "../system/visits/borrows.ajax.php",
					type: "POST",
					data: { department: department, date: date},
					dataType: "json"
				}).always(function(){
					$("#borrowed_names").slideDown();
                    console.log("Getting borrows...");
				}).done(function(results){
					addBorrows(results);
                    console.log("Paraada Tome");
				}).fail(function(jqXHR, textStatus){
					console.log( "Request failed: " + textStatus );
				});
                
            });
            
            var addBorrows = function(results){
                
                borrowedResults = results;
              
                $("#borrowed_body").find("tr").remove();
                console.log(results);
              
                var day = results.day;
                var rows = results.rows;
                var number_of_borrowed_users = results.number_of_users;
                
                $("#number_of_borrowed_users").text(number_of_borrowed_users);
              
                if (number_of_borrowed_users <= 10) {
                    $.each(rows, function(i, item) {
                        $('.borrowed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].count+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?readingrec="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    });
                }
                else {
                    // print 10
                    for(var i = 0; i < 10; i++) {
                        $('.borrowed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].count+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?readingrec="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                    // print paginator                     
                    printPagination("borrowed_pagination", number_of_borrowed_users, "changeBorrowedPage", 1);
                     
                }
                
                $('html, body').animate({
        scrollTop: $("#borrowed_names").offset().top
    }, 900);
                
            };
            
            $("#pdf_borrowed").on("click", function(){
                
               console.log("Exporting borrowed to PDF");
               var department = $(".department").val();
			   var date = $(".date").val(); 
                
               window.open('borrowedToPDF.php?department='+department+'&date='+date+"&departmentText="+departmentText);
               
            });
            
             /* Borrows END */
             
             /* Reservations */
            $(".view_reservations_detail").on("click", function(){
               
               console.log("Clicking on Reservations More");
               var department = $(".department").val();
			   var date = $(".date").val();
               
               $.ajax({
					url: "../system/visits/reservations.ajax.php",
					type: "POST",
					data: { department: department, date: date},
					dataType: "json"
				}).always(function(){
					$("#reservationed_names").slideDown();
                    console.log("Getting reservations...");
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
              
                var day = results.day;
                var rows = results.rows;
                var number_of_reservationed_users = results.number_of_users;
                
                $("#number_of_reservationed_users").text(number_of_reservationed_users);
              
                if (number_of_reservationed_users <= 10) {
                    $.each(rows, function(i, item) {
                        $('.reservationed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    });
                }
                else {
                    // print 10
                    for(var i = 0; i < 10; i++) {
                        $('.reservationed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
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
			   var date = $(".date").val(); 
                
               window.open('reservationedToPDF.php?department='+department+'&date='+date+"&departmentText="+departmentText);
               
            });
            
             /* Reservations END */
             
             /* VirtualReservations */
            $(".view_virtualReservations_detail").on("click", function(){
               
               console.log("Clicking on virtualReservations More");
               var department = $(".department").val();
			   var date = $(".date").val();
               
               $.ajax({
					url: "../system/visits/virtualReservations.ajax.php",
					type: "POST",
					data: { department: department, date: date},
					dataType: "json"
				}).always(function(){
					$("#virtualReservationed_names").slideDown();
                    console.log("Getting virtualReservations...");
				}).done(function(results){
					addVirtualReservations(results);
                    console.log("Paraada Tome");
				}).fail(function(jqXHR, textStatus){
					console.log( "Request failed: " + textStatus );
				});
                
            });
            
            var addVirtualReservations = function(results){
                
                virtualReservationedResults = results;
              
                $("#virtualReservationed_body").find("tr").remove();
                console.log(results);
              
                var day = results.day;
                var rows = results.rows;
                var number_of_virtualReservationed_users = results.number_of_users;
                
                $("#number_of_virtualReservationed_users").text(number_of_virtualReservationed_users);
              
                if (number_of_virtualReservationed_users <= 10) {
                    $.each(rows, function(i, item) {
                        $('.virtualReservationed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    });
                }
                else {
                    // print 10
                    for(var i = 0; i < 10; i++) {
                        $('.virtualReservationed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                    // print paginator
                    printPagination("virtualReservationed_pagination", number_of_virtualReservationed_users, "changeVirtualReservationedPage", 1);
                     
                }
                
                $('html, body').animate({
        scrollTop: $("#virtualReservationed_names").offset().top
    }, 900);
                
            };
            
            $("#pdf_virtualReservationed").on("click", function(){
                
               console.log("Exporting virtualReservationed to PDF");
               var department = $(".department").val();
			   var date = $(".date").val(); 
                
               window.open('virtualReservationedToPDF.php?department='+department+'&date='+date+"&departmentText="+departmentText);
               
            });
            
             /* Virtual Reservations END */
             
             /* Returns */
            $(".view_returns_detail").on("click", function(){
               
               console.log("Clicking on Returns More");
               var department = $(".department").val();
			   var date = $(".date").val();
               
               $.ajax({
					url: "../system/visits/returns.ajax.php",
					type: "POST",
					data: { department: department, date: date},
					dataType: "json"
				}).always(function(){
					$("#returned_names").slideDown();
                    console.log("Getting returns...");
				}).done(function(results){
					addReturns(results);
                    console.log("Paraada Tome");
				}).fail(function(jqXHR, textStatus){
					console.log( "Request failed: " + textStatus );
				});
                
            });
            
            var addReturns = function(results){
                
                returnedResults = results;
              
                $("#returned_body").find("tr").remove();
                console.log(results);
              
                var day = results.day;
                var rows = results.rows;
                var number_of_returned_users = results.number_of_users;
                
                $("#number_of_returned_users").text(number_of_returned_users);
              
                if (number_of_returned_users <= 10) {
                    $.each(rows, function(i, item) {
                        $('.returned').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].count+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/readingrec.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    });
                }
                else {
                    // print 10
                    for(var i = 0; i < 10; i++) {
                        $('.returned').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].count+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/readingrec.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                    // print paginator                     
                    printPagination("returned_pagination", number_of_returned_users, "changeReturnedPage", 1);
                }
                
                $('html, body').animate({
        scrollTop: $("#returned_names").offset().top
    }, 900);
                
            };
            
            $("#pdf_returned").on("click", function(){
                
               console.log("Exporting returned to PDF");
               var department = $(".department").val();
			   var date = $(".date").val(); 
                
               window.open('returnedToPDF.php?department='+department+'&date='+date+"&departmentText="+departmentText);
               
            });
            
             /* returns END */
             
             /* Paids */
            $(".view_paids_detail").on("click", function(){
               
               console.log("Clicking on paids More");
               var department = $(".department").val();
			   var date = $(".date").val();
               
               $.ajax({
					url: "../system/visits/paids.ajax.php",
					type: "POST",
					data: { department: department, date: date},
					dataType: "json"
				}).always(function(){
					$("#paid_names").slideDown();
                    console.log("Getting paids...");
				}).done(function(results){
					addPaids(results);
                    console.log("Paraada Tome");
				}).fail(function(jqXHR, textStatus){
					console.log( "Request failed: " + textStatus );
				});
                
            });
            
            var addPaids = function(results){
                
                paidResults = results;
              
                $("#paid_body").find("tr").remove();
                console.log(results);
              
                var day = results.day;
                var rows = results.rows;
                var number_of_paid_users = results.number_of_users;
                
                $("#number_of_paid_users").text(number_of_paid_users);
              
                if (number_of_paid_users <= 10) {
                    $.each(rows, function(i, item) {
                        $('.paid').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].timestamp+"</td><td>"+rows[i].accounttype+"</td></tr>");
                    });
                }
                else {
                    // print 10
                    for(var i = 0; i < 10; i++) {
                        $('.paid').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].timestamp+"</td><td>"+rows[i].accounttype+"</td></tr>");
                    }
                    // print paginator
                    printPagination("paid_pagination", number_of_paid_users, "changePaidPage", 1);
                }
                
                $('html, body').animate({
        scrollTop: $("#paid_names").offset().top
    }, 900);
                
            };
            
            $("#pdf_paid").on("click", function(){
                
               console.log("Exporting paid to PDF");
               var department = $(".department").val();
			   var date = $(".date").val(); 
                
               window.open('paidToPDF.php?department='+department+'&date='+date+"&departmentText="+departmentText);
               
            });
            
             /* paids END */
             
             /* Prolongs */
            $(".view_prolongs_detail").on("click", function(){
               
               console.log("Clicking on prolongs More");
               var department = $(".department").val();
			   var date = $(".date").val();
               
               $.ajax({
					url: "../system/visits/prolongs.ajax.php",
					type: "POST",
					data: { department: department, date: date},
					dataType: "json"
				}).always(function(){
					$("#prolonged_names").slideDown();
                    console.log("Getting prolongs...");
				}).done(function(results){
					addProlongs(results);
                    console.log("Paraada Tome");
				}).fail(function(jqXHR, textStatus){
					console.log( "Request failed: " + textStatus );
				});
                
            });
            
            var addProlongs = function(results){
                
                prolongedResults = results;
              
                $("#prolonged_body").find("tr").remove();
                console.log(results);
              
                var day = results.day;
                var rows = results.rows;
                var number_of_prolonged_users = results.number_of_users;
                
                $("#number_of_prolonged_users").text(number_of_prolonged_users);
              
                if (number_of_prolonged_users <= 10) {
                    $.each(rows, function(i, item) {
                        $('.prolonged').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].count+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/readingrec.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td><td>"+rows[i].accounttype+"</td></tr>");
                    });
                }
                else {
                    // print 10
                    for(var i = 0; i < 10; i++) {
                        $('.prolonged').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].count+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/readingrec.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td><td>"+rows[i].accounttype+"</td></tr>");
                    }
                    // print paginator
                    printPagination("prolonged_pagination", number_of_prolonged_users, "changeProlongedPage", 1);
                }
                
               $('html, body').animate({
        scrollTop: $("#prolonged_names").offset().top
    }, 900);
                
            };
            
            $("#pdf_prolonged").on("click", function(){
                
               console.log("Exporting prolonged to PDF");
               var department = $(".department").val();
			   var date = $(".date").val(); 
                
               window.open('prolongedToPDF.php?department='+department+'&date='+date+"&departmentText="+departmentText);
               
            });
            
             /* prolongs END */
             
              /* Virtual Prolongs */
            $(".view_virtualProlongs_detail").on("click", function(){
               
               console.log("Clicking on virtualProlongs More");
               var department = $(".department").val();
			   var date = $(".date").val();
               
               $.ajax({
					url: "../system/visits/virtualProlongs.ajax.php",
					type: "POST",
					data: { department: department, date: date},
					dataType: "json"
				}).always(function(){
					$("#virtualProlonged_names").slideDown();
                    console.log("Getting virtualProlongs...");
				}).done(function(results){
					addVirtualProlongs(results);
                    console.log("Paraada Tome");
				}).fail(function(jqXHR, textStatus){
					console.log( "Request failed: " + textStatus );
				});
                
            });
            
            var addVirtualProlongs = function(results){
                
                virtualProlongedResults = results;
              
                $("#virtualProlonged_body").find("tr").remove();
                console.log(results);
              
                var day = results.day;
                var rows = results.rows;
                var number_of_virtualProlonged_users = results.number_of_users;
                
                $("#number_of_virtualProlonged_users").text(number_of_virtualProlonged_users);
              
                if (number_of_virtualProlonged_users <= 10) {
                    $.each(rows, function(i, item) {
                        $('.virtualProlonged').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].count+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/readingrec.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    });
                }
                else {
                    // print 10
                    for(var i = 0; i < 10; i++) {
                        $('.virtualProlonged').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].count+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/readingrec.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                    // print paginator
                    printPagination("virtualProlonged_pagination", number_of_virtualProlonged_users, "changeVirtualProlongedPage", 1);
                }
                
               $('html, body').animate({
        scrollTop: $("#virtualProlonged_names").offset().top
    }, 900);
                
            };
            
            $("#pdf_virtualPprolonged").on("click", function(){
                
               console.log("Exporting virtualProlonged to PDF");
               var department = $(".department").val();
			   var date = $(".date").val(); 
                
               window.open('virtualProlongedToPDF.php?department='+department+'&date='+date+"&departmentText="+departmentText);
               
            });
            
             /* Virtual prolongs END */
             
             /* PDF Daily */
             $("#pdf_daily").on("click", function(){
                
               console.log("Exporting daily PDF");
               var department = $(".department").val();
			   var date = $(".date").val(); 
                
               window.open('dailyPDF.php?department='+department+'&date='+date+"&departmentText="+departmentText+"&total="+total+"&registrations="+registrations+"&borrows="+borrows+"&reservations="+reservations+"&returns="+returns+"&paids="+paids+"&prolongs="+prolongs);
               
            });
             /* PDF Daily END*/
            
            /* Close details */
            
            $("#remove_registered").on("click", function(){
                $("#registrations_names").slideUp();
            });
            $("#remove_borrowed").on("click", function(){
                $("#borrowed_names").slideUp();
            });
            $("#remove_reservationed").on("click", function(){
                $("#reservationed_names").slideUp();
            });
            $("#remove_returned").on("click", function(){
                $("#returned_names").slideUp();
            });
            $("#remove_paid").on("click", function(){
                $("#paid_names").slideUp();
            });
            $("#remove_prolonged").on("click", function(){
                $("#prolonged_names").slideUp();
            });
            $("#remove_virtualProlonged").on("click", function(){
                $("#virtualProlonged_names").slideUp();
            });
            $("#remove_virtualReservationed").on("click", function(){
                $("#virtualReservationed_names").slideUp();
            });
                
            /* Close details END */
            
            

		}); // //document ready END
        
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
                        $('.registered').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                else {
                    var toRecord = fromRecord + 10;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.registered').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                
                printPagination("registered_pagination", registeredResults.number_of_users, "changeRegisteredPage", goToPage);

            };
            
            var changeBorrowedPage = function(goToPage){
               console.log("Clicking on Go To Page: "+goToPage);
               $(".borrowed_pagination li").removeClass("active");
               $(".borrowed_pagination li a#"+goToPage).addClass("active");
               $("#borrowed_body").find("tr").remove();
               var rows = borrowedResults.rows;
               var fromRecord = (goToPage*10)-10;
               
                var lastPage = Math.floor(borrowedResults.number_of_users / 10);
                var rest = borrowedResults.number_of_users % 10;
                if (rest > 0){
                    lastPage = lastPage + 1;
                }
                
                if (goToPage == lastPage) {
                    var toRecord = fromRecord + rest;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.borrowed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].count+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/readingrec.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                else {
                    var toRecord = fromRecord + 10;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.borrowed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].count+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/readingrec.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                
                printPagination("borrowed_pagination", borrowedResults.number_of_users, "changeBorrowedPage", goToPage);

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
                        $('.reservationed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                else {
                    var toRecord = fromRecord + 10;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.reservationed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].timestamp+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                
                printPagination("reservationed_pagination", reservationedResults.number_of_users, "changeReservationedPage", goToPage);

            };
            
            var changeVirtualReservationedPage = function(goToPage){
               console.log("Clicking on Go To Page: "+goToPage);
               $(".virutalReservationed_pagination li").removeClass("active");
               $(".virutalReservationed_pagination li a#"+goToPage).addClass("active");
               $("#virutalReservationed_body").find("tr").remove();
               var rows = virutalReservationedResults.rows;
               var fromRecord = (goToPage*10)-10;
               
                var lastPage = Math.floor(virutalReservationedResults.number_of_users / 10);
                var rest = virutalReservationedResults.number_of_users % 10;
                if (rest > 0){
                    lastPage = lastPage + 1;
                }
                
                if (goToPage == lastPage) {
                    var toRecord = fromRecord + rest;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.virutalReservationed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].count)+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                else {
                    var toRecord = fromRecord + 10;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.virutalReservationed').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].count)+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                
                printPagination("virutalReservationed_pagination", virutalReservationedResults.number_of_users, "changeVirutalReservationedPage", goToPage);

            };
            
            var changeReturnedPage = function(goToPage){
               console.log("Clicking on Go To Page: "+goToPage);
               $(".returned_pagination li").removeClass("active");
               $(".returned_pagination li a#"+goToPage).addClass("active");
               $("#returned_body").find("tr").remove();
               var rows = returnedResults.rows;
               var fromRecord = (goToPage*10)-10;
               
                var lastPage = Math.floor(returnedResults.number_of_users / 10);
                var rest = returnedResults.number_of_users % 10;
                if (rest > 0){
                    lastPage = lastPage + 1;
                }
                
                if (goToPage == lastPage) {
                    var toRecord = fromRecord + rest;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.returned').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].count+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/readingrec.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                else {
                    var toRecord = fromRecord + 10;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.returned').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].count+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/readingrec.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                
                printPagination("returned_pagination", returnedResults.number_of_users, "changeReturnedPage", goToPage);

            };
            
            var changePaidPage = function(goToPage){
               console.log("Clicking on Go To Page: "+goToPage);
               $(".paid_pagination li").removeClass("active");
               $(".paid_pagination li a#"+goToPage).addClass("active");
               $("#paid_body").find("tr").remove();
               var rows = paidResults.rows;
               var fromRecord = (goToPage*10)-10;
               
                var lastPage = Math.floor(paidResults.number_of_users / 10);
                var rest = paidResults.number_of_users % 10;
                if (rest > 0){
                    lastPage = lastPage + 1;
                }
                
                if (goToPage == lastPage) {
                    var toRecord = fromRecord + rest;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.paid').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].timestamp+"</td><td>"+rows[i].accounttype+"</td></tr>");
                    }
                }
                else {
                    var toRecord = fromRecord + 10;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.paid').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].timestamp+"</td><td>"+rows[i].accounttype+"</td></tr>");
                    }
                }
                
                printPagination("paid_pagination", paidResults.number_of_users, "changePaidPage", goToPage);

            };
            
            var changeProlongedPage = function(goToPage){
               console.log("Clicking on Go To Page: "+goToPage);
               $(".prolonged_pagination li").removeClass("active");
               $(".prolonged_pagination li a#"+goToPage).addClass("active");
               $("#prolonged_body").find("tr").remove();
               var rows = prolongedResults.rows;
               var fromRecord = (goToPage*10)-10;
               
                var lastPage = Math.floor(prolongedResults.number_of_users / 10);
                var rest = prolongedResults.number_of_users % 10;
                if (rest > 0){
                    lastPage = lastPage + 1;
                }
                
                if (goToPage == lastPage) {
                    var toRecord = fromRecord + rest;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.prolonged').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].count+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/readingrec.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td><td>"+rows[i].accounttype+"</td></tr>");
                    }
                }
                else {
                    var toRecord = fromRecord + 10;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.prolonged').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+rows[i].count+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/readingrec.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td><td>"+rows[i].accounttype+"</td></tr>");
                    }
                }
                
                printPagination("prolonged_pagination", prolongedResults.number_of_users, "changeProlongedPage", goToPage);

            };
            
            var changeVirtualProlongedPage = function(goToPage){
               console.log("Clicking on Go To Page: "+goToPage);
               $(".virtualProlonged_pagination li").removeClass("active");
               $(".virtualProlonged_pagination li a#"+goToPage).addClass("active");
               $("#virtualProlonged_body").find("tr").remove();
               var rows = virtualProlongedResults.rows;
               var fromRecord = (goToPage*10)-10;
               
                var lastPage = Math.floor(virtualProlongedResults.number_of_users / 10);
                var rest = virtualProlongedResults.number_of_users % 10;
                if (rest > 0){
                    lastPage = lastPage + 1;
                }
                
                if (goToPage == lastPage) {
                    var toRecord = fromRecord + rest;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.virtualProlonged').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].count)+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                else {
                    var toRecord = fromRecord + 10;
                    for(var i = fromRecord; i < toRecord; i++) {
                        $('.virtualProlonged').find('tbody:last').append("<tr><td>"+rows[i].firstname+"</td><td>"+rows[i].surname+"</td><td>"+moneyFormat(rows[i].count)+"</td><td>"+rows[i].datetime+"</td><td><a href='../../cgi-bin/koha/members/boraccount.pl?borrowernumber="+rows[i].borrowernumber+"' target='_blank'><i class='fa fa-external-link'></i></a></td></tr>");
                    }
                }
                
                printPagination("virtualProlonged_pagination", virtualProlongedResults.number_of_users, "changeVirtualProlongedPage", goToPage);

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
