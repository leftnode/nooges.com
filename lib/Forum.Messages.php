<?php

require_once 'DataModeler/DataObject.php';

class Forum_Messages extends DataObject {
	public function __construct() {
		parent::__construct();
		$this->pkey('id_msg')->hasDate(false);
	}
}