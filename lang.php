<?php

include "config.php";
include "include/database.php";
include "include/languages.php";
include "include/functions.php";
include "include/template.php";
include "include/login.php";

	// Authenticate
	$login = new Login($db);	
	if (!$login->getLoggedIn()) return;
	
	// Initialize new database object
	$db = new DB($host, $db, $user, $pass, $charset);

	// Initialize language object
	$langs = new Languages($db->getLanguages());

	$page = new Template();
	
	$block_raw = $page->getHTML('lang_block1');
	$block_complete = '';
		
	$lang_array = $langs->getLangArray();		
		
	foreach ($lang_array as $key => $val) {
		$temp = $block_raw;
		$temp = str_replace('{ID}', $val['lang_code'], $temp);
		$temp = str_replace('{NAME}', $val['name'], $temp);
		$block_complete .= $temp;								
	}

	$header = $page->getHTML('header');
	$middle = $page->getHTML('lang');
	$middle = str_replace('<!-- TEMPLATE BLOCK1 -->', $block_complete, $middle);
	$footer = $page->getHTML('footer');
	$body = $header.$middle.$footer;
	echo $body;

?>