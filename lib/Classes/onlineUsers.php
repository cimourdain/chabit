<?php

namespace chabit;

class onlineUsers
{
	use tDatesManager;
	use tObservedClass;

	protected $online_users;
	protected $online_users_file = 'OnlineUsers.json';
	protected $inactive_after_mn = 10;

	public function __construct($rootFolder)
	{
		$this -> setObserver('onlineUsers');

		$this -> setOnlineUsersFile($rootFolder);

		$this -> cleanInactiveUsers();
	}

	/* SETTERS */
	protected function setOnlineUsersFile($rootFolder)
	{
		$this -> observer :: addNewLog('onlineUsers', 'Define OnlineUsers file.', 3);
		$file = $rootFolder.$this -> online_users_file;
		if(file_exists($file))
		{
			$this -> online_users_file = $file;
		}else{
			$this -> online_users_file = '';
		}
	}


	/* GETTERS */
	public function getOnlineUsers($format = 'string')
	{
		$this -> fetchOnlineUsersFromFile();

		if($format == 'string')
		{
			return json_encode($this -> online_users, JSON_PRETTY_PRINT);
		}else
		{
			return $this -> online_users;
		}
	}

	/* CUSTOM */
	//get content of online user file
	protected function fetchOnlineUsersFromFile()
	{
		$file_content =  @file_get_contents($this -> online_users_file);
		if ($file_content === false)
		{
			$this -> online_users = [];
		}else{
			$this -> online_users = json_decode($file_content, true);	
		}
	}

	protected function updateOnlineUsersFile()
	{
		@file_put_contents($this -> online_users_file, json_encode($this -> online_users));
	}


	//clean inactive users
	public function cleanInactiveUsers()
	{
		
		$this -> fetchOnlineUsersFromFile();
		//echo 'Current date :'.$this -> current_date->format('Y-m-d H:i:s').'<br />';
		//parse users to find 
		foreach($this -> online_users as $ju => $jud)
		{
			//get user last update date as timestamp
			//echo 'Last update date :'.$jud['LAST_UPDATE'].'<br />';
			$last_update_date = strtotime($jud['LAST_UPDATE']);
			
			
			//get time diff between now and user last update
			$diff = round(($this -> getCurrentDate() -> getTimestamp() - $last_update_date)/60, 0);
			
			//remove user if inactivity delay is over
			//echo $ju.' is connected since '.$diff.'mn<br />';
			if ($diff > $this -> inactive_after_mn)
			{
				//echo 'Remove '.$ju.' because is is over the tolerance of '.$this -> inactive_after_mn.'mn<br />';
				unset($this -> online_users[$ju]);
			}
		}
		$this -> updateOnlineUsersFile();
		
	}

	public function isHashInOnlineUsers($hash)
	{
		$this -> observer :: addNewLog('onlineUsers', 'Chack if '.$hash.' is in Online users.', 3);
		foreach($this -> online_users as $ju => $jud)
		{
			if($ju == $hash)
			{
				return true;
			}
		}
		$this -> observer :: addNewLog('onlineUsers', $hash.' was not found in Online users.', 3);
		return false;	
	}

	public function getUserNameFromHash($hash)
	{
		$this -> observer :: addNewLog('onlineUsers', 'get Username from hash.', 3);
		foreach($this -> online_users as $ju => $jud)
		{
			if($ju == $hash)
			{
				return $jud['USERNAME'];
			}
		}
		$this -> observer :: addNewLog('onlineUsers', $hash.' was not found in Online users, return null.', 3);
		return null;
	}

	public function addOrUpdateUser($username, $hash)
	{
		//add new user to Online Users File
		$this -> fetchOnlineUsersFromFile();

		$this -> online_users[$hash] = array("USERNAME" => $username, "LAST_UPDATE" => $this -> getCurrentDate() ->format($this -> date_format));

		$this -> updateOnlineUsersFile();
	}

	public function removeUser($username, $hash)
	{
		//add new user to Online Users File
		$this -> fetchOnlineUsersFromFile();

		unset($this -> online_users[$hash]);

		$this -> updateOnlineUsersFile();
	}

}

?>