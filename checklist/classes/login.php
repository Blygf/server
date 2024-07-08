<?php 

class Login 
{
	private $error = "";

	public function evaluate($data)
	{
	    $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
	    $password = filter_var($data['password'], FILTER_SANITIZE_STRING);

	    $query = "select * from users where email = '$email' limit 1 ";

	    $DB = new Database();
	    $result = $DB->read($query);

	    if ($result) {
	        $row = $result[0];
	        if (password_verify($password, $row['password_hash'])) {
	            if (session_status() == PHP_SESSION_NONE) {
	                session_start();
	            }
	            $new_session_token = $this->generate_number();
	            $_SESSION['session_token'] = $new_session_token;
	            $expiry = date('Y-m-d H:i:s', strtotime('+7 days'));
	            $query = "insert into sessions (userid, expiry, token) values ('$row[userid]', '$expiry', '$new_session_token')";

	            $DB->save($query);
	        } else {
	            $this->error .= "Password incorrect ";
	        }
	    } else {
	        // Check if email is valid
	        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email) || strstr($email, " ")) {
	            $this->error .= "Invalid email address!<br>";
	        }

	        // Check if the domain has MX records
	        $domain = substr(strrchr($email, "@"), 1);
	        if (!checkdnsrr($domain, "MX")) {
	            $this->error .= "The domain does not have MX records -> fake email detected";
	        }

	        // Check if email is malicious
	        if (filter_var($email, FILTER_SANITIZE_EMAIL) == '') {
	            $this->error .= "Malicious email, please change it.";
	        }

	        // If no errors, create user
	        if ($this->error == "") {
	            $this->create_user($email, $password);
	        } else {
	            return $this->error;
	        }
	    }

	    if ($this->error == "") {
	        return "";
	    } else {
	        return $this->error;
	    }
	}
	private function generate_number($length = 11)
	{
	    $token = bin2hex(random_bytes($length));

	    $query = "SELECT * FROM sessions WHERE token = '$token' LIMIT 1";
	    $DB = new Database();
	    $result = $DB->read($query);

	    // Check if the token already exists
	    if ($result) {
	        // If token exists, recursively generate a new one
	        return $this->generate_number($length);
	    } else {
	        // If token does not exist, return it
	        return $token;
	    }
	}

	public function create_user($email, $password)
	{
		
		$name = filter_var(strstr($email, '@', true), FILTER_SANITIZE_STRING);
		$name = ucfirst($name);
		$password_hash = password_hash($password, PASSWORD_ARGON2ID);
		
		$query = "insert into users (email,name,password_hash) values ('$email','$name','$password_hash')";
		$DB = new Database();
		$DB->save($query);


		$query = "select userid from users where email = '$email' limit 1";
		$DB = new Database();
		$userid = $DB->read($query)[0]['userid'];
		
		$new_session_token = $this->generate_number();

        $_SESSION['session_token'] = $new_session_token;
        $expiry = date('Y-m-d H:i:s', strtotime('+7 days'));
        $query = "insert into sessions (userid, expiry, token) values ('$userid', '$expiry', '$new_session_token')";

        $DB->save($query);
	}




	public function check_login($token)
	{
		if(!empty($token))
		{

			$query = "select * from sessions where token = '$token' limit 1";

			$DB = new Database();
			$result = $DB->read($query);

			if($result)
			{
				$user_data = $result[0];
				return $user_data;

			}else
			{	
				if (session_status() == PHP_SESSION_NONE) {
	                session_start();
	            }
				unset($_SESSION['session_token']);
				header("Location: login.php");
		        die;
	    	}	
			//check if user is logged in
			   
	    }else
	    {	
	    	if (session_status() == PHP_SESSION_NONE) {
	            session_start();
	        }
			unset($_SESSION['session_token']);
	        header("Location: login.php");
	        die;
	    }

	}

}





 ?>