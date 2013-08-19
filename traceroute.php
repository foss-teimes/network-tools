<?php

	$dir = "/dev/shm/traceroute/";
	$tracefile = $dir . $_SERVER[REMOTE_ADDR] . "_" . $_GET[timestamp];
	$endmsg = "traceroute finised";

	// Start the execution of traceroute
	if($_GET[action] == "start") {

		// Remove old files from directory
		foreach (glob($dir . "*") as $file) {

		if (filemtime($file) < time() - 600) {
		    unlink($file);
		    }
		}

		// Check for IPV4 or IPV6
		if (strpos($_GET[address], ".") > -1)
			$version = "traceroute";
		else
			$version = "traceroute6";

		// Execute traceroute and add a custom "End of Execution" string for the client to recognize when to stop reading
		exec("$version '" . escapeshellcmd($_GET[address]) . "' > $tracefile; echo '$endmsg' >> $tracefile");
	}
	// Display the current state of traceroute execution
	elseif ($_GET[action] == "display") {
		echo nl2br(file_get_contents($tracefile));
	}
?>
