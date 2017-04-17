class chabit
{

	constructor()
	{
		this.user = new user(this);

		this.chatrooms = [];
		this.pms = [];

		//build main structure from template
		this.buildMainFromTemplate();
		this.updateChat();

		
	}

	getUser()
	{
		return this.user;
	}

	buildMainFromTemplate()
	{
		console.log('Create Online Users/chatroom list: get init');
		getFileResponse('GET', 'lib/Templates/main.xml', this, this.buildMainFromTemplateFromTemplate, this.buildMainFromTemplateFromTemplate);
	}

	buildMainFromTemplateFromTemplate(html_template)
	{
		console.log('HTML Template Found, inject it in <chat></chat>');

		var chat_tag = document.getElementById('chabit');
		chat_tag.innerHTML = html_template;
	}

	updateChat()
	{
		console.log('Update chat');
		getFileResponse('GET', 'lib/status.php', this, this.updateChatValues, this.setChatToConnexionError);
	}

	updateChatValues(response)
	{
	
		console.log(response);
		var chatStatus = JSON.parse(response);
		//console.log(chatStatus);

		console.log('Update Online user list');
		this.online_users = new onlineUsers(chatStatus['ONLINE_USERS'], this);

		console.log('Update Login box');
		this.user.updateLoginBox(chatStatus['USER']);

		this.updatePMS(chatStatus['PMS'], chatStatus['ONLINE_USERS']);
		
		this.updateDOM();
	}

	openPM(event)
	{
		var target_userhash = event.target.getAttribute("hash");
		var target_username = event.target.getAttribute("username");

		this.pms.push({"HASH": target_userhash, "NAME": target_username, "OPENED": false});

		if(target_userhash != this.user.hash)
		{
			//create new conversation object & create conversation on server
			var pm = new private_conversation(this, target_userhash);
			pm.createNewConversation();

			//add PM to list of object PM
			this.pms.push(pm);
		}
		else{
			console.log('Cannot open a conversation with yourself');
		}
	}



	updatePMS(pms_details)
	{
		//console.log(pms_details);
		
		this.pms = [];
		for(var target_userhash in pms_details)
		{
			console.log('Analyse PM conversation with '+target_userhash);
			if(this.online_users.userIsOnline(target_userhash))
			{
				var auto_open_conv = true;
			}else{
				var auto_open_conv = false;
			}
			var pm = new private_conversation(this, target_userhash, pms_details[target_userhash]['MESSAGES'], auto_open_conv);

			this.pms.push(pm);
		}

		//if no opened pms, delete all opened pms chatbox
		if(this.pms.length == 0)
		{
			document.getElementById('chabit_pms').innerHTML = '';
		}
	}


	updateDOM()
	{
		var minise_buttons = document.getElementsByClassName('minimise');
		for (var i = 0; i < minise_buttons.length; i++)
		{
			//add minimise listener to click
			minise_buttons[i].addEventListener('click', this.minimiseChatbox, false);
		}
	}

	minimiseChatbox(event)
	{
		console.log('Minimise chatbox');
		var target_chatbox_name = event.target.getAttribute("target");
		console.log(target_chatbox_name);
		var target_chatbox_element = document.getElementById(target_chatbox_name);

		toggleVisibility(target_chatbox_element);
	}

	setChatToConnexionError(response)
	{
		console.log('Error fetching data')
	}

}

class onlineUsers
{

