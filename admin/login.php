<?php

require_once("../bj_config.php");

$admin_thisfile = (basename_withpath($_SERVER['REQUEST_URI']) == "admin") ? "index.php" : basename_withpath($_SERVER['REQUEST_URI']);
require("admin-functions.php");

switch($_GET['req']) {
	case "lostpass" :
	validate_session(true); ?>
	
<?php
	break;
	case "logout" :
		validate_session();
		setcookie("bj_auth",'',time()-7200,'/');
		@header("Location: ".load_option('siteurl')."admin/login.php");
		die();
	break;
	default :
		validate_session(true);
		if(isset($_POST['sent'])) {
			$user = $bj_db->get_item("SELECT * FROM `".$bj_db->users."` WHERE `login` = '".bj_clean_string($_POST['login'])."' LIMIT 1");
			if(
				isset($user['login']) #User exists...
				and ($user['password'] == md5( md5($user['pass_salt']) . md5($_POST['password']) )) #Password matches...
			) { #We're good to go.
				if($_POST['remember'] != "") {
					setcookie($bj_db->prefix."pass",md5( md5($user['pass_salt']) . md5($_POST['password']) ),time()+31536000,'/');
					setcookie($bj_db->prefix."id",$user['ID'],time()+31536000,'/');
				}
				else {
					setcookie($bj_db->prefix."pass",md5( md5($user['pass_salt']) . md5($user['password']) ),time()+7200,'/');
					setcookie($bj_db->prefix."id",$user['ID'],time()+7200,'/');
				}
				@header("Location: ".load_option('siteurl')."admin/".$_POST['redirect']);
				die();
			}
			else {
				$errors[] = _r('Login Incorrect.');
			}
		}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Blackjack &rsaquo; Login</title>
		<link rel="stylesheet" href="blackjack.css" type="text/css" />
	</head>
	<body class="login">
		<div class="loginbox">
			<div class="innerlogin">
				<a class="aligncenter" id="logo" href="http://ceeps.blogs.tbomonline.com/section/blackjack/"><h1 class="blank"><?php _e('Blackjack'); ?></h1></a>
				<?php
				if(is_array($errors)) { ?>
					<div class="loginerror littlespacing"><?php
					echo implode("<br />",$errors); ?></div><?php
				}
				?>
				<form method="post" action="login.php">
					<div class="littlespacing">
						<label for="login"><?php _e('Username:'); ?></label>
						<input type="text" name="login" id="login" value="<?php if(isset($_GET['u'])) { echo $_GET['u']; } ?>" />
					</div>
					<div class="littlespacing">
						<label for="password"><?php _e('Password:'); ?></label>
						<input type="password" name="password" id="password" value="" />
					</div>
					<div class="littlespacing">
						<input type="checkbox" name="remember" id="remember" value="yes" /> <label for="remember"><?php _e('Remember Me?'); ?></label>
					</div>
					<input type="hidden" name="redirect" value="<?php if(isset($_GET['redir'])) { echo urldecode($_GET['redir']); } ?>" />
					<input type="hidden" name="sent" value="yes" />
					<div class="submit largespacing">
						<input type="submit" class="button" value="Login" />
					</div>
				</form>
				<div class="column width50 aligncenter">
					<a href="register.php" class="blockit"><?php _e('Register'); ?></a>
				</div>
				<div class="column width50 aligncenter">
					<a href="login.php?req=lostpass" class="blockit"><?php _e('Password Recovery'); ?></a>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
}
?>