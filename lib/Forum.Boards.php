<?php

require_once 'DataModeler/DataObject.php';

class Forum_Boards extends DataObject {
	public function __construct() {
		parent::__construct();
		$this->pkey('id_board')->hasDate(false);
	}
}