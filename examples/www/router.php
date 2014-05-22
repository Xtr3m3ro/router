<?php
// router.php

function route() {
	$uri = $_SERVER["REQUEST_URI"];
	$possible_file = realpath(dirname(__FILE__) . $uri);

	if (is_file($possible_file) && $possible_file != __FILE__) {
		return false;    // serve the requested resource as-is.
	} else { 
		require_once("/usr/local/src/php/my-site/bootstrap.php");
	}
}

route();
