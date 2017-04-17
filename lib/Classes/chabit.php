<?php

namespace chabit;

class chabit
{

	use tObservedClass;
	use tDatesManager;

	protected $user;

	protected $chatFilesFolder = '../chat_files/';


	protected $online_users;

	
	protected $conversationsManager;

	public function __construct()
	{
		$this -> setObserver('chabit');

		$this -> setUser();

		$this -> setOnlineUsers();

		$this -> setConversationsManager();
	}

	/* SETTERS */
	//set current user
	protected function setUser()
	{
		$this -> observer :: addNewLog('chabit', 'Define user', 3);
		$this -> user = new user();

	}

	//set online users
	public function setOnlineUsers()
	{
		$this -> observer :: addNewLog('chabit', 'Define online users', 3);
		$this -> online_users = new onlineUsers($this -> chatFilesFolder);

		//add current user in list if is logged in
		if($this -> user -> isLoggedIn())
		{
			$this -> observer :: addNewLog('chabit', 'Add current user to list of users', 3);
			$this -> online_users -> addOrUpdateUser($this -> user -> getUserName(), $this -> user -> getUserHash());
		}else{
			$this -> observer :: addNewLog('chabit', 'No user currently logged in', 3);
		}

	}

	public function setConversationsManager()
	{
		$this -> observer :: addNewLog('chabit', 'Define conversation manager', 3);
		$this -> conversationsManager = new conversationsManager($this -> chatFilesFolder, $this -> user);

		if($this -> user -> isLoggedIn())
		{
			$this -> conversationsManager -> fetchUserPrivateConv($this -> user -> getUserHash());
		}else
		{
			$this -> observer :: addNewLog('chabit', 'User is not logged in, no need to fetch its conversations (TO DO: fetch public conv)', 3);
		}
	}



	/* GETTERS */
	public function getOnlineUsers()
	{
		$this -> observer :: addNewLog('chabit', 'Fetch Online Users', 3);
		return $this -> online_users;
	}

	public function getUser()
	{
		$this -> observer :: addNewLog('chabit', 'Fetch User', 3);
		return $this -> user;
	}


	/* CUSTOM */
	public function updateUserInOnlineUsers()
	{
		$this -> setUser();
		$this -> setOnlineUsers();

		if($this -> user -> isLoggedIn())
		{
			$this -> observer :: addNewLog('chabit', 'Call onlineUserClass to update user "'.$this -> user -> getUserName().'"', 3);
			$this -> online_users -> addOrUpdateUser($this -> user -> getUserName(), $this -> user -> getUserHash());
		}
	}

	public function newPrivateConv($target_hash)
	{
		if($this -> user -> isLoggedIn() && $this -> online_users -> isHashInOnlineUsers($target_hash) && $target_hash != $this -> user -> getUserHash())
		{
			$this -> conversationsManager -> createNewConversation('private', $this -> online_users ->getUserNameFromHash($target_hash), $target_hash);
		}else{
			$this -> observer :: addNewLog('chabit', 'Error opening new conversation.', 3);
		}
	}

	public function PostNexInPrivateConv($target_hash, $message)
	{
		if($this -> user -> isLoggedIn() && $target_hash != $this -> user -> getUserHash())
		{
			$this -> conversationsManager -> postNewMessageInPrivateConv($target_hash, $message);
		}
	}


	public function getStatusJSON()
	{

		$status = [];

		$this -> observer :: addNewLog('chabit', 'Fetch Online Users', 3);
		$this -> setOnlineUsers();
		$status['ONLINE_USERS'] = $this -> online_users -> getOnlineUsers("array");

		/*$this -> observer :: addNewLog('chabit', 'Fetch Channels', 3);
		$status['CHANNELS'] = array();
		foreach($this -> opened_channels as $channel)
		{
			array_push($status['CHANNELS'], $this -> getChannelPosts($channel, 'array'));	
		}
		*/

		$this -> observer :: addNewLog('chabit', 'Fetch PMs', 3);
		if($this -> user -> isLoggedIn()){
			$status['PMS'] = $this ->  conversationsManager -> fetchUserPrivateConvContent();
		}
		else{
			$status['PMS'] = [];
		}
		

		$this -> observer :: addNewLog('chabit', 'Fetch User', 3);
		$status['USER'] = array();
		if($this -> user -> isLoggedIn())
		{
			$status['USER']['USERNAME'] = $this -> user -> getUserName();
			$status['USER']['USERHASH'] = $this -> user -> getUserHash();
		}

		return json_encode($status, JSON_UNESCAPED_SLASHES);
		
	}
}

?>