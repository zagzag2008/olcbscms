<?php
error_reporting(E_ALL);
session_start();

// Загрузка плагинов
spl_autoload_register(function ($class) {
	$file = __DIR__ . '/plugins/' . str_replace('Plugin\\', '', $class).'.php';
	if (file_exists($file)) require $file;
});
$plugins = glob(__DIR__ . '/plugins/*.php');

// Класс проверки авторизации пользователя
require 'classes/user.php';
$user = new User(); 

// Контролер отвечает за передаваемые команды и выбирает соответствующую реакцию
require 'classes/controller.php';
$controller = new Controller($user); 
// далее использовать $controller->file_path, $controller->file_name и $controller->mode

switch ($controller->mode) {
case 'captcha':
	require realpath(__DIR__ . '/modules/captcha.php');
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
	header("location: /");
	exit;
case 'save': 
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		header('Content-Type: application/json');
		$input = json_decode(file_get_contents('php://input'), true);
		$markdown = $input['markdown'];
		$path = $controller->path;
		$page = ($controller->page == '' ? 'index' : $controller->page);
		$content_file = realpath(__DIR__ . '/pages' . $path) . '/' . $page . '.md';
		file_put_contents($content_file, $markdown);
		echo json_encode(['status' => 'success']);
	}
	exit;
case 'upload': // загрузка изображений
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		header('Content-Type: application/json');
		$path = $controller->path;
		$page = ($controller->page == '' ? 'index' : $controller->page);
		$image_path = realpath(__DIR__ . '/pages' . $path) . '/' . $page . '.images/';
		//echo "path = $path, page = $page, image_path = $image_path";

		if (isset($_FILES['image'])) {
			require('classes/thumb.php');
			$thumb = new Thumb();
			$img_src = $_FILES['image']['tmp_name'];
			$image_data = file_get_contents($img_src);
			$image_name = md5($image_data) . '.jpg';
			$image_size = getimagesize($img_src); // 0 - width, 1 - height, 3 - html (width=".." height="..")
			$src_width  = $image_size[0];
			$src_height = $image_size[1];
			$src_aspect = $src_width / $src_height; // отношение = ширина / высота (800 / 600 = 1.3333)
			 
			if (!file_exists($image_path)) mkdir($image_path, 0777 , true); // папка для изображений

			if ($src_aspect > 1) {
				//$thumb->make_from_file($_FILES['image']['tmp_name'], 1280, 1280, 0); // по ширине 1280, а высота пропорционально
				$thumb->make_from_string($image_data, 1280, 1280, 0);
			} else {
				//$thumb->make_from_file($_FILES['image']['tmp_name'], 1280, 1280, 1); // по высоте 1280, а ширина пропорционально
				$thumb->make_from_string($image_data, 1280, 1280, 1);
			}
			$thumb->save_jpeg($image_path . $image_name);
			//echo $image_path . $image_name;
			unlink($_FILES['image']['tmp_name']);
			//echo json_encode(['url' => $path . $page . '.images/' . $image_name]);
			echo json_encode(['url' => $image_name]);
		}
	}
	exit;
case 'view':
case 'edit':
	// Вывод страницы
	require 'classes/page.php';
	$page = new Page($user, $controller); 
	$page->load_template('saam'); // + для каждого плагина вызывается событие onInit
	$page->load_content();
	echo $page->render(); // + для каждого плагина вызывается событие onRender
	break;
}

