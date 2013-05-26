<?php
/*
 * credits to:
 * c00kiemon5ter for various suggestions
 * HdkiLLeR(vpk) for security tips
 */
?>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="el" xml:lang="el">
<head>

<META AUTHOR="Periklis Ntanasis a.k.a. Master_ex && Thomas Kapoulas a.k.a. tomkap">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<meta name="keywords" content="traceroute ping nslookup foss teimes ipv4 ipv6" />
<meta name="description" content="free online network tools by foss.teimes" />
<meta name="distribution" content="global" />

<title>FOSS TEIMES - Network Tools</title>

<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="http://foss.tesyd.teimes.gr/sites/default/files/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="main.css" type="text/css" media="all" />

</head>

<body>
<div id="header" class="clearfix">
      <div id="site-logo"><a href="https://foss.tesyd.teimes.gr/" title="Home">
        <img src="https://foss.tesyd.teimes.gr/sites/default/files/tux-header.png" alt="Home" />
      </a></div>

      <h1>Εργαλεία Δικτύου - Network Tools</h1>
      <h5>Κοινότητα Ελεύθερου Λογισμικού και Λογισμικού Ανοιχτού Κώδικα ΤΕΙ Μεσολογγίου<br />
          Free and Open Source Software Community of TEI of Messolonghi</h5>
</div>

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
        if ($s == $_GET['service'])
            echo ' selected="selected"';
        echo '>'.$v.'</option>'."\n    ";
    }
    ?>
    </select>
    IP ADDRESS/HOSTNAME:
    <input type="text" name="address" value="<?php echo trim($_GET['address']); ?>"/>
    <input type="submit" name ="submit" value="Submit" /></p>
    <p class="smallfont">IPv4/IPv6 address example : www.google.com or google.com or 209.85.129.99 or 2a00:1450:4009:804::1003 - don't use 'http://' prefix</p>
</form> 

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
		echo "</br>";
	}
	if($results == null)
	{
		echo "Address format error or address doesn't exist";
	}
}
?>

</p></div>

<div id="footer">
    <hr />
    <p>Powered by <a href="https://foss.tesyd.teimes.gr/">foss.tesyd.teimes.gr</a></p>
</div>

</body>
</html>
