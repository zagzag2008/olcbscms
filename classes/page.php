<?php
class Page {
	private $params;
	private $user;
	private $controller;
	public $style;
	public $template; // ####
	public $content;  // ####
	private $plugins = array(); // обнаруженные и загруженные плагины

	public function __construct(&$user, &$controller) {
		global $plugins;
		$this->user = &$user;
		$this->controller = &$controller;
		$this->style = array();
		
		foreach ($plugins as $plugin) {
			$pluginName = basename($plugin, '.php');
			$pluginClass = "Plugin\\$pluginName";
			if (class_exists($pluginClass)) {
				$this->plugins[] = new $pluginClass();
			}
		}
	}
	
	public function load_template($template_name) {
		$controller = $this->controller;
		$template_file = realpath(dirname(__FILE__) . "/../templates/$template_name.php");
		if (file_exists($template_file)) {
			ob_start();
			$user = $this->user;
			$page = &$this;
			include $template_file;
			$this->template = ob_get_clean();

			// обрабатываем плагины (onInit)
			foreach ($this->plugins as $plugin) {
				$plugin->onInit();
			}
			
			//var_dump($user);
		} else {
			echo "$template_file не найден";
		}
	}
	
	public function load_content() {
		$path = $this->controller->path;
		$page = $this->controller->page;
		$mode = $this->controller->mode;
		$content_file = realpath(__DIR__ . "/../pages") . ($path == '' ? '' : "$path") . $page . '.md';
		//echo "mode: '$mode', path: '$path', page: '$page'<br>$content_file";
		if (file_exists($content_file)) {
			$this->content = file_get_contents($content_file);
		} else {
			if ($mode =='view') {
				if ($this->user->isAuth()) {
					$this->content = "Страница $path$page не существует<BR><A href='$path$page/edit'>Создать</A>";
				} else {
					$this->content = "Страница $path$page не найдена";
					header("HTTP/1.0 404 Not Found");
				}
			} else {
				$this->content = '';
			}
		}

/*		case 'folder': // файловый менеджер (только для админов)
			$blog_path = $pages_path . '/' . ($sef[0] == 'home' ? '' : implode('/', $sef));
			$debug[] = $blog_path;
			$blog = scandir($blog_path);
			if ($blog !== false) {
				foreach ($blog as $blog_item) {
					$page_file = strtolower(substr($blog_item, -4, 4));
					if ($page_file !== '.php') continue;
					$content .= $blog_item . '<br>';
				}
			}
*/
//		}
	}

	public function render() {
		global $plugins;

		// обрабатываем $this->template встроенные блоки: {style}
		$this->template = preg_replace('/\{style\}/', implode(PHP_EOL . PHP_EOL, $this->style), $this->template);

		$content = $this->content;
		if ($this->controller->mode == 'edit') {
			$content = '<textarea id="page_editor">' . $content . '</textarea>';
			$content .= file_get_contents(realpath(__DIR__ . '/../modules') . '/easymde.php');
		} else {
			// 
			//$content = "<div id='content'>$content</div><script>document.getElementById('content').innerHTML = marked.parse(document.getElementById('content').innerHTML);</script>";
			$content = "<div id='content'>$content</div><script>mode = 'view'; document.getElementById('content').innerHTML = customMarkdownParser(document.getElementById('content').innerHTML);</script>";
		}

		// подставляем {content}
		$this->template = preg_replace('/\{content\}/', $content, $this->template);

// Обработка изображений и миниатюр (в кеш)
/*
$page_html = preg_replace_callback('/\<img\s+.*?\>/siu', function ($matches) {
	global $srp, $images_path;
	$attr_allowed = array('class', 'src', 'alt', 'title', 'width', 'height');
	$img = array();
	foreach ($attr_allowed as $attr) {
		if (preg_match("/$attr\s*=\s*([\"'])(.*?)\\1/siu", $matches[0], $img_attr)) {
			$img[$attr] = $img_attr[2];
		}
	}
	// #### тут у нас есть массив $img из которого нужно собрать миниатюру, лайтбокс или просто изображение и вернуть новый HTML вместо <img src="">
	//echo 'Исходное: ' . $img['src'] . PHP_EOL;
	if (isset($img['src'])) {
		$img['src'] = ltrim($img['src'], '/');
		$img['src'] = (strpos($img['src'], $srp) !== false) ? substr($img['src'], strlen($srp) + 1) : $img['src'];
		//echo 'Удалили SRP: ' . $img['src'] . PHP_EOL;
		$img['src'] = ltrim($img['src'], '/');
		$img_src_exp = explode('/', $img['src']);
		//print_r($img_src_exp);
		if ($img_src_exp[0] == 'https:' || $img_src_exp[0] == 'http:') {
			
		} elseif ($img_src_exp[0] != 'images' && $img_src_exp[0] != 'modules') {
			$img['src'] = "$srp/images/" . $img['src'];
		} else {
			$img['src'] = "$srp/" . $img['src'];
		}
		
		//echo $img['src'] . PHP_EOL;
		$img_attr = array();
		foreach ($img as $k => $v) {
			$img_attr[] = $k . '="' . $v . '"';
		}
		return '<IMG ' . implode(' ', $img_attr) . '>';
	}
	
	// возвращаем HTML без изменений
	return $matches[0];
}, $page_html);

echo $page_html;
*/		
		// обрабатываем Markdown
		
		// обрабатываем миниатюры

		// обрабатываем плагины (onRender)
		foreach ($this->plugins as $plugin) {
			$plugin->onRender();
		}

		echo $this->template;
	}
	
}
?>
