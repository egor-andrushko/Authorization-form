<?php   
	error_reporting(E_ALL ^ E_NOTICE);
	$is_emty = false;
	foreach ($_POST as $key => $value) {
		if (!empty($value)){
			$user[$key] = htmlentities(trim($value));
		}
		else{
			$is_emty = true;
			break;
		}
	}

	if ($is_emty){
		MakeResponse('error',"Заполните все поля!<br>");
	}
	elseif ($user['pass'] !== $user['confirm_pass']){
		MakeResponse('error',"Введённые пароли не совпадают!<br>");
	}
	else{
		unset($user['confirm_pass']);
		AddUser($user);
		MakeResponse('success',"Hello $user[name]<br>");
	}

	function MakeResponse($status,$msg)
	{
		$Response = [
	  	'status' => $status,
	    'msg'    => $msg
  	];
  	echo json_encode($Response);
	}

	function AddUser($data)
	{
		$data['salt'] = GenerateSalt();
		$data['pass'] = md5($data['salt'] . $data['pass']);
		$users=simplexml_load_file('data.xml');
		$new_user = $users->addChild('user');
		foreach ($data as $key => $value) {
			$new_user->addChild($key, $value);
		}
		$users->asXML('data.xml');
	}

	function GenerateSalt(){
		$salt = '';
		$length = rand(5,10);//Длина соли
		for ($i=0; $i <$length ; $i++) { 
			$salt .= chr(rand(33,126));
		}
		return $salt;
	}