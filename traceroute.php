<?php
	$endmsg = "traceroute finised";
	$dir = "/dev/shm/traceroute/";
	$tracefile = $dir.$_SERVER[REMOTE_ADDR].$_GET[timestamp];

	if($_GET[action] == "start") {
		foreach (glob($dir."*") as $file) {

		if (filemtime($file) < time() - 86400) {
		    unlink($file);
		    }
		}

		if($_GET[version] != "traceroute" && $_GET[version] != "traceroute6")
			exit();
		exec("$_GET[version] '".escapeshellcmd($_GET[address])."' > $tracefile");
		exec("echo '$endmsg' >> $tracefile");
	}
	elseif ($_GET[action] == "display") {
		$content = nl2br(file_get_contents($tracefile));
		echo $content;
	}
?>
