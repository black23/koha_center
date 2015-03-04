<?php 

    session_start();

	if (!headers_sent()) {
		header('Content-Type: text/html; charset=utf-8');
	}

    $root = "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/center";

    $langShortcut = "cs";
    
    $langFile = $root."/tpl/lang/".$langShortcut.".json";
    $langData = file_get_contents($langFile);
    $text = json_decode($langData);
    
    error_reporting(-1);
	ini_set('display_errors', 'On');
    

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $text->KOHA." ".$text->statistical_center; ?>">
    <meta name="keywords" content="<?php echo $text->keywords; ?>">
    <meta name="author" content="MartinKravec">

    <title><?php echo $text->KOHA." ".$text->statistical_center; ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo $root; ?>/css/bootstrap.min.css" rel="stylesheet">
    
    
     <style>
         body {
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #eee;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin .checkbox {
  font-weight: normal;
}
.form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="text"] {
  margin-bottom: 2px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
     </style>
     
     <!-- Custom CSS -->
    <link href="<?php echo $root; ?>/css/style.css" rel="stylesheet"> 



    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
      
      <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo $root; ?>"><strong><?php echo $text->KOHA; ?></strong><sup>&reg;</sup> <?php echo $text->statistical_center; ?></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="<?php echo "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']; ?>" title="<?php echo $text->koha_official_website; ?>" target="_blank"><?php echo $text->KOHA; ?></a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <div class="container">

      <form class="form-signin" action="login.ajax.php" method="post">
        <h2 class="form-signin-heading"><?php echo $text->sign_in; ?></h2>
        <label for="inputEmail" class="sr-only"><?php echo $text->user; ?></label>
        <input name="username" type="text" id="inputUsername" class="form-control" placeholder="<?php echo $text->enter_username; ?>" required autofocus>
        <label for="inputPassword" class="sr-only"><?php echo $text->password; ?></label>
        <input name="password" type="password" id="inputPassword" class="form-control" placeholder="<?php echo $text->password; ?>" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $text->sign_in; ?></button>
      </form>

    </div> <!-- /container -->
    
    <div class="alert box alert-warning wrong_credentials" role="alert"><?php echo $text->wrong_credentials; ?></div>
    <div class="alert box alert-warning no_permissions" role="alert"><?php echo $text->no_permissions; ?></div>

    <script src="../js/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    
    <script type="text/javascript">
        $(".wrong_credentials").hide();
        $(".no_permissions").hide();
    </script>
    
    <?php
    if((isset($_SESSION["sign_in_error"]) AND ($_SESSION["sign_in_error"] == "1"))){
        echo "<script>$('.wrong_credentials').slideDown();</script>";
    }
    elseif((isset($_SESSION["sign_in_error"]) AND ($_SESSION["sign_in_error"] == "2"))){
        echo "<script>$('.no_permissions').slideDown();</script>";
    }
    ?>

  </body>
</html>
