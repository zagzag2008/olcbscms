<?php
class User {
	public $user_login = null;
	private $users;

	public function __construct() {
		$this->user_login = isset($_SESSION['user_login']) ? $_SESSION['user_login'] : null;
	}

	public function isAuth() {
		return ($this->user_login != null);
	}

	public function login(string $login, string $password) {
		$this->users = $this->defaultUsers();
		if (!isset($this->users[$login])) return false;
		if ($password === $this->users[$login]['password']) {
			$_SESSION['user_login'] = $login;
			return true;
		} else {
			unset($_SESSION['user_login']);
		}
		return false;
	}

	private function defaultUsers() {
		return [
		'admin' => ['password' => '']
		];
	}
}
?>
