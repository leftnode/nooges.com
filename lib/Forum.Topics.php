<?php

require_once 'DataModeler/DataObject.php';

class Forum_Topics extends DataObject {
	public function __construct() {
		parent::__construct();
		$this->pkey('id_topic')->hasDate(false);
	}
}