<?php

/* Connection: close and Content-Length headers are sent, so that browsers disconnect
 * early on to minimize connection time */

ignore_user_abort(true);
ob_end_clean();

header('Content-type: image/gif');
header('Connection: close');

// Output a 1x1 pixel transparent gif
ob_start();
echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
$length = ob_get_length();

header("Content-Length: $length");

// Flush output - both calls required
ob_end_flush();
flush();

require_once "../../../wp-load.php";
require_once "config.php";
require_once "source/Tracking/RequestTracker.php";

if (isset($_GET[WPAM_PluginConfig::$RefKey])
    && get_option (WPAM_PluginConfig::$AffEnableImpressions)) {
	try {
		$requestTracker = new WPAM_Tracking_RequestTracker();
		$requestTracker->handleImpression($_GET);
	} catch (Exception $e) {
		wp_die("WPAM FAILED: " . $e->getMessage());             
	}
}

exit;
?>
