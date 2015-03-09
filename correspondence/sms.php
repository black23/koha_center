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
            $title = $title = $text->KOHA." ".$title = $text->statistical_center;
            require_once "../tpl/head.php"; 
            
            require_once "../system/correspondence/sms.class.php"; 
            $Sms = new Sms($db, $SMS_username, $SMS_password);
            $credit = $Sms->getCredit();
            
            $Sms->downloadInbox2DB(); 
            
            //$inbox = $Sms->getInboxAsXML();
            $borrowers = $Sms->getBorrowersAsAssociativeArray();
            $borrowersAsString = $Sms->borrowersAsString();
            $staffAsString = $Sms->staffAsString();

            $incoming = $Sms->getMessages("incoming", 10);
            $outgoing = $Sms->getMessages("outgoing", 10);
            
            /*
            echo "<pre>";
            print_r($borrowers);
            echo "</pre>";
            */
            
        ?>
        
        <link href="../css/jquery.atwho.css" rel="stylesheet">
         <link href="../css/bootstrap-switch.css" rel="stylesheet">
        <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/v4.0.0/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

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
                                <li class="active"><?php echo $text->sms; ?></li>
			</ol>
		</div>

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <?php echo $text->sms; ?> <button class="btn btn-lg btn-primary rightButton sendMessage" type="button"  data-toggle="modal" data-target="#compose"><i class="fa fa-lg fa-edit"></i> <?php echo $text->compose_new_message; ?> </button>
                </h1>
                <!-- Button trigger modal -->
                  
            </div>
            
            
            
            <div class="row">
               <div class="col-md-12" id="sendForm">
                    <div class="panel panel-default">
                        <div class="panel-heading panel-warning" style="background-color: #ffec8b!important">
                            <h4><?php echo $text->new_message; ?> </h4>
                            <span id="sendFormClose" class="closeButton"><i class="fa fa-times fa-2x"></i></span>
                        </div>
                        <div class="panel-body ">
                        
                            <form>
                              <div class="form-group">
                                <label for="receiver" class="control-label"><?= $text->receiver ?></label>
                                <textarea class="form-control receivers" id="receivers"></textarea>
                              </div>
                              
                              <div class="form-group">
                                <label for="message" class="control-label"><?= $text->message ?></label>
                                <div class='row'>
                                <div class='col-md-6'>
                                            <button style='margin-bottom: 10px;' class="btn btn-default prefillMenuButton" type="button" >
                                                <?= $text->prefill ?>
                                            </button>                                            
                                    </div>
                              </div>
                                <textarea class="form-control" id="message"></textarea>
                              </div>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        Napsáno znaků: <span id="charNum">0</span> | Zbývá znaků: <span id="charLeft">459</span> | Počet SMS: <span id="noSMS">1</span>/3
                                    </div>  
                                </div>
                                <div class='row'>
                                    <div class='col-md-6'>
                                          <div class="form-group" id="timeDiv">
                                            <label for="time" class="control-label"><?= $text->send_time ?></label>
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input id="time" type="text" class="form-control">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i>
                                            </div>
                                          </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="deliveryReport" class="control-label"><?= $text->delivery_report ?></label>
                                            <div>
                                                <input type="checkbox" value="1" class="form-control" id="deliveryReport">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              
                                <div>
                                    <button class="btn btn-primary rightButton sendMessageSubmit"><?= $text->send_message ?></button>
                                </div>
                                
                            </form>
                        </div>
                    </div>
               </div>
            </div>
            <div class="col-md-12">
                <div class="well"><b>Zbývající kredit</b>: <?php echo number_format($credit, 2, ',', ' ')." ".$text->currency;  ?>
                    
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-lg fa-mail-forward"></i> <?php echo $text->sent; ?> <span class="badge"><?php echo $outgoing['count']; ?></span></h4>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped outgoing">
                            <thead>
                                <tr class='outgoingFilterButton'><td><a href="#" onclick='showOutgoingFilter(event);'><?= $text->use_filter ?></a></td><td></td><td></td><td></td><td></td></tr>
                                <tr class='outgoingFilter'>
                                    <td colspan='3'><input type="text" class='form-control input-sm' id='outgoingReceiver' placeholder='<?= $text->receiver ?>'></td>
                                    
                                    <td>
                                        <select class='form-control input-sm' id='outgoingStatus'>
                                            <option value="" selected><?= $text->status ?></option>
                                            <option value='0'><?= $text->status_0 ?></option>
                                            <option value='1'><?= $text->status_1 ?></option>
                                            <option value='2'><?= $text->status_2 ?></option>
                                            <option value='3'><?= $text->status_3 ?></option>
                                            <option value='5'><?= $text->status_5 ?></option>
                                        </select>
                                    </td>
                                    <td><input type="text" class='form-control input-sm' id='outgoingStaff' placeholder='<?= $text->sender ?>'></td>
                                </tr>
                                <tr>
                                    <th><?= $text->receiver ?></th>
                                    <th><?= $text->sent_at ?></th>
                                    <th><?= $text->detail ?></th>
                                    <th><?= $text->status ?></th>
                                    <th><?= $text->sender ?></th>
                                </tr>
                            </thead>
                            <tbody id='outgoingBody'>
                                <?php foreach($outgoing['messages'] as $val){
                                    
                                    $phone = str_replace(" ", "", $val['phone']);
                                    $phone = str_replace("-", "", $phone);
                                    $phone = str_replace("(", "", $phone);
                                    $phone = str_replace(")", "", $phone);

                                    $phone = substr($phone, -9);
                                    $nameAndSurname = $borrowers[$phone]['firstname']." ".$borrowers[$phone]['surname'];
                                    
                                    $stuff = $Sms->getBorrowerByID($val['sender_id']);
                                    
                                    $datetime = new DateTime($val['time']);
                                    $time = $datetime->format("d.m. H:i");
                                    echo "<tr>"
                                            ."<td>".$nameAndSurname."</td>"
                                            ."<td>".$time."</td>"
                                            ."<td><a href='#'><i class='fa fa-link' onclick='getMessage(event, ".$val['sms_id'].");' id='".$val['sms_id']."'></i></a></td>";
                                    if($val['status'] == 0){
                                         echo "<td><span class='badge badgeInfo'>".$text->status_0."</span></td>";
                                    }
                                    elseif($val['status'] == 1){
                                         echo "<td><span class='badge badgeInfo'>".$text->status_1."</span></td>";
                                    }
                                    elseif($val['status'] == 2){
                                         echo "<td><span class='badge badgeInfo'>".$text->status_2."</span></td>";
                                    }
                                    elseif($val['status'] == 3){
                                         echo "<td><span class='badge badgeInfo notDelivered'>".$text->status_3."</span></td>";
                                    }
                                    elseif($val['status'] == 5){
                                         echo "<td><span class='badge badgeInfo expiredMessage'>".$text->status_5."</span></td>";
                                    }
                                    else{
                                        echo "<td><span class='badge badgeInfo'>".$val['status']."</span></td>";
                                    }
                                   
                                      echo  "<td>$stuff</td></tr>";
                                }
                                ?>
                                
                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="outgoing_pagination pagination">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading panel-warning" style="background-color: #ffec8b!important">
                        <h4><i class="fa fa-lg fa-mail-reply"></i> <?php echo $text->received; ?> <span class="badge"><?= $incoming['count'] ?></span></h4>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped incoming">
                            <thead>
                                <tr class='incomingFilterButton'><td><a href="#" onclick='showIncomingFilter(event);'><?= $text->use_filter ?></a></td><td></td><td></td><td></td><td></td></tr>
                                <tr class='incomingFilter'>
                                    <td colspan='2'><input type="text" class='form-control input-sm' id='incomingSender' placeholder='<?= $text->sender ?>'></td>
                                    <td></td>
                                    <td>
                                        <select class='form-control input-sm' id='incomingStatus'>
                                            <option value="" selected><?= $text->status ?></option>
                                            <option value='1'><?= $text->status_solved ?></option>
                                            <option value='2'><?= $text->status_unsolved ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?= $text->sender ?></th>
                                    <th><?= $text->received_at ?></th>
                                    <th><?= $text->detail ?></th>
                                    <th><?= $text->status ?></th>
                                </tr>
                            </thead>
                            <tbody id='incomingBody'>
                                <?php foreach($incoming['messages'] as $val){
                                    
                                    $phone = str_replace(" ", "", $val['phone']);
                                    $phone = str_replace("-", "", $phone);
                                    $phone = str_replace("(", "", $phone);
                                    $phone = str_replace(")", "", $phone);

                                    $phone = substr($phone, -9);
                                    $nameAndSurname = $borrowers[$phone]['firstname']." ".$borrowers[$phone]['surname'];
                                    
                                    $datetime = new DateTime($val['time']);
                                    $time = $datetime->format("d.m. H:i");
                                    echo "<tr>"
                                            ."<td>".$nameAndSurname."</td>"
                                            ."<td>".$time."</td>"
                                            ."<td><a href='#'><i class='fa fa-link' onclick='getMessage(event, ".$val['sms_id'].");' id='".$val['sms_id']."'></i></a></td>";
                                    if($val['status'] == 1){
                                         echo "<td><span class='badge solvedMessage'>".$text->status_solved."</span></td>";
                                    }
                                    elseif($val['status'] == 2){
                                         echo "<td><span class='badge unsolvedMessage solve' onclick='solve(event, ".$val['sms_id'].");' id='".$val['sms_id']."'>".$text->status_unsolved."</span></td>";
                                    }
                                    else{
                                        echo "<td><span class='badge badgeInfo'></span></td>";
                                    }
                                   
                                      echo  "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="incoming_pagination pagination">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
         
        

    <div id="myModal" class="modal fade">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                    <h4 class="modal-title"><?= $text->message ?></h4>

                </div>

                <div class="modal-body ajaxMessage">

                    

                </div>
                <div class="modal-body solverInfo">
                        
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= $text->close ?></button>

                </div>

            </div>

        </div>

    </div>
        
    <div id="menuModal" class="modal fade">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                    <h4 class="modal-title"><?= $text->prefill ?></h4>

                </div>

                <div class="modal-body">

                   <div class="list-group">
                        <a href="#" data-dismiss="modal" class="list-group-item template_default"><?= $text->template_default ?></a>
                        <a href="#" data-dismiss="modal" class="list-group-item template_left"><?= $text->template_left ?></a>
                        <a href="#" data-dismiss="modal" class="list-group-item template_not_picked_up"><?= $text->template_not_picked_up ?></a>
                        <a href="#" data-dismiss="modal" class="list-group-item template_new_mvs"><?= $text->template_new_mvs ?></a>
                        <a href="#" data-dismiss="modal" class="list-group-item template_return"><?= $text->template_return ?></a>
                   </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= $text->close ?></button>

                </div>

            </div>

        </div>

    </div>
        
        <div id="solveModal" class="modal fade">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                    <h4 class="modal-title"><?= $text->message ?></h4>

                </div>

                <div class="modal-body">

                    <?php echo $text->wannaSolveThisMessage; ?>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default solveIt" id="" data-dismiss="modal"><?= $text->yes ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= $text->close ?></button>

                </div>

            </div>

        </div>

    </div>
        
        
        
        
        
        

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
    <script src="../js/bootstrap-switch.js"></script>
    
    <script type="text/javascript" src="http://ichord.github.io/Caret.js/src/jquery.caret.js"></script>
    <script src="../js/jquery.atwho.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/v4.0.0/src/js/bootstrap-datetimepicker.js"></script>
    
    <script>
        $( document ).ready(function() {
            
            $("#outgoingStatus").on("change", function(event){
                
                event.preventDefault();
                changeOutgoingPage(1);
                
            });

            $("#incomingStatus").on("change", function(event){
                
                event.preventDefault();
                changeIncomingPage(1);
                
            });
            
            
            
            
            $(".incomingFilter").hide();
            $(".outgoingFilter").hide();
            
            window.incomingCount = <?= $incoming['count'] ?>;
            window.outgoingCount = <?= $outgoing['count'] ?>;
            
            // print paginator
            printPagination("outgoing_pagination", window.outgoingCount, "changeOutgoingPage", 1);
            printPagination("incoming_pagination", window.incomingCount, "changeIncomingPage", 1);
            
            var options = {
                onText: "<?= $text->yes ?>",
                onColor: 'primary',
                offColor: 'danger',
                offText: "<?= $text->no ?>",
                animate: true,
            };

            $("#deliveryReport").bootstrapSwitch(options);
            
            
                $('#datetimepicker1').datetimepicker({
                icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down",
                    previous: "fa fa-mail-reply",
                    next: "fa fa-mail-forward"
                },
                locale: 'cs',
                stepping: 5,
                sideBySide: true,
                format: "DD. MM. YYYY HH:mm:ss"
            });
            
                
            
                $("#sendForm").hide();
            
                $(".sendMessage").on("click", function(){
                    $("#sendForm").slideDown();
                });
                
                $("#sendFormClose").on("click", function(){
                    $("#sendForm").slideUp();
                    $("#message").val("");
                    $("#receivers").val("");
                    $("#time").val("");
                });
            
            $('.receivers').atwho({
                at: "",
                data:[<?= $borrowersAsString ?>]
            });
            
            $('#outgoingReceiver').atwho({
                at: "",
                data:[<?= $borrowersAsString ?>]
            }).on("inserted.atwho", function(event, $li) {
                event.preventDefault();
                changeOutgoingPage(1);
            });
            
            $('#incomingSender').atwho({
                at: "",
                data:[<?= $borrowersAsString ?>]
            }).on("inserted.atwho", function(event, $li) {
                event.preventDefault();
                changeIncomingPage(1);
            });
            
            
            
            $('#outgoingStaff').atwho({
                at: "",
                data:[<?= $staffAsString ?>]
            }).on("inserted.atwho", function(event, $li) {
                event.preventDefault();
                changeOutgoingPage(1);
            });
            
            $('#outgoingReceiver').on("change", function(){
                if($(this).val() == ""){
                    changeOutgoingPage(1);
                }
            });
            $('#outgoingStaff').on("change", function(){
                if($(this).val() == ""){
                    changeOutgoingPage(1);
                }
            });
            $('#incomingSender').on("change", function(){
                if($(this).val() == ""){
                    changeIncomingPage(1);
                }
            });
            
            var len = 0;
            $("#message").on("keyup", function(){
                len = $(this).val().length;
                $('#charNum').text(len);
                $('#charLeft').text(459 - len);
                
                if(len <= 160){
                    $('#noSMS').text(1);
                }
                else if((len > 160) && (len <= 306)){
                    $('#noSMS').text(2);
                }
                else if((len > 306) && (len <= 459)){
                    $('#noSMS').text(3);
                }
                else{
                    $('#noSMS').text("Too many");
                }
                
            });
            
            $(".sendMessageSubmit").on("click", function(event){
                
                event.preventDefault();
                
                var len = $("#message").val().length;
                var receiversLen = $("#receivers").val().length;
                var message = $("#message").val();
                var time = $("#time").val();
                var deliveryReport = 0;
                if($('#deliveryReport').prop('checked')){
                    deliveryReport = 1;
                }

                var receivers = $("#receivers").val();
                
                
                
                if(len == 0){
                    alert("<?= $text->too_short_message ?>");
                }
                else if(len > 459){
                    alert("<?= $text->too_many_characters ?>");
                }
                else{
                    if(receiversLen == 0){
                        alert("<?= $text->no_receivers ?>");
                    }
                    else{
                        
                        $.ajax({
                            url: "sendSMS.ajax.php",
                            type: "POST",
                            data: { message: message, receivers: receivers, time: time, deliveryReport: deliveryReport},
                            dataType: "json"
                        }).always(function(){
                            console.log("Sending SMS... [time: "+time+", deliveryReport: "+deliveryReport);
                        }).done(function(results){
                            console.log(results);

                            if(results['status'] == 'error'){
                                console.log(results['message']);
                                alert(results['message']);
                            }
                            else{
                                console.log("SMS sent successfully");
                                alert("<?= $text->sms_sent_successfully ?>");
                                $("#sendForm").slideUp();
                                $("#sendForm").slideUp();
                                $("#message").val("");
                                $("#receivers").val("");
                                $("#time").val("");
                            }

                        }).fail(function(jqXHR, textStatus){
                            console.log( "Request failed: " + textStatus );
                        });
                    
                    }
                }
                
            });
            
            
            
            var template_default = "Dobry den, knihovna  C. Trebova 732 756 827";

            var template_left = "Dobry den, v pujcovne jste zapomnel(a)  ZZZ. Knihovna C. Trebova 732 756 827";

            var template_not_picked_up = "Dobry den, zatim jste si nevyzvedl(a) rezervovanou knihu XYZ. Zastavte se"
            +"pro ni nebo se nam ozvete. Knihovna C. Trebova 732 756 82";

            var template_new_mvs = "Dobry den, dorazila kniha ZZZ vami objednana pres MVS. Muzete si ji"
            +"vyzvednout. MK C. Trebova 732 756 827";

            var template_return = "Dobry den, prosime o vraceni ZZZ ,"
            +"abychom ji mohli odeslat zpet knihovne, ktera nam ji pujcila. Knihovna C. "
            +"Trebova 732 756 827";
            
            $(".template_default").on("click", function(event){
            
                event.preventDefault();
                
                $("#message").text(template_default);
                
                    len = $("#message").val().length;
                    $('#charNum').text(len);
                    $('#charLeft').text(459 - len);

                    if(len <= 160){
                        $('#noSMS').text(1);
                    }
                    else if((len > 160) && (len <= 306)){
                        $('#noSMS').text(2);
                    }
                    else if((len > 306) && (len <= 459)){
                        $('#noSMS').text(3);
                    }
                    else{
                        $('#noSMS').text("Too many");
                    }
                
            });
            
            $(".template_left").on("click", function(event){
            
                event.preventDefault();
                
                $("#message").text(template_left);
                
                len = $("#message").val().length;
                    $('#charNum').text(len);
                    $('#charLeft').text(459 - len);

                    if(len <= 160){
                        $('#noSMS').text(1);
                    }
                    else if((len > 160) && (len <= 306)){
                        $('#noSMS').text(2);
                    }
                    else if((len > 306) && (len <= 459)){
                        $('#noSMS').text(3);
                    }
                    else{
                        $('#noSMS').text("Too many");
                    }
                
            });
            $(".template_not_picked_up").on("click", function(event){
            
                event.preventDefault();
                
                $("#message").text(template_not_picked_up);
                
                len = $("#message").val().length;
                    $('#charNum').text(len);
                    $('#charLeft').text(459 - len);

                    if(len <= 160){
                        $('#noSMS').text(1);
                    }
                    else if((len > 160) && (len <= 306)){
                        $('#noSMS').text(2);
                    }
                    else if((len > 306) && (len <= 459)){
                        $('#noSMS').text(3);
                    }
                    else{
                        $('#noSMS').text("Too many");
                    }
                
            });
            
            $(".template_new_mvs").on("click", function(event){
            
                event.preventDefault();
                
                $("#message").text(template_new_mvs);
                
                len = $("#message").val().length;
                    $('#charNum').text(len);
                    $('#charLeft').text(459 - len);

                    if(len <= 160){
                        $('#noSMS').text(1);
                    }
                    else if((len > 160) && (len <= 306)){
                        $('#noSMS').text(2);
                    }
                    else if((len > 306) && (len <= 459)){
                        $('#noSMS').text(3);
                    }
                    else{
                        $('#noSMS').text("Too many");
                    }
                
            });
            
            $(".template_return").on("click", function(event){
            
                event.preventDefault();
                
                $("#message").text(template_return);
                
                len = $("#message").val().length;
                    $('#charNum').text(len);
                    $('#charLeft').text(459 - len);

                    if(len <= 160){
                        $('#noSMS').text(1);
                    }
                    else if((len > 160) && (len <= 306)){
                        $('#noSMS').text(2);
                    }
                    else if((len > 306) && (len <= 459)){
                        $('#noSMS').text(3);
                    }
                    else{
                        $('#noSMS').text("Too many");
                    }
                
            });
            
            $(".prefillMenuButton").on("click", function(event){
            
                event.preventDefault();
                
                $("#menuModal").modal('show');

            });
               
        }); /* DOCUMENT READY END */
        
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
            
            /* outgoing start */
            var changeOutgoingPage = function(goToPage, receiver="", sender="", staff="", status=""){
               console.log("Clicking on Go To Page: "+goToPage);
               $(".outgoing_pagination li").removeClass("active");
               $(".outgoing_pagination li a#"+goToPage).addClass("active");
               $("#outgoingBody").find("tr").remove();
               
                var receiver = $("#outgoingReceiver").val();
                var staff = $("#outgoingStaff").val();
                var status = $("#outgoingStatus").val();
                if(status === null){status = "";}

                var fromRecord = goToPage * 10 - 10;

                var type = "outgoing";

                $.ajax({
                    url: "getMessagesByLimit.ajax.php",
                    type: "POST",
                    data: { type: type, from: fromRecord, offset: 10, receiver: receiver, sender: sender, staff: staff, status: status },
                    dataType: "json"
                }).always(function(){
                    $("#loading-indicator").show();
                }).done(function(results){
                    $("#loading-indicator").hide();
                    console.log(results);
                    $.each(results, function(i, item) {

                        var status;
                        if(item.status == 0){
                             status = "<td><span class='badge badgeInfo'><?= $text->status_0 ?></span></td>";
                        }
                        else if(item.status == 1){
                             status = "<td><span class='badge badgeInfo'><?= $text->status_1 ?></span></td>";
                        }
                        else if(item.status == 2){
                             status = "<td><span class='badge badgeInfo'><?= $text->status_2 ?></span></td>";
                        }
                        else if(item.status == 3){
                             status = "<td><span class='badge badgeInfo notDelivered'><?= $text->status_3 ?></span></td>";
                        }
                        else if(item.status == 5){
                             status = "<td><span class='badge badgeInfo expiredMessage'><?= $text->status_5 ?></span></td>";
                        }
                        else{
                            status = "<td><span class='badge badgeInfo'>"+item.status+"</span></td>";
                        }

                        $('.outgoing').find('tbody:last').append("<tr><td>"+item.receiver+"</td><td>"+item.datetime+"</td><td><a href='#'><i class='fa fa-link' onclick='getMessage(event, "+item.sms_id+");'  id='"+item.sms_id+"'></i></a></td>"+status+"<td>"+item.sender+"</td></tr>");
                        window.outgoingCount = item.count;
                        
                    });

                    results.length = 0;
                    //alert(window.outgoingCount);
                    printPagination("outgoing_pagination", window.outgoingCount, "changeOutgoingPage", goToPage);

                }).fail(function(jqXHR, textStatus){
                    console.log( "Request failed: " + textStatus );
                });

            };
            /* outgoing end */
            /* incoming start */
            var changeIncomingPage = function(goToPage, receiver="", sender="", staff="", status=""){
               console.log("Clicking on Go To Page: "+goToPage);
               $(".incoming_pagination li").removeClass("active");
               $(".incoming_pagination li a#"+goToPage).addClass("active");
               $("#incomingBody").find("tr").remove();

                var status = $("#incomingStatus").val();
                var sender = $("#incomingSender").val();
                if(status === null){status = "";}

                var fromRecord = goToPage * 10 - 10;
    
                var type = "incoming";
               
                $.ajax({
                    url: "getMessagesByLimit.ajax.php",
                    type: "POST",
                    data: { type: type, from: fromRecord, offset: 10, receiver: receiver, sender: sender, staff: staff, status: status },
                    dataType: "json"
                }).always(function(){
                    $("#loading-indicator").show();
                }).done(function(results){
                    $("#loading-indicator").hide();
                    console.log(results);
                    $.each(results, function(i, item) {

                        var status = "";
                        if(item.status == 1){
                             status = "<td><span class='badge solvedMessage'><?= $text->status_solved ?></span></td>";
                        }
                        else if(item.status == 2){
                             status = "<td><span class='badge unsolvedMessage solve' onclick='solve(event, "+item.sms_id+");' id='"+item.sms_id+"'><?= $text->status_unsolved ?></span></td>";
                        }
                        else{
                            status = "<td><span class='badge badgeInfo'></span></td>";
                        }

                        $('.incoming').find('tbody:last').append("<tr><td>"+item.receiver+"</td><td>"+item.datetime+"</td><td><a href='#'><i class='fa fa-link' onclick='getMessage(event, "+item.sms_id+");'  id='"+item.sms_id+"'></i></a></td>"+status+"</tr>");
                        window.incomingCount = item.count;
                    });

                    results.length = 0;
                    //alert(window.incomingCount);
                    printPagination("incoming_pagination", window.incomingCount, "changeIncomingPage", goToPage);

                }).fail(function(jqXHR, textStatus){
                    console.log( "Request failed: " + textStatus );
                });
                    
                    

            };
            /* incoming end */
            
            var getMessage = function(event, id){
            
                event.preventDefault();
            
                $.ajax({
                    url: "getMessage.ajax.php",
                    type: "POST",
                    data: { id: id },
                    dataType: "json"
                }).always(function(){
                    $("#loading-indicator").show();
                    console.log("Getting message ID "+id);
                }).done(function(results){
                    $("#loading-indicator").hide();
                    console.log(results);
                    $(".ajaxMessage").text(results['message']);
                    
                    if(results['type'] == "incoming"){
                        if(results['status'] == 1){
                            $(".solverInfo").text("<?= $text->solver ?>: "+results['solver']);
                        }
                        else{ //2
                            $(".solverInfo").text("");
                        }
                    }
                    else{
                        $(".solverInfo").text("");
                    }
                    
                    $("#myModal").modal('show');
                }).fail(function(jqXHR, textStatus){
                    console.log( "Request failed: " + textStatus );
                });
            
            };
            
            var showOutgoingFilter = function(event){
            
                event.preventDefault();
            
                $(".outgoingFilterButton").slideUp();
                $(".outgoingFilter").slideDown();
            
            };
            
            var showIncomingFilter = function(event){
            
                event.preventDefault();
            
                $(".incomingFilterButton").slideUp();
                $(".incomingFilter").slideDown();
            
            };
            
            var solve = function(event, id){
            
                event.preventDefault();
                $(".solveIt").attr("id", id);
                $("#solveModal").modal("show");
                
            };
            
            $(".solveIt").on("click", function(event){
                
                event.preventDefault();
                
                var id = $(this).attr("id");

                // change CSS and text
                  
                  
                  var new_status = 1;
                  
                // ajax set status, solver_id
                $.ajax({
                    url: "solveMessage.ajax.php",
                    type: "POST",
                    data: { sms_id: id, status: new_status},
                }).always(function(){
                    $("#loading-indicator").show();
                    console.log("Setting status ID "+id);
                }).done(function(results){
                    $("#loading-indicator").hide();
                    console.log(results);
                    
                    $("#incomingBody tr td").find("span#"+id).removeClass("unsolvedMessage");
                    $("#incomingBody tr td").find("span#"+id).removeClass("solve");
                    $("#incomingBody tr td").find("span#"+id).removeAttr("onclick");
                    $("#incomingBody tr td").find("span#"+id).addClass("solvedMessage");
                    $("#incomingBody tr td").find("span#"+id).text("<?= $text->status_solved ?>");
                    
                }).fail(function(jqXHR, textStatus){
                    console.log( "Request failed: " + textStatus );
                });
                
            });
        
    </script>

	<?php 
		require_once "../tpl/footer.php"; 
    ?>

