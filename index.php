<?php
/*
 * created by foss.aueb.gr
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
<meta name="author" content="Periklis Ntanasis a.k.a. Master_ex &amp;&amp; Thomas Kapoulas a.k.a. tomkap" />
<meta name="keywords" content="traceroute ping nslookup foss teimes ipv4 ipv6" />
<meta name="description" content="free online network tools by foss.teimes" />

<title>Εργαλεία Δικτύου - Network Tools | Κοινότητα ΕΛ/ΛΑΚ ΤΕΙ Μεσολογγίου</title>

<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="https://foss.tesyd.teimes.gr/sites/default/files/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="main.css" type="text/css" media="all" />

<script>
function ajaxGet(url, id, interval) {

	var xmlhttp;
	
	if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else { // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (id) {
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById(id).innerHTML=xmlhttp.responseText;
				if (xmlhttp.responseText.search("traceroute finised") > -1)
					window.clearInterval(interval);
			}
		}
	}
	xmlhttp.open("GET", url, true);
	xmlhttp.send();
}

function manageTraceroute(form) {

	var interval, version;

	if(form.service.value != "traceroute" && form.service.value != "traceroute6")
		return true;

	if(form.address.value.search(".") > -1)
		version = "traceroute";
	else 
		version = "traceroute6";

	ajaxGet("traceroute.php?action=start&address="+form.address.value+"&version="+version);

	interval = window.setInterval(function() { ajaxGet("traceroute.php?action=display", "resp", interval); }, 200);
	
	return false;
}
</script>

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
<form name="input" action="index.php" method="get" onsubmit="return manageTraceroute(this)"><p>
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
        echo '    <option value="'.$s.'"';
        if (isset($_GET['service']) && $s == $_GET['service'])
            echo ' selected="selected"';
        echo '>'.$v.'</option>'."\n    ";
    }
?>
    </select>
    IP ADDRESS/HOSTNAME:
    <input type="text" name="address" value="<?php $ip=$_SERVER['REMOTE_ADDR']; echo (isset($_GET['submit']) ? trim($_GET['address']) : $ip); ?>"/>
    <input type="submit" name ="submit" value="Submit" /></p>
    <p class="smallfont">IPv4/IPv6 address example : www.google.com or google.com or 209.85.129.99 or 2a00:1450:4009:804::1003 - don't use 'http://' prefix</p>
</form> 
</div> <!-- input form -->
<div id="response"><p id="resp">
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
	if ( (strpos($address,'/')>0) || (strpos($address,'/')===0) ) {
		echo "Don't be naughty!";
		exit();
	}
	
	elseif ($service=="whois") {
		exec("whois '".escapeshellcmd($address)."'",$results);
	}

	elseif ($service=="nslookup") {
		exec("nslookup '".escapeshellcmd($address)."'",$results);   
	}

	elseif (strpos($address, ".") > -1) {
		if($service=="ping") {
			exec("ping '".escapeshellcmd($address)."' -c 4",$results);
		}
        
		elseif ($service=="traceroute") {
			exec("traceroute '".escapeshellcmd($address)."'",$results);
		}
	}

	else {
		if($service=="ping") {
			exec("ping6 '".escapeshellcmd($address)."' -c 4",$results);
		}
		elseif($service=="traceroute") {
			exec("traceroute6 '".escapeshellcmd($address)."'",$results);
		}
	}
	
	foreach ($results as $result) {
		echo $result;
		echo "<br />\n";
	}
	
	if ($results == null) {
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
