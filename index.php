<?php
error_reporting(E_ALL);

session_start();

// Класс проверки авторизации пользователя
require 'classes/user.php';
$user = new User(); 

// Контролер отвечает за передаваемые команды и выбирает соответствующую реакцию
require 'classes/controller.php';
$controller = new Controller($user); 

// далее использовать $controller->file_path, $controller->file_name и $controller->mode

switch ($controller->mode) {
case 'captcha':
	require realpath(dirname(__FILE__)) . '/modules/captcha.php';
	exit;
case 'auth':
	sleep(2); // защита от быстрого подбора
	if (isset($_POST['login']) && isset($_POST['psw']) && isset($_POST['captcha'])) {
		$login    = $_POST['login'];
		$password = $_POST['psw'];
		$captcha   = $_POST['captcha'];
		if ($_SESSION['captcha'] == crypt($captcha, '$1$itchief$7')) {
			$user->login($login, $password);
		}
	}
	header("location: $srp/");
	exit;
case 'save': 
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		header('Content-Type: application/json');
		$input = json_decode(file_get_contents('php://input'), true);
		$markdown = $input['markdown'];
		$content_file = $pages_path . '/' . implode('/', $sef) . '.php';
		if (!file_exists($content_file) && is_dir($pages_path . '/' . implode('/', $sef))) {
			$content_file = $pages_path . '/' . implode('/', $sef) . '/index.php';
		}
		file_put_contents($content_file, $markdown);
		echo json_encode(['status' => 'success']);
	}
	exit;
case 'view':
case 'edit':
case 'edit':
	// Вывод страницы
	require 'classes/page.php';
	$page = new Page($user); 
	$page->load_template('saam');
	$page->render();
	$page_html = str_replace('{page-css}', implode(PHP_EOL . PHP_EOL, $page->style()), $page_html);
	break;
}

/*
// Загружаем контент
$content = '';
switch ($mode) {
case 'view':
case 'edit':
	$blog_path = $pages_path . '/' . implode('/', $sef);
	if (file_exists("$blog_path.php")) {
		$debug[] = "$blog_path.php"; // #### debug
		$content = file_get_contents("$blog_path.php");
	} elseif (file_exists("$blog_path/index.php")) {
		$debug[] = "$blog_path/index.php"; // #### debug
		$content = file_get_contents("$blog_path/index.php");
	} else {
		$content = '404. Указанный путь не найден';
	}
	break;
case 'folder': // файловый менеджер (только для админов)
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
}
$debug[] = "mode = $mode";
*/

/*
if (file_exists($content_file)) {
	$content = file_get_contents($content_file);
} else {
	$content_path = $pages_path . '/' . $cmd;
	if (is_dir($content_path) && isset($sef[1])) {
		$content_file = $content_path . '/' . $sef[1] . '.php';
		if (file_exists($content_file)) {
			$content = file_get_contents($content_file);
		}
	}
}
*/

/*
// Обработка контента
// парсер Markdown (c) Emanuil Rusev https://github.com/erusev/parsedown/
if ($mode == 'edit') {
	$content = '<textarea id="page_editor">' . $content . '</textarea>';
	$content .= call_module('simplemde');
} else {
	require_once 'classes/ParsedownExtended.php';
	$Parsedown = new ParsedownExtended();
	$Parsedown->setBreaksEnabled(true);
	$content = $Parsedown->text($content);
}
$page_html = str_replace('{content}', $content, $page_html);
*/

/*
$page_html = preg_replace_callback('/\{([\w\d-]+?)\}/siu', function ($matches) {
	global $sef, $srp, $user, $debug, $request_uri, $pages_path;
	switch($matches[0]) {
	case '{debug}': $ret = '<p>' . implode('<br>', $debug) . '</p>'; break;
	case '{srp}': $ret = $srp; break;
	case '{current-file}': 
		$content_file = $pages_path . '/' . implode('/', $sef) . '.php';
		if (!file_exists($content_file) && is_dir($pages_path . '/' . implode('/', $sef))) {
			$ret = 'index.php';
		} else {
			$ret = $sef[count($sef) - 1] . '.php';
		}
		break;
	case '{admin-breadcrumbs}': // Ссылки на просмотр папок и редактирование файла
		/ <A href="<?php echo $request_uri;?>/edit">{current-file}</A> /
		$folders = array();
		$html = '';
		$file = array_pop($sef);
		foreach ($sef as $folder) {
			$folders[] = $folder;
			$html .= ' / <A href="' . $srp . '/' . implode('/', $folders) . '/folder">' . $folder . '</A> ';
		}
		$ret = $html . ' / ' . '<A href="' . $request_uri . '/edit">' . $file . '</A>';
		
		break;
	default: $ret = $matches[0]; 
	}
	return $ret;
}, $page_html);
$page_html = str_replace('{page-css}', implode(PHP_EOL . PHP_EOL, $page->style()), $page_html);
*/

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