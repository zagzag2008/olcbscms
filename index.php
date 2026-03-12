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

