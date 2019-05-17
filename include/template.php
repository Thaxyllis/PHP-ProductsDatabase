<?php
class Template {
	public function getHTML($name) {

		$html = file_get_contents('template/'.$name.'.html');
		return $html; 
	}
}
?>