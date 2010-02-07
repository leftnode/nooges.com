<?php

require_once 'DataModeler/DataObject.php';

class Forum_Members extends DataObject {
	public function __construct() {
		parent::__construct();
		$this->pkey('id_member')->hasDate(false);
	}
}