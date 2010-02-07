<?php

require_once 'DataModeler/DataObject.php';

class Nooges_Response extends DataObject {
	public function voteUp() {
		$like_count = $this->getLikeCount();
		$this->setLikeCount(++$like_count);
		return $this->getLikeCount();
	}
	
	public function voteDown() {
		$dislike_count = $this->getDislikeCount();
		$this->setDislikeCount(++$dislike_count);
		return $this->getDislikeCount();
	}

}