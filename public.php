<?php

include "config.php";
include "include/database.php";
include "include/languages.php";
include "include/template.php";

	// Initialize new database object
	$db = new DB($host, $db, $user, $pass, $charset);

	// Initialize language object
	$langs = new Languages($db->getLanguages());
	$lang_array = $langs->getLangArray();
	if(isset($_GET['lang'])) $lang = $_GET['lang']; else $lang = 1;

	$products = $db->getMultipleProducts($lang);

	$page = new Template();
	
	$block_raw = $page->getHTML('public_block1');
	$block_complete = '';

	$lang_link = '';
	foreach ($lang_array as $key => $val) {
		if($val['lang_code'] > 1) $lang_link .= '|&nbsp;&nbsp;';
		$lang_link .= '<a href="public.php?lang='.$val['lang_code'].'">'.$val['name'].'</a>&nbsp;&nbsp;';
	}
		
	foreach($products as $key => $val) {
		$temp = $block_raw;
		$temp = str_replace('{NAME}', $val['name'], $temp);
		$temp = str_replace('{DESCRIPTION}', $val['description'], $temp);
		$temp = str_replace('{PRICE}', $val['price'], $temp);
		$temp = str_replace('{QUANTITY}', $val['quantity'], $temp);
		$block_complete .= $temp;								
	}

	$header = $page->getHTML('header_pub');
	$middle = $page->getHTML('public');
	$middle = str_replace('<!-- TEMPLATE BLOCK1 -->', $block_complete, $middle);
	$middle = str_replace('{LANGS}', $lang_link, $middle);
	$footer = $page->getHTML('footer_pub');
	$body = $header.$middle.$footer;
	echo $body;

?>