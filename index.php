<?php
/*
 * created by foss.aueb.gr
 * 
 * Thomas Kapoulas <tomkap@pebkac.gr>
 * Nick Raptis <airscorp@gmail.com>
 */
?>
<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="el">
<head>
<meta charset="UTF-8" />
<meta name="author" content="Periklis Ntanasis a.k.a. Master_ex &amp;&amp; Thomas Kapoulas a.k.a. tomkap" />
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
	<br /><?php $ip=$_SERVER['REMOTE_ADDR']; echo("Your IP address is ".$ip); ?>
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
        "traceroute6" => "traceroute (IPv6)",
        "ping"        => "ping",
        "ping6"       => "ping (IPv6)",
        "nslookup"    => "nslookup",
    );
    
    // List options programmatically
    // output should look like
    // <option value="ping" selected="selected">ping</option>
    foreach($services_array as $s => $v) {
        echo '    <option value="'.$s.'"';
        if (isset($_GET['service']) && $s == $_GET['service'])
            echo ' selected="selected"';
        echo '>'.$v.'</option>'."\n    ";
    }
    ?>
    </select>
    IP ADDRESS/HOSTNAME:
    <input type="text" name="address" value="<?php echo (isset($_GET['submit']) ? trim($_GET['address']) : $ip); ?>"/>
    <input type="submit" name ="submit" value="Submit" /></p>
    <p class="smallfont">IPv4/IPv6 address example : www.google.com or google.com or 209.85.129.99 or 2a00:1450:4009:804::1003 - don't use 'http://' prefix</p>
</form> 
</div> <!-- input form -->

<hr />

<div id="response"><p>
<?php
if(isset($_GET['submit']))
{
	// use of escapeshellcmd - must be enabled
	// http://php.net/manual/en/function.escapeshellcmd.php
	// escapes #&;`|*?~<>^()[]{}$\, \x0A and \xFF. ' and " 
	// are escaped only if they are not paired. 
	$service = trim($_GET['service']);
	$address = trim($_GET['address']);
        $results = null;
    if( 
           (strpos($address,'/')>0)
        || (strpos($address,'/')===0) )
	{
		echo "Don't be naughty!";
		exit();
	}
	if($service=="ping")
	{
		exec("ping '".escapeshellcmd($address)."' -c 4",$results);
	}
	if($service=="ping6")
	{
		exec("ping6 '".escapeshellcmd($address)."' -c 4",$results);
	}
	if($service=="traceroute")
	{
		exec("traceroute '".escapeshellcmd($address)."'",$results);
	}
        if($service=="traceroute6")
	{
		exec("traceroute6 '".escapeshellcmd($address)."'",$results);
	}
	if($service=="nslookup")
	{
		exec("nslookup '".escapeshellcmd($address)."'",$results);
	}
	foreach($results as $result)
	{
		echo $result;
		echo "<br />\n";
	}
	if($results == null)
	{
		echo "Address format error or address doesn't exist";
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