	constructor(online_users, controller)
	{
		var user_list_html = "";
		for(var userhash in online_users)
		{
			var user_class = "";
			var username = online_users[userhash]['USERNAME'];

			var user_inactive_for_mn = timeTillNow(online_users[userhash]['LAST_UPDATE']);
			console.log('Add user '+username+' last active '+user_inactive_for_mn+'mn ago.');

			var inactive_time_html = '';
			if (user_inactive_for_mn < 3)
			{
				user_class = "active";
				
			}else if(user_inactive_for_mn < 7)
			{
				user_class = "afk";
				inactive_time_html = ' ('+user_inactive_for_mn+'mn )';
			}else{
				user_class = "inactive";
				inactive_time_html = ' ('+user_inactive_for_mn+'mn )';
			}

			user_list_html += '<li class="chabit_username '+user_class+'" id="user_'+userhash+'" username="'+username+'" hash="'+userhash+'">'+username+inactive_time_html+'</li>';
		}

		var user_list_ul = document.getElementsByClassName("user_list")[0];
		user_list_ul.innerHTML = user_list_html;
		findAncestorByTagName (user_list_ul, 'div').classList.remove("disabled");

		//add liseners to userlist (to open pm)
		var usernames = document.getElementsByClassName('chabit_username');
		for(var i = 0; i < usernames.length; i++)
		{
			controller.openPM = controller.openPM.bind(controller);
			usernames[i].addEventListener('click', controller.openPM, false);
		}
	}

	userIsOnline(hash)
	{
		var user_ref = document.getElementById('user_'+hash);
		if(user_ref != null)
		{
			return true;
		}else
		{
			return false;
		}
	}

}

class user
{
	constructor(controller)
	{
		this.controller = controller;
		this.username = '';
		this.magic_pass = '';
		this.hash = '';
	}

	reset()
	{
		this.username = '';
		this.magic_pass = '';
		this.hash = '';
	}

	setUsername(username)
	{
		this.username = username;
	}

	setUserMagicPass(magic_pass)
	{
		this.magin_pass = magic_pass;
	}

	setUserhash(hash)
	{
		this.hash = hash;
	}

	getUsername()
	{
		return this.username;
	}

	getUserhash()
	{
		return this.hash;
	}

	getUserMagicPass()
	{
		return this.magic_pass;
	}

	updateLoginBox(user_status)
	{

		console.log('Update Login box');
		console.log(user_status);
		var loginbox = document.getElementById('chabit_loginbox');
		if(typeof user_status['USERNAME'] !== 'undefined' && typeof user_status['USERHASH'] !== 'undefined')
		{
			//update user object
			this.setUsername(user_status['USERNAME']);
			this.setUserhash(user_status['USERHASH']);

			console.log('Display Username and logout button');
			loginbox.innerHTML = this.getUsername()+'<br /><button id="logout_btn">Logout</button>';
			
			//add listener to logout buton
			this.logout = this.logout.bind(this);
			document.getElementById('logout_btn').addEventListener('click',  this.logout, false);
		}else
		{
			console.log('Display Login form');
			//get current value
			var current_input_login = document.getElementById('input_login');
			var current_magic_pass = document.getElementById('input_pass');
			
			this.reset();
			if (current_input_login === null)
			{
				loginbox.innerHTML = 'Login : <input type="text" id="input_login" /><br />'
								+'Magic Pass: <input type="text" id="input_pass" /><br />'
								+'<button id="login_btn">Login</button>';

				//add listener to login button
				this.login = this.login.bind(this);
				document.getElementById('login_btn').addEventListener('click', this.login, false);
			}
			
		}
	}

	login()
	{
		console.log('Login');
		var username = document.getElementById('input_login').value;
		var magic_pass = document.getElementById('input_pass').value;


		if(username.length > 3)
		{

			getFileResponse('GET', 'lib/login.php?username='+username+'&usermagicpass='+magic_pass, this.controller, this.controller.updateChat, this.setChatToConnexionError);
		}
	}

	logout()
	{
		console.log('Logout ');
		
		getFileResponse('GET', 'lib/logout.php', this.controller, this.controller.updateChat, this.setChatToConnexionError);
	}

}

class conversation
{

	getPMChatroomTemplate()
	{
		getFileResponse('GET', 'lib/Templates/chatroom.xml', this, this.buildPMChatRoom, this.setChatToConnexionError);
	}
}

