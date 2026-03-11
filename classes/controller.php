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
//		$sef = (strpos($path, $srp) == 0) ? substr($path, strlen($srp) + 1) : $sef; // удаляем путь к сайту, оставляем только SEF путь
		$sef = explode('/', $path);

		// Режим работы: view, edit, save, folder, captcha
		// Путь к файлу/папке

		// Режимы только для авторизованных пользователей
		$file_name = 'index';
		$sef_last = array_pop($sef);
		switch ($sef_last) {
		case 'folder': 
		case 'edit': 
		case 'save': 
			if ($user->isAuth()) $this->mode = $sef_last;
			break;
		case 'auth':
		case 'captcha':
			$this->mode = $sef_last;
			break;
		}

		$this->page = ($sef_last == '' ? 'index' : array_pop($sef));
		$this->path = implode('/', $sef);
		//$pages_path = realpath(dirname(__FILE__) . '/../pages');
		//$file_path = "$pages_path/$path";

		// для рендера использовать $controller->file_path, $controller->file_name и $controller->mode
/*		echo '<pre>';
		$mode = $this->mode;
		echo "mode = $mode" . PHP_EOL;
		echo "file_path = $file_path" . PHP_EOL;
		echo "file_name = $file_name" . PHP_EOL;
		print_r($sef);
		echo '</pre>';
*/
	}
}
?>
