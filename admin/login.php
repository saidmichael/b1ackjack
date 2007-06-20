<?php

require_once("../bj_init.php");

$admin_thisfile = (basename($_SERVER['REQUEST_URI']) == ADMIN_DIR) ? "index.php" : basename($_SERVER['REQUEST_URI']);
require("admin-functions.php");

switch($_GET['req']) {
	case "lostpass" :
	validate_session(true); ?>
	
<?php
	break;
	case "logout" :
		validate_session();
		setcookie($bj->vars->passcookie,'',time()-7200,'/');
		setcookie($bj->vars->usercookie,'',time()-7200,'/');
		@header("Location: ".get_siteinfo('adminurl')."login.php");
		die();
	break;
	default :
		validate_session(true);
		if(isset($_POST['sent'])) {
			$user = $bj->db->get_item("SELECT * FROM `".$bj->db->users."` WHERE `user_login` = '".bj_clean_string($_POST['login'])."' LIMIT 1");
			if(
				isset($user['user_login']) #User exists...
				and ($user['user_pass'] == md5($_POST['password'])) #Password matches...
			) { #We're good to go.
				if(!empty($_POST['remember'])) {
					setcookie($bj->vars->passcookie,md5($user['user_pass']),time()+31536000,'/');
					setcookie($bj->vars->usercookie,$user['ID'],time()+31536000,'/');
				}
				else {
					setcookie($bj->vars->passcookie,md5($user['user_pass']),time()+7200,'/');
					setcookie($bj->vars->usercookie,$user['ID'],time()+7200,'/');
				}
				@header("Location: ".get_siteinfo('adminurl').urldecode($_POST['redirect']));
				die();
			}
			else
				$errors[] = _r('Login Incorrect.');
		}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php _e('Blackjack &rsaquo; Login'); ?></title>
		<link rel="stylesheet" href="blackjack.css" type="text/css" />
	</head>
	<body class="login">
		<div class="loginbox">
			<div class="innerlogin">
				<h1><a class="aligncenter" id="logo" href="<?php siteinfo('siteurl'); ?>"><span class="blank"><?php _e('Blackjack'); ?></span></a></h1>
				<?php
				if(is_array($errors)) { ?>
					<div class="loginerror littlespacing"><?php
					echo implode("<br />",$errors); ?></div><?php
				}
				?>
				<form method="post" action="">
					<p class="username">
						<label for="login"><?php _e('Username:'); ?></label>
						<input type="text" name="login" id="login" value="" />
					</p>
					<p class="password">
						<label for="password"><?php _e('Password:'); ?></label>
						<input type="password" name="password" id="password" value="" />
					</p>
					<p class="remember">
						<input type="checkbox" name="remember" id="remember" value="yarly" checked="checked" /> <label for="remember"><?php _e('Remember Me?'); ?></label>
					</p>
					<input type="hidden" name="redirect" value="<?php echo (isset($_GET['redir'])) ? urlencode($_GET['redir']) : ''; ?>" />
					<input type="hidden" name="sent" value="true" />
					<p class="submit">
						<input type="submit" class="button" value="Login" />
					</p>
				</form>
				<div class="column width100 aligncenter">
					<a href="login.php?req=lostpass" class="blockit"><?php _e('Password Recovery'); ?></a>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
}
?>
