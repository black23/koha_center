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
            .st_report_part_1{
                background-color: #ffe599;
            }
            .st_report_part_1:nth-of-type(even){
                background-color: #fff2cc;
            }
            
            .st_report_part_2{
                background-color: #a4c2f4;
            }
            .st_report_part_2:nth-of-type(even){
                background-color: #d2e1fa;
            }
            
            .st_report_part_3{
                background-color: #b4a7d6;
            }
            .st_report_part_3:nth-of-type(even){
                background-color: #d2cae6;
            }
            
            .st_report_part_4{
                background-color: #e06666;
            }
            .st_report_part_4:nth-of-type(even){
                background-color: #e99090;
            }
            
            .st_report_part_7{
                background-color: #76a5af;
            }
            .st_report_part_7:nth-of-type(even){
                background-color: #96bac2;
            }
            
            .st_report_part_8{
                background-color: #ffffff;
            }
            .st_report_part_8:nth-of-type(even){
                background-color: #dddddd;
            }
            
            .st_report_head{
                background-color: #b7b7b7;
            }
            tbody tr td:nth-child(2){
                font-weight: bold;
            }
            .copy{
                cursor: pointer;
                margin-right: 4px;
            }
            #ui-datepicker-div{
                z-index: 1509!important;
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
                                    <button class="btn btn-primary rightButton generateReport"><?= $report_text->generate_report ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
               </div>
            </div>
            <!-- /.row -->
        
        <div class="row">
            <div class="col-md-12">
                <table class='table reportTable'>
                    <thead>
                        <tr class='st_report_head'>
                            <th><?= $report_text->part ?></th>
                            <th><?= $report_text->item ?></th>
                            <th><?= $text->description ?></th>
                            <th><?= $report_text->value ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class='st_report_part_1'>
                            <td><?= $report_text->st_report_part_1 ?></td>
                            <td>0101</td>
                            <td><?= $report_text->st_report_item_0101 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0101"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0102</td>
                            <td><?= $report_text->st_report_item_0102 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0102"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0103</td>
                            <td><?= $report_text->st_report_item_0103 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0103"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0104</td>
                            <td><?= $report_text->st_report_item_0104 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0104"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0105</td>
                            <td><?= $report_text->st_report_item_0105 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0105"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0106</td>
                            <td><?= $report_text->st_report_item_0106 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0106"></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0107</td>
                            <td><?= $report_text->st_report_item_0107 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0107"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0108</td>
                            <td><?= $report_text->st_report_item_0108 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0108"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0109</td>
                            <td><?= $report_text->st_report_item_0109 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0109"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0110</td>
                            <td><?= $report_text->st_report_item_0110 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0110"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0111</td>
                            <td><?= $report_text->st_report_item_0111 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0111"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0112</td>
                            <td><?= $report_text->st_report_item_0112 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0112"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0113</td>
                            <td><?= $report_text->st_report_item_0113 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0113"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0114</td>
                            <td><?= $report_text->st_report_item_0114 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0114"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0115</td>
                            <td><?= $report_text->st_report_item_0115 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0115"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0116</td>
                            <td><?= $report_text->st_report_item_0116 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0116"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0118</td>
                            <td><?= $report_text->st_report_item_0118 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0118"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0119</td>
                            <td><?= $report_text->st_report_item_0119 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0119"></span></td>
                        </tr>
                        <tr class='st_report_part_1'>
                            <td></td>
                            <td>0139</td>
                            <td><?= $report_text->st_report_item_0139 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0139"></span></td>
                        </tr>
                        
                        <tr class='st_report_part_2'>
                            <td><?= $report_text->st_report_part_2 ?></td>
                            <td>0201</td>
                            <td><?= $report_text->st_report_item_0201 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0201"></span></td>
                        </tr>
                        <tr class='st_report_part_2'>
                            <td></td>
                            <td>0202</td>
                            <td><?= $report_text->st_report_item_0202 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0202"></span></td>
                        </tr>
                        <tr class='st_report_part_2'>
                            <td></td>
                            <td>0205</td>
                            <td><?= $report_text->st_report_item_0205 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0205"></span></td>
                        </tr>
                        
                        <tr class='st_report_part_3'>
                            <td><?= $report_text->st_report_part_3 ?></td>
                            <td>0301</td>
                            <td><?= $report_text->st_report_item_0301 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0301"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0302</td>
                            <td><?= $report_text->st_report_item_0302 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0302"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0303</td>
                            <td><?= $report_text->st_report_item_0303 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0303"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0304</td>
                            <td><?= $report_text->st_report_item_0304 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0304"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0305</td>
                            <td><?= $report_text->st_report_item_0305 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0305"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0306</td>
                            <td><?= $report_text->st_report_item_0306 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0306"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0307</td>
                            <td><?= $report_text->st_report_item_0307 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0307"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0308</td>
                            <td><?= $report_text->st_report_item_0308 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0308"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0309</td>
                            <td><?= $report_text->st_report_item_0309 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0309"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0310</td>
                            <td><?= $report_text->st_report_item_0310 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0310"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0311</td>
                            <td><?= $report_text->st_report_item_0311 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0311"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0312</td>
                            <td><?= $report_text->st_report_item_0312 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0312"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0313</td>
                            <td><?= $report_text->st_report_item_0313 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0313"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0314</td>
                            <td><?= $report_text->st_report_item_0314 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0314"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0315</td>
                            <td><?= $report_text->st_report_item_0315 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0315"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0316</td>
                            <td><?= $report_text->st_report_item_0316 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0316"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0317</td>
                            <td><?= $report_text->st_report_item_0317 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0317"></span></td>
                        </tr>
                        <tr class='st_report_part_3'>
                            <td></td>
                            <td>0339</td>
                            <td><?= $report_text->st_report_item_0339 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0339"></span></td>
                        </tr>
                        
                        <tr class='st_report_part_4'>
                            <td><?= $report_text->st_report_part_4 ?></td>
                            <td>0402</td>
                            <td><?= $report_text->st_report_item_0402 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0402"></span></td>
                        </tr>
                        <tr class='st_report_part_4'>
                            <td></td>
                            <td>0403</td>
                            <td><?= $report_text->st_report_item_0403 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0403"></span></td>
                        </tr>
                        <tr class='st_report_part_4'>
                            <td></td>
                            <td>0404</td>
                            <td><?= $report_text->st_report_item_0404 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0404"></span></td>
                        </tr>
                        <tr class='st_report_part_4'>
                            <td></td>
                            <td>0405</td>
                            <td><?= $report_text->st_report_item_0405 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0405"></span></td>
                        </tr>
                        <tr class='st_report_part_4'>
                            <td></td>
                            <td>0406</td>
                            <td><?= $report_text->st_report_item_0406 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0406"></span></td>
                        </tr>
                        <tr class='st_report_part_4'>
                            <td></td>
                            <td>0407</td>
                            <td><?= $report_text->st_report_item_0407 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0407"></span></td>
                        </tr>
                        <tr class='st_report_part_4'>
                            <td></td>
                            <td>0408</td>
                            <td><?= $report_text->st_report_item_0408 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0408"></span></td>
                        </tr>
                        <tr class='st_report_part_4'>
                            <td></td>
                            <td>0409</td>
                            <td><?= $report_text->st_report_item_0409 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0409"></span></td>
                        </tr>
                        <tr class='st_report_part_4'>
                            <td></td>
                            <td>0410</td>
                            <td><?= $report_text->st_report_item_0410 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0410"></span></td>
                        </tr>
                        <tr class='st_report_part_4'>
                            <td></td>
                            <td>0411</td>
                            <td><?= $report_text->st_report_item_0411 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0411"></span></td>
                        </tr>
                        <tr class='st_report_part_4'>
                            <td></td>
                            <td>0412</td>
                            <td><?= $report_text->st_report_item_0412 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0412"></span></td>
                        </tr>
                        
                        <tr class='st_report_part_7'>
                            <td><?= $report_text->st_report_part_7 ?></td>
                            <td>0701</td>
                            <td><?= $report_text->st_report_item_0701 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0701"></span></td>
                        </tr>
                        <tr class='st_report_part_7'>
                            <td></td>
                            <td>0702</td>
                            <td><?= $report_text->st_report_item_0702 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0702"></span></td>
                        </tr>
                        <tr class='st_report_part_8'>
                            <td><?= $report_text->st_report_part_8 ?></td>
                            <td>0808</td>
                            <td><?= $report_text->st_report_item_0808 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0808"></span></td>
                        </tr>
                        <tr class='st_report_part_8'>
                            <td></td>
                            <td>0809</td>
                            <td><?= $report_text->st_report_item_0809 ?></td>
                            <td><i class='fa fa-clipboard copy'></i><span id="0809"></span></td>
                        </tr>
                    </tbody>
                </table>
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
    <script src="../js/bootstrap-tooltip.js"></script>
    
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    
    <div class="tooltip top ctrlc" role="tooltip">
        <div class="tooltip-arrow"></div>
        <div class="tooltip-inner">
            CTRL+C
        </div>
    </div>
    
    <?php
        if (isset($langShortcut) AND ($langShortcut == "cs")) {
            echo "<script src='../js/datepicker-cs.js'></script>";
        }
    ?>
    
    <script>
        $( document ).ready(function() {
            
            $(".reportTable").hide();
            
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
            
            
            
            $(".copy").on("click", function(event){
                
                event.preventDefault;
                var textElement = $(this).next();
                $(textElement).selectText();
                
                $(".ctrlc").tooltip();
            });
            
            $(".generateReport").on("click", function(event){
                
                event.preventDefault();
                
                var from = $("#from").val();
                var to = $("#to").val();
                
                $.ajax({
                        url: "generateReport.ajax.php",
                        type: "POST",
                        data: { from: from, to: to },
                        dataType: "json"
                }).always(function(){
                        console.log("Generating report from: "+ from+", to: "+to);
                        $("#loading-indicator").show();
                }).done(function(results){
                    
                    $("#loading-indicator").hide();
                    
                    $.each(results, function(i, item){
                        $("span#"+i).text(moneyFormat(item));
                    });

                    $(".reportTable").slideDown();

                    $('html, body').animate({
                        scrollTop: $(".reportTable").offset().top - 50
                    }, 900);
                        
                }).fail(function(jqXHR, textStatus){
                        console.log( "Request failed: " + textStatus );
                        $("#loading-indicator").hide();
                });
                
            });
                
        });
        
        jQuery.fn.selectText = function(){
            var doc = document
                , element = this[0]
                , range, selection
            ;
            if (doc.body.createTextRange) {
                range = document.body.createTextRange();
                range.moveToElementText(element);
                range.select();
            } else if (window.getSelection) {
                selection = window.getSelection();        
                range = document.createRange();
                range.selectNodeContents(element);
                selection.removeAllRanges();
                selection.addRange(range);
            }
        };
        
        /**
         *  Money Format
         *  
         *  @param {int} amount Count
         */
        var moneyFormat = function(amount){
            return amount.toString().replace(/.(?=(?:.{3})+$)/g, '$& ');
        };
        
    </script>

    <?php 
        require_once "../tpl/footer.php"; 
    ?>

