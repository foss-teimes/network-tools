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
<title>foss.tesyd.teimes.gr &bull; Network Tools</title>
<link rel="icon" href="images/favicon.ico" type="image/x-icon" /> 
<link rel="shortcut icon" href="http://foss.tesyd.teimes.gr/favicon.ico" type="image/x-icon" />
</head>
<center><a href="http://foss.aueb.gr/" target="_blank"><img style="border-style: none" align="left" src="http://foss.tesyd.teimes.gr/favicon.png" /></a><font size=6><b>FOSS AUEB - Network Tools</b></font></center>
<h5> Κοινότητα Ελεύθερου Λογισμικού και Λογισμικού Ανοιχτού Κώδικα ΤΕΙ Μεσολογγίου<br/>Free and Open Source Software Community of TEI of Messolonghi</h5>
</br>
<body>
<form name="input" action="index.php" method="get">
<select name="servise">
<option value="traceroute">traceroute</option>
<option value="traceroute6">traceroute (IPv6)</option>
<option value="ping">ping</option>
<option value="ping6">ping (IPv6)</option>
<option value="nslookup">nslookup</option>
</select>
IP ADDRESS:
<input type="text" name="address" />
<input type="submit" name ="submit" value="Submit" />
</form> 
<font size=1>IPv4/IPv6 address example : www.google.com or google.com or 209.85.129.99 or 2a00:1450:4009:804::1003 - don't use 'http://' prefix </font>
<hr>
<?php
if(isset($_GET['submit']))
{
	// use of escapeshellcmd - must be enabled
	// http://php.net/manual/en/function.escapeshellcmd.php
	// escapes #&;`|*?~<>^()[]{}$\, \x0A and \xFF. ' and " 
	// are escaped only if they are not paired. 
	$servise = trim($_GET['servise']);
	$address = trim($_GET['address']);
    if( 
           (strpos($address,'/')>0)
        || (strpos($address,'/')===0) )
	{
		echo "Don't be naughty!";
		exit();
	}
	if($servise=="ping")
	{
		exec("ping '".escapeshellcmd($address)."' -c 4",$results);
	}
	if($servise=="ping6")
	{
		exec("ping6 '".escapeshellcmd($address)."' -c 4",$results);
	}
	if($servise=="traceroute")
	{
		exec("traceroute '".escapeshellcmd($address)."'",$results);
	}
        if($servise=="traceroute6")
	{
		exec("traceroute6 '".escapeshellcmd($address)."'",$results);
	}
	if($servise=="nslookup")
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
	echo "<hr>";
}
?>
<center>
<br/>
<span style="font-family: sans-serif; text-decoration: none; font-size: 10px;">Powered by <a href="http://foss.tesyd.teimes.gr">foss.tesyd.teimes.gr</a> <(' )</span><br/>
</center>
</body>
</html>