class private_conversation extends conversation
{
	constructor(controller, target_userhash, messages = [], auto_open_conv = true)
	{
		super();
		this.controller = controller;
		this.target_userhash = target_userhash;
		this.target_username;
		this.setName;

		this.elementid = 'chabit_chatroom_'+this.target_userhash;

		this.new_messages = messages;
		console.log('Check if necessary to open conversation window for PM with '+this.target_userhash);
		if(auto_open_conv && !this.isPMConversationOpen())
		{
			console.log('Auto open conversation');
			this.getPMChatroomTemplate();
		}else if(this.isPMConversationOpen()){

			this.addMessages(this.new_messages);
		}

	}

	createNewConversation()
	{
		getFileResponse('GET', 'lib/post_new_conv.php?userhash='+this.target_userhash, this, null, null, false, false); 
	}

	isPMConversationOpen()
	{
		var chatroom_id = document.getElementById(this.elementid);
		if(chatroom_id != null)
		{
			console.log('Conversation is already opened');
			return true;
		}else
		{
			console.log('Conversation is not opened');
			return false;
		}
	}

	buildPMChatRoom(html_template)
	{
		console.log('Build PM Chatroom');
		var PMChatroomsTag = document.getElementById('chabit_pms');
		
		console.log('replace TARGET_HASH by '+this.target_userhash);
		html_template = html_template.replace(/TARGET_HASH/g, this.target_userhash);
		if(this.isTargetUserOnline())
		{
			console.log('replace TARGET_NAME by '+this.target_username);
			html_template = html_template.replace(/TARGET_NAME/g, this.target_username);
		}else{
			console.log('replace TARGET_NAME by '+this.target_userhash);
			html_template = html_template.replace(/TARGET_NAME/g, this.target_userhash);
		}


		PMChatroomsTag.innerHTML += html_template;

		//add listener 
		this.postToPM = this.postToPM.bind(this);
		document.getElementById('post_to_pm_'+this.target_userhash).addEventListener('keydown', this.postToPM, false);

		this.addMessages(this.new_messages);
	}

	addMessages(messages)
	{
		console.log('Add '+Object.keys(messages).length+' new messages to conversation');

		console.log(typeof(messages));
		//messages = sortJSON(messages, 'CREATION_DATE', 321);
		//messages = messages.sort(sort_by('CREATION_DATE', true));

		var messages_html = '';
		for(var message in messages)
		{
			console.log(message+' : '+messages[message]['POSTED_DATE']+' : '+messages[message]['CONTENT']);
			var author = messages[message]['POSTED_BY'];
			var extra_msg_class = '';
			if(author == this.controller.getUser().getUserhash())
			{
				var extra_msg_class = ' user-message';
			}

			messages_html += '<div class="chat-message'+extra_msg_class+'">'+messages[message]['CONTENT']+'</div>';
		}
		messages_html = '<div class="chat-messages">'+messages_html+'</div>';

		var chatbox = document.getElementById(this.elementid).getElementsByClassName('subchatboxcontent')[0];
		chatbox.innerHTML = messages_html;
		chatbox.scrollTop = chatbox.scrollHeight;
	}

	postToPM(event)
	{
		  var pm_with = event.target.getAttribute("target");
		  console.log('New input '+event.target.value+' in Pm conversation with ' + pm_with);

		  if (event.which == 13 || event.keyCode == 13) {
		      console.log('Enter key pressed: Post '+event.target.value+' in Pm conversation with ' + pm_with);
		      getFileResponse('GET', 'lib/post_new_pm.php?target_userhash='+this.target_userhash+'&message='+event.target.value, this.controller, this.controller.updateChat, this.controller.setChatToConnexionError);
		      event.target.value = ''; 
		  }
	}

	isTargetUserOnline()
	{
		var target_user_in_online_list = document.getElementById('user_'+this.target_userhash);
		
		if(target_user_in_online_list != null)
		{
			this.target_username = target_user_in_online_list.getAttribute('username');
			return true;
			//target_user_in_online_list.className += ' alert';
		}else 
		{
			return false;
		}
	}

	getPMHash()
	{
		return this.target_userhash;
	}
			
}
