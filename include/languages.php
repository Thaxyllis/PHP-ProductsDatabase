<?php
class Languages {

	private $lang_cnt;
	private $lang_array = [];
	
	public function __construct($languages) {
	
		$this->lang_array = $languages;
		$cnt = 0;
		foreach ($this->lang_array as $key => $val) {
			$cnt++;
		}	

		$this->lang_cnt = $cnt;
	}
	
	public function getCount() {
		return $this->lang_cnt;
	}
	
	public function getLangArray() {
		return $this->lang_array;
	}
}

?>