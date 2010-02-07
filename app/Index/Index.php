<?php


class Index_Controller extends Artisan_Controller {
	/// The name of the layout to use. Can be overwritten.
	protected $layout = 'nooges';
	
	public function indexGet() {
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
		$response_list = Nooges::getDataModel()
			->innerJoin($forum_messages, new Nooges_Response(), NULL, 'id_msg')
			->where($forum_messages->fieldOp('id_topic', '='), $nooge->getIdTopic())
			->where($nooges_response->fieldOp('parent_id', '='), 0)
			->orderBy($nooges_response->field('date_create'), 'DESC')
			->loadAll($forum_messages);

		$this->nooge = $nooge;
		$this->response_list_left = NULL;
		$this->response_list_right = NULL;

		$this->renderLayout('index');
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * Render the overall application so everything can be included at once.
	 * @param string $view The name of the view to render.
	 * @retval bool Returns true.
	 */
	protected function renderLayout($view) {
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
	
	protected function redirect($url) {
		header("Status: 200");
		header("Location: " . $url);
		exit;
	}
}
