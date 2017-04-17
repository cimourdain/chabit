<?php

namespace chabit;

class user
{
	use tDatesManager;
	use tObservedClass;

	protected $username;
	protected $userhash;

	public function __construct()
	{
		$this -> setObserver('user');

		if(isset($_GET['username']) && isset($_GET['usermagicpass']))
		{
			$this -> observer :: addNewLog('user', 'Login with provided $_GET values.', 3);
			$this -> logout();
			$this -> login($_GET['username'], $_GET['usermagicpass']);
		}else if ($this -> isLoggedIn()){
			
			$this -> register($_SESSION['username'], $_SESSION['userhash']);
		}
	}

	public function setUserName($username)
	{
		$this -> observer :: addNewLog('user', 'Set user Name to '.htmlentities($username), 3);
		$_SESSION['username'] = $this -> username = htmlentities($username);

	}

	public function setUserHash($userhash)
	{
		$_SESSION['userhash'] = $this -> userhash = $userhash;
	}

	public function getUserName()
	{
		return $this -> username;
	}

	public function getUserHash()
	{
		return $this -> userhash;
	}

	public function isLoggedIn()
	{
		if(isset($_SESSION['username']) && isset($_SESSION['userhash']))
		{
			return true;
		}else{
			return false;
		}
	}

	protected function login($username, $magic_pass)
	{
		$this -> observer :: addNewLog('user', 'Login user :'.$username, 3);
		$this -> setUserName($username);
		$this -> setUserHash($this -> calculateHash($this -> getUserName(), htmlentities($magic_pass)));
	}

	protected function register($username, $hash)
	{
		$this -> observer :: addNewLog('user', 'Register already logged user :'.$username, 3);
		$this -> setUserName($username);
		$this -> setUserHash($hash);
	}

	public function calculateHash($username, $hash = '')
	{
		return md5($username.$hash);
	}

	public function logout()
	{
		session_destroy();
		session_start();

		//remove user from online users
	}

}

?>