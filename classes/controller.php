<?php
class Controller {
	public $mode = 'view';
	public $page = '';
	public $path = '';

	public function __construct(&$user) {
		// SEF
		$request_uri = $_SERVER['REQUEST_URI'];
		$path = parse_url($request_uri, PHP_URL_PATH);
		$path = trim($path, '/');

		$sef = array_filter(explode('/', trim($path, '/')));
		$modes = ['edit', 'save', 'captcha', 'auth', 'folder', 'logout'];
		$mode = 'view';
		if (!empty($sef) && in_array(end($sef), $modes)) {
		  $mode = array_pop($sef);
		}
		$this->page = !empty($sef) ? array_pop($sef) : 'index';
		$this->path = !empty($sef) ? '/' . implode('/', $sef) . '/' : '/';

		if ($user->isAuth() || in_array($mode, array('captcha', 'auth', 'logout'))) {
			if ($mode == 'logout') {
				$user->logout();
				header('Location: /');
			}
			$this->mode = $mode;
		}
		
		//var_dump($this);
		//echo "<pre>mode = {$this->mode}, path = {$this->path}, page = {$this->page}, </pre>";
	}
}
?>
