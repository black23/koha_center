        <script type="text/javascript">

            $(document).ready(function(){

                    $(".log_out").on("click", function(){

                        $.ajax({
                            url: "<?php echo $root; ?>/system/logout.ajax.php"
                        }).always(function(){
                            console.log("Logging out...");
                        }).done(function(results){
                            console.log("Logged out.");
                            window.location.replace("<?php echo $root; ?>/system/login.php");
                        }).fail(function(jqXHR, textStatus){
                            console.log( "Request failed: " + textStatus );
                        });		

                    });

                });

        </script>

	</body>

</html>
