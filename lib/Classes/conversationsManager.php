<?php

namespace chabit;

class conversationsManager
{
	use tObservedClass;
	use tDatesManager;

	protected $private_conversation_folder = 'Private/';

	protected $user;

	public function __construct($rootFolder, $user)
	{
		$this -> setObserver('conversationManager');

		$this -> setPrivateConversationFolder($rootFolder);
		$this -> setUser($user);
	}

	public function setPrivateConversationFolder($rootFolder)
	{
		$this -> observer :: addNewLog('ConversationManager', 'Define Conv By User file.', 3);
		$this -> private_conversation_folder = $rootFolder.$this -> private_conversation_folder;
	}

	public function setUser($user)
	{
		$this -> user = $user;
	}

	public function fetchUserPrivateConv($userhash)
	{
		$this -> observer :: addNewLog('ConversationManager', 'Fetch private conversation for user '.$userhash, 3);
	
		$searchString = $userhash;
		$files = glob($this -> private_conversation_folder.'*.json');
		$filesFound = array();
		foreach($files as $file) {
		    $name = pathinfo($file, PATHINFO_FILENAME);
		    $this -> observer :: addNewLog('ConversationManager', 'Analyse file '.$name.' check if '.strtolower($name).' contains '.strtolower($searchString), 3);
		    // determines if the search string is in the filename.
		    if(is_int(strpos(strtolower($name), strtolower($searchString)))) {
		    	$target_user_in_private = str_replace('-', '', str_replace($userhash, '', $name));
		        $filesFound[$target_user_in_private] = $file;
		    }
		}
		$this -> observer :: addNewLog('ConversationManager', count($filesFound).' files found.', 3);
		return $filesFound;
	}

	public function fetchUserPrivateConvContent()
	{
		$userhash = $this -> user -> getUserHash();
		$this -> observer :: addNewLog('ConversationManager', 'Fetch private conversation for user '.$userhash, 3);
	
		$searchString = $userhash;
		$files = glob($this -> private_conversation_folder.'*.json');
		$all_pc = array();
		foreach($files as $file) {
		    $name = pathinfo($file, PATHINFO_FILENAME);
		    $this -> observer :: addNewLog('ConversationManager', 'Analyse file '.$name.' check if '.strtolower($name).' contains '.strtolower($searchString), 3);
		    // determines if the search string is in the filename.
		    if(is_int(strpos(strtolower($name), strtolower($searchString)))) {
		    	$this -> observer :: addNewLog('ConversationManager', 'Add content to PM detail', 3);
		    	$file_content = $this -> fetchConversationContent($file);
		    	$target_user_in_private = str_replace('-', '', str_replace($userhash, '', $name));
		    	$all_pc[$target_user_in_private] = $file_content;
		    }
		}
		return $all_pc;
	}

	public function createNewConversation($conv_type, $title, $target_hash = null)
	{
		if($conv_type == 'private' && !array_key_exists($target_hash, $this -> fetchUserPrivateConv($this -> user -> getUserHash())))
		{
			$this -> createPrivateConversation($title, $target_hash);
		}else if($conv_type == 'public')
		{
			$this -> observer :: addNewLog('ConversationManager', 'TO DO: Create public conversation.', 3);
		}else{
			$this -> observer :: addNewLog('ConversationManager', 'Conversation type not recognised.', 3);
		}
	}

	public function createPrivateConversation($target_user_name, $target_hash)
	{
		$first_message = $this -> buildNewMessage(1, 'Welcome on your new private conversation with '.$target_user_name);
		$conv_content = array("TITLE" => $target_user_name, "CREATION_DATE" =>  $this -> getCurrentDate() ->format('Y-m-d H:i:s'), "MESSAGES" => array (1 => $first_message), 'LAST_ID' => 1);

		//create file
		$private_conversation_file = $this -> private_conversation_folder.$this -> user -> getUserHash().'-'.$target_hash.'.json';
		touch($private_conversation_file);

		@file_put_contents($private_conversation_file, json_encode($conv_content));
	}

	public function postNewMessageInPrivateConv($target_hash, $message)
	{
		$user_conversations = $this -> fetchUserPrivateConv($this -> user -> getUserHash());
		if(array_key_exists($target_hash, $user_conversations))
		{
			//fetch conversation content
			$current_conversation_content =  $this -> fetchConversationContent($user_conversations[$target_hash]);
			if(!empty($current_conversation_content))
			{
				//add new message
				$new_message = $this -> buildNewMessage($current_conversation_content['LAST_ID'] + 1 , $message);
				var_dump($new_message);
				$current_conversation_content['MESSAGES'][$current_conversation_content['LAST_ID'] + 1] = $new_message;
				$current_conversation_content['LAST_ID'] += 1;

				//update conversation
				
				file_put_contents($user_conversations[$target_hash], json_encode($current_conversation_content));

				
				$this -> observer :: addNewLog('ConversationManager', 'New message successfully posted.', 3);
			}else
			{
				$this -> observer :: addNewLog('ConversationManager', 'Could not fetch conversation content.', 3);
			}

		}else{
			$this -> observer :: addNewLog('ConversationManager', 'Conversation not found.', 3);
		}
	}

	public function fetchConversationContent($file)
	{
		$this -> observer :: addNewLog('ConversationManager', 'Fetch content of file '.$file, 3);
		$file_content =  @file_get_contents($file);
		if ($file_content === false)
		{
			$this -> observer :: addNewLog('ConversationManager', 'Could not fetch conversation content.', 3);
			return [];
		}else
		{
			$this -> observer :: addNewLog('ConversationManager', 'Return conversation content.', 3);
			return json_decode($file_content, true);
		}
	}

	protected function buildNewMessage($message_id, $message)
	{
		return array('POSTED_DATE' => $this -> getCurrentDate() ->format('Y-m-d H:i:s'), 'POSTED_BY' => $this -> user -> getUserHash(), 'CONTENT' => htmlentities($message), 'READ_BY'=> array());
	}


}

?>