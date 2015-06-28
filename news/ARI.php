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
        
        Debugger::enable(Debugger::DEVELOPMENT, __DIR__ . '/../log');
        Debugger::$strictMode = TRUE;
	
?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <?php 
            $title = $text->KOHA." | ARI";
            require_once "../tpl/head.php"; 
            
            $defaultFrom = date("Y-m-")."01";

            $lastDayInActualMonth = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y")); 
            $defaultTo = date("Y-m-").$lastDayInActualMonth;
        ?>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="../css/token-input.css" />
        <style>
            .multiselect-container{position:absolute;list-style-type:none;margin:0;padding:0}
            .multiselect-container .input-group{margin:5px}.multiselect-container>li{padding:0}
            .multiselect-container>li>a.multiselect-all label{font-weight:700}
            .multiselect-container>li.multiselect-group label{margin:0;padding:3px 20px 3px 20px;height:100%;font-weight:700}
            .multiselect-container>li.multiselect-group-clickable label{cursor:pointer}.multiselect-container>li>a{padding:0}
            .multiselect-container>li>a>label{margin:0;height:100%;cursor:pointer;font-weight:400;padding:3px 20px 3px 40px}
            .multiselect-container>li>a>label.radio,.multiselect-container>li>a>label.checkbox{margin:0}
            .multiselect-container>li>a>label>input[type=checkbox]{margin-bottom:5px}
            .btn-group>.btn-group:nth-child(2)>.multiselect.btn{border-top-left-radius:4px;border-bottom-left-radius:4px}
            .form-inline .multiselect-container label.checkbox,.form-inline .multiselect-container label.radio{padding:3px 20px 3px 40px}
            .form-inline .multiselect-container li a label.checkbox input[type=checkbox],.form-inline .multiselect-container li a label.radio input[type=radio]{margin-left:-20px;margin-right:0}
            
            .add-term-input-link{
                cursor: pointer;
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
                                <li class="active">ARI</li>
			</ol>
		</div>

        <div class="row">

            <div class="row">
               <div class="col-md-12" id="sendForm">
                    <div class="panel panel-default">
                        <div class="panel-body ">
                            <form>
                                <div class='row'>
                                    <div class='col-lg-4'>
                                          <div class="form-group">
                                            <label class="control-label">Term</label>
                                            <div class='input-group'>
                                                <textarea id="term" class="form-control term"></textarea>
                                            </div>
                                            <!--<div class='input-group boolean-selection'>
                                                <select id="boolean-selection">
                                                    <option value='AND'>AND</option>
                                                    <option value='AND'>OR</option>
                                                    <option value='AND'>NOT</option>
                                                </select>
                                            </div>-->
                                          </div>
                                          <!--<div class="form-group" id='add-term-input'>
                                              <span class='add-term-input-link'>Přidat další</span>
                                          </div>-->
                                    </div>
                                    <div class='col-lg-2'>
                                          <div class="form-group" id="timeDiv">
                                            <label for="time" class="control-label"><?= $text->from_date ?></label>
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input id="from" type="text" class="form-control">
                                                </div>
                                          </div>
                                          <div class="form-group" id="timeDiv">
                                            <label for="time" class="control-label"><?= $text->to_date ?></label>
                                            <div class='input-group date' id='datetimepicker2'>
                                                <input id="to" type="text" class="form-control">
                                            </div>
                                          </div>
                                    </div>

                                    <div class='col-lg-3'>
                                         <div class="form-group">
                                            <label for="time" class="control-label"><?= $text->departments ?></label>
                                            <div class='input-group date'>
                                                <select class="branches" multiple="multiple">
                                                    <?php
                                                        require_once "../system/finances/DatabaseHandler.class.php";
                                                        $databaseHandler = new DatabaseHandler($db);
                                                        $branches = $databaseHandler->getBranches();
                                                        foreach ($branches as $arr) {
                                                            echo "<option value=\"".$arr['branchcode']."\">".$arr['branchname']."</option>\n";
							}
                                                    ?>
                                                </select>
                                            </div>
                                          </div>
                                        
                                        <div class="form-group">
                                            <label for="time" class="control-label"><?= $text->types ?></label>
                                            <div>
                                                <select class="types" multiple="multiple">
                                                    <?php
                                                    foreach($doc_types AS $key => $val){
                                                        echo "<option value='".$val."'>".$text->$key."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="time" class="control-label">Prohledávat</label>
                                            <div>
                                                <select class="marc-arrays" multiple="multiple">
                                                    <?php
                                                    $marcArrays = array(600,610, 611, 630, 648, 650, 651,655);
                                                    foreach($marcArrays AS $val){
                                                        if ($val === 650) {
                                                            echo "<option value='".$val."' selected>&nbsp;".$val."&nbsp;</option>";
                                                        } else {
                                                            echo "<option value='".$val."'>&nbsp;".$val."&nbsp;</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class='col-lg-3'>
                                        
                                         <div class="form-group" id="timeDiv">
                                            <label for="time" class="control-label">ARI číslo</label>
                                            <div class='input-group date' id='datetimepicker2'>
                                                <input id="ariNumber" type="text" class="form-control" value="/">
                                            </div>
                                          </div>
                                        
                                          <div class="form-group" id="timeDiv">
                                            <label for="time" class="control-label">Žadatel</label>
                                            <div class='input-group date'>
                                                <input id="zadatel" type="text" class="form-control">
                                                </div>
                                          </div>
                                          <div class="form-group" id="timeDiv">
                                            <label for="time" class="control-label">Zpracovatel</label>
                                            <div class='input-group date'>
                                                <input id="zpracovatel" type="text" class="form-control">
                                            </div>
                                          </div>
                                    </div>
                                    
                                </div>
                              
                                <div>
                                    <button class="btn btn-primary rightButton generatePDF">Generovat DOC</button>
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
    <script type="text/javascript" src="../js/jquery.tokeninput.js"></script>
    <script>
        $( document ).ready(function() {
            
            $(".term").tokenInput("getTerm.php");
            
            $('.branches, .types, .marc-arrays').multiselect({
                numberDisplayed: 1
            });

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
            
            $(".generatePDF").on("click", function(event){
                
                event.preventDefault();
                
                var from = $("#from").val();
                var to = $("#to").val();
                
                var ariNumber = $("#ariNumber").val();
                var zadatel = $("#zadatel").val();
                var zpracovatel = $("#zpracovatel").val();
                
                var branches = "";
                $(".branches option:checked").each(function(){
                    branches += ","+"'"+$(this).val()+"'";
                });
                branches = branches.substring(1);
                
                var types = "";
                $(".types option:checked").each(function(){
                    types += ","+"'"+$(this).val()+"'";
                });
                types = types.substring(1);
                
                var marcArrays = "";
                $(".marc-arrays option:checked").each(function(){
                    branches += ","+"'"+$(this).val()+"'";
                });
                marcArrays = marcArrays.substring(1);
                
                if( (branches.length < 1) || (types.length < 1) ){
                    alert("Enter branches and types");
                }
                else{
                    var terms = $("#term").val();
                    console.log("Sending from: "+from+", to: "+to+", branches="+branches+"types: "+types+"&ariNumber="+ariNumber+"&zadatel="+zadatel+"&zpracovatel="+zpracovatel+"&marcArrays="+marcArrays+"&terms="+terms);
                    window.open('generateARIOutput.ajax.php?from='+from+'&to='+to+"&branches="+branches+"&types="+types+"&ariNumber="+ariNumber+"&zadatel="+zadatel+"&zpracovatel="+zpracovatel+"&marcArrays="+marcArrays+"&terms="+terms);
                }
                    
            });
                
        });
        
    </script>

	<?php 
		require_once "../tpl/footer.php"; 
    ?>

