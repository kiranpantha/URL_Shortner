<?php /* index.php ( lilURL implementation ) */

require_once 'includes/conf.php'; // <- site-specific settings
require_once 'includes/go_kiran_cf.php'; // <- lilURL class file
$lilurl = new lilURL();
$msg = '';

// if the form has been submitted
if ( isset($_POST['longurl']) &&  isset($_POST['longurl3']) && isset($_POST['longurl2']) )
{
	if($_POST['longurl2']!='GoHomeIndianMedia')
	{
	echo '<script type="text/javascript" language="javascript">
<!--
alert("Error in The Passkey Cannot Shorten Url");
document.location="index.php";
-->
</script>';
	die();
	}
	// escape bad characters from the user's url
	$longurl = trim(mysql_escape_string($_POST['longurl']));
	$longurl3 = trim(mysql_escape_string($_POST['longurl3']));

	// set the protocol to not ok by default
	$protocol_ok = false;
	
	// if there's a list of allowed protocols, 
	// check to make sure that the user's url uses one of them
	if ( count($allowed_protocols) )
	{
		foreach ( $allowed_protocols as $ap )
		{
			if ( strtolower(substr($longurl, 0, strlen($ap))) == strtolower($ap) )
			{
				$protocol_ok = true;
				break;
			}
		}
	}
	else // if there's no protocol list, screw all that
	{
		$protocol_ok = true;
	}
		
	// add the url to the database
	if ( $protocol_ok && $lilurl->add_url_spe($longurl,$longurl3) )
	{
		if ( REWRITE ) // mod_rewrite style link
		{
			$url = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF']).'/'.$lilurl->get_id($longurl);
		}
		else // regular GET style link
		{
			$url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?id='.$lilurl->get_id($longurl);
		}

		$msg = '<p class="success">URL is: <a href="'.$url.'">'.$url.'</a></p>';
	}
	elseif ( !$protocol_ok )
	{
		$msg = '<p class="error">Invalid protocol!</p>';
	}
	else
	{
		$msg = '<p class="error">Creation of your lil&#180; URL failed for some reason.</p>';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>

	<head>
		<title>Go.Kiran.CF</title>
		
		<style type="text/css">
		body {
			font: .8em "Trebuchet MS", Verdana, Arial, Sans-Serif;
			text-align: center;
			color: #333;
			background-color: #fff;
			margin-top: 5em;
		}
		
		h1 {
			font-size: 2em;
			padding: 0;
			margin: 0;
		}

		h4 {
			font-size: 1em;
			font-weight: bold;
		}
		
		form {
			width: 28em;
			background-color: #eee;
			border: 1px solid #ccc;
			margin-left: auto;
			margin-right: auto;
			padding: 1em;
		}

		fieldset {
			border: 0;
			margin: 0;
			padding: 0;
		}
		
		a {
			color: #09c;
			text-decoration: none;
			font-weight: bold;
		}

		a:visited {
			color: #07a;
		}

		a:hover {
			color: #c30;
		}

		.error, .success {
			font-size: 1.2em;
			font-weight: bold;
		}
		
		.error {
			color: #ff0000;
		}
		
		.success {
			color: #000;
		}
		
		</style>

	</head>
	
	<body onload="document.getElementById('longurl').focus()">
		
		<h1>Go.Kiran.CF [Chort Form]</h1><br /><?php echo $msg; ?>

		<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
		 <div align="left">
			<fieldset>
			  <label for="longurl">
			   Enter a long URL:
		         <br />
		  </label>
			      <p>
			      <input type="text" name="longurl" id="longurl" />
			      <br />
			      Special URL<br />
			      <input type="text" name="longurl3" id="longurl3" />
			      <br />
			        PASSWORD  to Shorten :<br />
			        <input type="text" name="longurl2" id="longurl2" />
	          </p>
</fieldset>   <input type="submit" name="submit" id="submit" value="Make it!" /></div>
		</form> 
	</body>
Just for Own Purpose
</html>
		
