<?php
class Page {
	private $params;
	private $user;
	private $style;
	public $template; // ####

	public function __construct(&$user) {
		$this->user = &$user;
		$this->style = array();
	}
	
	public function load_template($template_name) {
		//var_dump($this->user);
		$template_file = realpath(dirname(__FILE__) . "/../templates/$template_name.php");
		if (file_exists($template_file)) {
			ob_start();
			$user = $this->user;
			$page = &$this;
			include $template_file;
			$this->template = ob_get_clean();
		} else {
			echo "$template_file не найден";
		}
	}
	
	public function add_style($style) {
	// <STYLE> в <HEAD>, иначе w3 валидатор ругается что стили в неположенном месте
		$this->style[] = $style;
	}

	public function style() {
		return implode(PHP_EOL . PHP_EOL, $this->style);
	}

	public function render() {
		$html = preg_replace_callback('/\{module-([\w\d-]+?)\}/siu', function ($matches) {
			$module_name = $matches[1];
			$module_file = realpath(dirname(__FILE__) . "/../modules/$module_name.php");
			$page = $this;
			if (file_exists($module_file)) {
				ob_start();
				include $module_file;
				return ob_get_clean();
			}
			return "Модуль $module_name не найден";
		}, $this->template);
		
		echo $html;
	}
	
// Обработка модулей шаблона
}
?>
