<?php


class Index_Controller extends Artisan_Controller {
	/// The name of the layout to use. Can be overwritten.
	protected $layout = 'nooges';
	
	public function indexGet() {
		try {
			/* Some globals used for later. */
			$forum_messages = new Forum_Messages();
			$forum_topics = new Forum_Topics();
			$nooges_response = new Nooges_Response();
			
			/* Query to load up the latest nooge. */
			$nooge = Nooges::getDataModel()
				->field($forum_messages->allFields())
				->innerJoin($forum_messages, new Forum_Topics(), 'id_topic', NULL)
				->where($forum_topics->fieldOp('id_board', '='), NOOGES_BOARD_ID)
				->where($forum_topics->fieldOp('locked', '='), STATUS_ENABLED)
				->orderBy($forum_messages->field('poster_time'), 'ASC')
				->loadFirst($forum_messages);

			/**
			 * Load the responses and filter them out into the proper response list.
			 * Then render the list and send it to the main layout.
			 */
			$nooge_list = Nooges::getDataModel()
				->innerJoin($forum_messages, new Nooges_Response(), NULL, 'id_msg')
				->where($forum_messages->fieldOp('id_topic', '='), $nooge->getIdTopic())
				->where($nooges_response->fieldOp('parent_id', '='), 0)
				->orderBy($nooges_response->field('date_create'), 'DESC')
				->loadAll($forum_messages);

			$this->nooge = $nooge;
			$this->response_list_left = $this->renderNoogeList($nooge_list, 0);
			$this->response_list_right = $this->renderNoogeList($nooge_list, 1);

			$this->renderLayout('index');
		} catch ( Exception $e ) { }
		
		return true;
	}
	
	
	
	
	public function queryGet() {
		$this->setLayout();
		
		try {
			
		} catch ( Exception $e ) { }
		
		return true;
	}
	
	
	
	
	
	
	
	
	
	
	public function createPost() {
		$this->setLayout(NULL);
		
		try {
			$topic_id = $this->getParam('topic_id');
			$parent_id = $this->getParam('parent_id');
			$side = $this->getParam('side');
			$message = $this->getParam('message');
			$message = trim($message);
			
			$poster_name = NOOGES_ANONYMOUS_USER;
			$poster_email = NOOGES_ANONYMOUS_EMAIL;
			
			/* See if there's a user ID in the session, if so, get the user from there. */
			$user_id = er('user_id', $_SESSION, 0);
			if ( $user_id > 0 ) {
				$forum_member = Nooges::getDataModel()
					->where('id_member = ?', $user_id)
					->loadFirst(new Forum_Members());
				
				if ( true === $forum_member->exists() ) {
					$poster_name = $forum_member->getRealName();
					$poster_email = $forum_member->getEmailAddress();
				}
			}
			
			
			/* Create the initial Forum_Messages record. */
			$forum_messages = new Forum_Messages();
			$forum_messages->setIdTopic($topic_id)
				->setIdBoard(NOOGES_BOARD_ID)
				->setPosterTime(time())
				->setIdMember($user_id)
				->setIdMsgModified(0)
				->setPosterName($poster_name)
				->setPosterEmail($poster_email)
				->setPosterIp(input_get_ipv4())
				->setSmileysEnabled(STATUS_DISABLED)
				->setBody($message)
				->setIcon('xx')
				->setApproved(STATUS_ENABLED);
			$message_id = Nooges::getDataModel()->save($forum_messages);
		
			if ( $message_id > 0 ) {
				/* Update some values in `forum_topics` */
				$forum_topic = Nooges::getDataModel()
					->where('id_topic = ?', $topic_id)
					->loadFirst(new Forum_Topics());
				if ( true === $forum_topic->exists() ) {
					$reply_count = $forum_topic->getNumReplies();
					$forum_topic->setIdLastMsg($message_id)
						->setNumReplies(++$reply_count);
					
					Nooges::getDataModel()->save($forum_topic);
				}
				
				/* Update the users post count. */
				$post_count = $forum_member->getPosts();
				$forum_member->setPosts(++$post_count);
				Nooges::getDataModel()->save($forum_member);
				
				/* Create the actual response. */
				$nooges_response = new Nooges_Response();
				$nooges_response->setIdTopic($topic_id)
					->setIdMsg($message_id)
					->setParentId($parent_id)
					->setSide($side)
					->setLikeCount(0)
					->setDislikeCount(0)
					->setStatus(STATUS_ENABLED);
				$nooges_response_id = Nooges::getDataModel()->save($nooges_response);
				
				/* Everything went well, return a entry. */
				$forum_messages = new Forum_Messages();
				
				/* Load up the latest response and display it. */
				$response = Nooges::getDataModel()
					->innerJoin($forum_messages, new Nooges_Response(), NULL, 'id_msg')
					->where($nooges_response->fieldOp('nooges_response_id', '='), $nooges_response_id)
					->loadFirst($forum_messages);
				$this->response = $response;
				
				$this->render('index/response-list-item');
			}
		} catch ( Exception $e ) { }
		
		return true;
	}
	
	public function votePost() {
		$this->setLayout(NULL);
		
		try {
			$votes = 0;
			$response_id = intval($this->getParam('response_id'));
			$direction = intval($this->getParam('direction'));
			
			if ( VOTE_UP != $direction && VOTE_DOWN != $direction ) {
				$direction = 1;
			}
			
			$nooges_response = Nooges::getDataModel()
				->where('nooges_response_id = ?', $response_id)
				->loadFirst(new Nooges_Response());
			
			switch ( $direction ) {
				case VOTE_UP: {
					$votes = $nooges_response->voteUp();
					break;
				}
				
				case VOTE_DOWN: {
					$votes = $nooges_response->voteDown();
					break;
				}
			}
			
			Nooges::getDataModel()->save($nooges_response);
		} catch ( Exception $e ) { }
		
		echo $votes;
		
		return true;
	}
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * Render the overall application so everything can be included at once.
	 * @param string $view The name of the view to render.
	 * @retval bool Returns true.
	 */
	private function renderLayout($view) {
		/* Global CSS */
		$this->css_nooges = DIR_CSS . 'nooges.css';
		
		/* Global JS */
		$this->js_jquery = DIR_JAVASCRIPT . 'jquery.js';
		$this->js_nooges = DIR_JAVASCRIPT . 'nooges.js';

		/* Render the header, which includes the CSS and JS. */
		$this->render('index/header', 'header');
		
		/* Render the menu. */
		$this->render('index/menu', 'menu');
		
		/* Render the sidebar where the forums are. */
		$this->board_list = Nooges::getDataModel()
			->where('id_parent = ?', 0)
			->orderBy('board_order', 'ASC')
			->limit(150)
			->loadAll(new Forum_Boards());
		$this->render('index/sidebar', 'sidebar');
		
		/* Render the body. */
		$this->render($view, 'body');
		
		/* And the footer. */
		$this->render('index/footer', 'footer');
		
		return true;
	}
	
	private function redirect($url) {
		header("Status: 200");
		header("Location: " . $url);
		exit;
	}
	
	private function renderNoogeList(DataIterator $nooge_list, $side) {
		$nooge_list->reset();
		$response_list = $nooge_list->filter('side = ?', $side)
			->fetch();
			
		$this->response_list = $response_list;
		$this->side = $side;
		
		$response_list = $this->render('index/response-list');
		return $response_list;
	}
	
}