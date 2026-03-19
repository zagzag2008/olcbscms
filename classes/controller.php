<?php
class Controller {
	public $mode = 'view';
	public $page = '';
	public $path = '';
	public $base_path = '';

	public function __construct(&$user) {
		// SEF
		$request_uri = $_SERVER['REQUEST_URI'];
		$path = parse_url($request_uri, PHP_URL_PATH);
		$path = trim($path, '/');

		// SEF парсинг с автоматическим удалением базового пути
		// Определяем базовый путь сайта (относительно корня)
		$script_name = $_SERVER['SCRIPT_NAME'];  // или $_SERVER['PHP_SELF']
		$base_path = trim(parse_url($base_url = 'http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $script_name, PHP_URL_PATH), '/');

		// Разбиваем путь на сегменты
		$sef = array_filter(explode('/', $path));

		// Автоматически удаляем сегменты базового пути
		$base_segments = array_filter(explode('/', $base_path));

		$base_path = $base_segments;
		array_pop($base_path);
		$this->base_path = implode('/', $base_path);

		$shift_count = count($base_segments);
		for ($i = 1; $i < $shift_count && !empty($sef); $i++) {
			 array_shift($sef);
		}


		$modes = ['edit', 'save', 'captcha', 'auth', 'folder', 'logout', 'upload'];
		$mode = 'view';
		
		if (empty($sef)) $sef[] = 'index';
		
		if (!empty($sef) && in_array(end($sef), $modes)) {
		  $mode = array_pop($sef);
		}
		$this->page = array_pop($sef);
		$this->path = $this->base_path . !empty($sef) ? '/' . implode('/', $sef) . '/' : '/';

		if ($user->isAuth() || in_array($mode, array('captcha', 'auth', 'logout'))) {
			if ($mode == 'logout') {
				$user->logout();
				header("Location: {$this->base_path}/");
			}
			$this->mode = $mode;
		}
		
		//var_dump($this);
		//echo "<pre>mode = {$this->mode}, path = {$this->path}, page = {$this->page}, </pre>";
	}
}
?>
