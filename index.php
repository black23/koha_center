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
	
	require_once "system/config.php";
	
?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <?php 
            $title = $title = $text->KOHA." ".$title = $text->statistical_center;;
            require_once "tpl/head.php"; 
        ?>

    </head>

    <body>

    <?php 
		require_once "tpl/nav.php"; 
    ?>

    <!-- Page Content -->
    <div class="container">
		<div class="row">
			<ol class="breadcrumb">
				<li class="active"><a href="index.php"><?php echo $text->home; ?></a></li>
			</ol>
		</div>

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <?php echo $text->quick_pick; ?> 
                </h1>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-lg fa-calculator"></i> <?php echo $text->finances; ?></h4>
                    </div>
                    <div class="panel-body">
                        <p>
                            <a href="finances/daily-revenue-by-department.php" title="<?php echo $text->daily_revenue_by_department; ?>"><?php echo $text->daily_revenue_by_department; ?></a><br>
                            <a href="finances/monthly-revenue-by-department.php" title="<?php echo $text->monthly_revenue; ?>"><?php echo $text->monthly_revenue; ?></a><br>
                            <a href="finances/yearly-revenue-by-department.php" title="<?php echo $text->yearly_revenue; ?>"><?php echo $text->yearly_revenue; ?></a><br>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-lg fa-mobile"></i> <?php echo $text->correspondence; ?></h4>
                    </div>
                    <div class="panel-body">
                        <p>
                            <a href="correspondence/sms.php" title="<?php echo $text->sms; ?>"><?php echo $text->sms; ?></a><br>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-lg fa-book"></i> <?php echo $text->store; ?></h4>
                    </div>
                    <div class="panel-body">
                        <p>
                            <a href="visits/daily-visits.php" title="<?php echo $text->daily_visits; ?>"><?php echo $text->daily_visits; ?></a><br>
                            <a href="visits/monthly-visits-by-department.php" title="<?php echo $text->monthly_visits; ?>"><?php echo $text->monthly_visits; ?></a><br>
                            <a href="visits/yearly-visits-by-department.php" title="<?php echo $text->yearly_visits; ?>"><?php echo $text->yearly_visits; ?></a><br>
                            <a href="visits/add_daily_visits.php" title="<?php echo $text->add_daily_visits; ?>"><?php echo $text->add_daily_visits; ?></a><br>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
         
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-lg fa-users"></i> <?php echo $text->members; ?></h4>
                    </div>
                    <div class="panel-body">
                        <p>
                            
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-lg fa-refresh"></i> <?php echo $text->circulations; ?></h4>
                    </div>
                    <div class="panel-body">
                        <p>
                            <a href="news/news.php" title="<?php echo $text->news_in_pdf; ?>"><?php echo $text->news_in_pdf; ?></a><br>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-lg fa-bar-chart"></i> <?php echo $text->statistical_reports; ?></h4>
                    </div>
                    <div class="panel-body">
                        <p>
                            <a href="reports/statistical-report.php" title="<?php echo $text->yearly_report; ?>"><?php echo $text->yearly_report; ?></a><br>
                            <a href="reports/daily-report.php" title="<?php echo $text->daily_report; ?>"><?php echo $text->daily_report; ?></a><br>
                        </p>
                    </div>
                </div>
            </div>
        </div> 
        <!-- /.row -->

        <hr>

        <!-- Footer -->
       <?php require_once 'tpl/footer.tpl.php'; ?>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

	<?php 
		require_once "tpl/footer.php"; 
    ?>

