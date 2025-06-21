<?php

class Login extends Controller {

    public function index() {		
	    $this->view('login/index');
    }
    
    public function verify(){
			$username = $_REQUEST['username'];
			$password = $_REQUEST['password'];
		
			$user = $this->model('User');
			$user->authenticate($username, $password); 
    }

		public function auth()	{
			$u = trim($_POST['username']??'');
			$p = $_POST['password']??'';

			$log  = new Log;
			$user = new User;

			if($log->tooManyBad($u)){
					$this->view('login/index',['error'=>'Locked out 60 s after 3 fails.']); return;
			}

			if($user->verify($u,$p)){
					$log->record($u,'good');
					$_SESSION['user']=ucwords($u);
					header('Location: /home'); exit;
			}

			$log->record($u,'bad');
			$this->view('login/index',['error'=>'Invalid credentials.']);
	}

	 public function create() { /* step 5 */ }}

}

 }<br>
