<?php
/*
 * created by foss.aueb.gr
 * extended by foss.tesyd.teimes.gr
 *
 * Thomas Kapoulas <tomkap@pebkac.gr>
 * Nick Raptis <airscorp@gmail.com>
 * Charalampos Kostas <root@charkost.gr>
 */
?>
<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="el">
<head>
<meta charset="UTF-8" />
<meta name="author" content="Periklis Ntanasis a.k.a. Master_ex, Thomas Kapoulas a.k.a. tomkap, Charalampos Kostas a.k.a charkost " />
<meta name="keywords" content="traceroute ping nslookup foss teimes ipv4 ipv6" />
<meta name="description" content="free online network tools by foss.teimes" />

<title>Εργαλεία Δικτύου - Network Tools | Κοινότητα ΕΛ/ΛΑΚ ΤΕΙ Μεσολογγίου</title>

<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="https://foss.tesyd.teimes.gr/sites/default/files/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="main.css" type="text/css" media="all" />
</head>

<body>
<div id="wrapper">

<div id="header" class="clearfix">
	<div id="site-logo">
		<a href="https://foss.tesyd.teimes.gr/" title="Home">
			<img src="https://foss.tesyd.teimes.gr/sites/default/files/tux-header.png" alt="Home" />
		</a>
	</div>
	<div id="subheader">
		<h1>Εργαλεία Δικτύου - Network Tools</h1>
	</div>
</div>

<div id="main">
<div id="input_form">
<form name="input" action="index.php" method="get"><p>
	<select name="service">
<?php

	$services_array = array(
		"traceroute"  => "traceroute",
		"ping"        => "ping",
		"nslookup"    => "nslookup",
		"whois"       => "whois",
	);

	// List options programmatically
	// output should look like
	// <option value="ping" selected="selected">ping</option>
	foreach ($services_array as $s => $v) {
		echo '<option value="'.$s.'"';
		if (isset($_GET['service']) && $s == $_GET['service'])
			echo ' selected="selected"';
		echo '>'.$v.'</option>'."\n";
	}
?>
	</select>
	IP ADDRESS/HOSTNAME:
	<input type="text" name="address" value="<?php $ip=$_SERVER['REMOTE_ADDR']; echo (isset($_GET['submit']) ? trim($_GET['address']) : $ip); ?>"/>
	<input type="submit" name ="submit" value="Submit" /></p>
	<p class="smallfont">IPv4/IPv6 address example : www.google.com or google.com or 209.85.129.99 or 2a00:1450:4009:804::1003 - don't use 'http://' prefix</p>
</form>
</div> <!-- input form -->
<div id="response"><p>
<?php
function stream_exec($cmd) {
	$descriptorspec = array(
		0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
		1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
		2 => array("pipe", "w")    // stderr is a pipe that the child will write to
	);

	ob_flush();
	flush();

	$process = proc_open($cmd, $descriptorspec, $pipes, realpath('./'), array());
	$status = proc_get_status($process);

	while ($status[running]) {
		echo nl2br(fgets($pipes[1]));
		ob_flush();
		flush();
		usleep(2000);
		$status = proc_get_status($process);
	}
}

if(isset($_GET['submit']))
{
	// use of escapeshellcmd - must be enabled
	// http://php.net/manual/en/function.escapeshellcmd.php
	// escapes #&;`|*?~<>^()[]{}$\, \x0A and \xFF. ' and "
	// are escaped only if they are not paired.
	$service = trim($_GET['service']);
	$address = trim($_GET['address']);

	if ( (strpos($address,'/')>0) || (strpos($address,'/')===0) ) {
		echo "Don't be naughty!";
		exit();
	}

	elseif ($service=="whois") {
		echo nl2br( shell_exec("whois '".escapeshellcmd($address)."'") );
	}

	elseif ($service=="nslookup") {
		echo nl2br( shell_exec("nslookup '".escapeshellcmd($address)."'") );
	}

	elseif (strpos($address, ".") > -1) {
		if($service=="ping") {
			stream_exec("ping '".escapeshellcmd($address)."' -c 4");
		}

		elseif ($service=="traceroute") {
			stream_exec("traceroute '".escapeshellcmd($address)."'");
		}
	}

	else {
		if($service=="ping") {
			stream_exec("ping6 '".escapeshellcmd($address)."' -c 4");
		}
		elseif($service=="traceroute") {
			stream_exec("traceroute6 '".escapeshellcmd($address)."'");
		}
	}
?>

</p></div>

</div> <!-- main -->

<div id="footer">
    <p>Powered by foss.tesyd.teimes.gr</p>
</div>

</div> <!-- wrapper -->
</body>
</html>
