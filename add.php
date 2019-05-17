<?php
include "config.php";
include "include/database.php";
include "include/languages.php";
include "include/template.php";
include "include/login.php";

	// Authenticate
	$login = new Login($db);	
	if (!$login->getLoggedIn()) return;

	$page = new Template();
	
	$header = $page->getHTML('header');
	$middle = $page->getHTML('add');
	$footer = $page->getHTML('footer');
	$body = $header.$middle.$footer;
	echo $body;
?>