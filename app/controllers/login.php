<?php

class Login extends Controller {

    public function index(): void {		
	    $this->view('login/index');
    }
    
    public function verify(): void{
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

	public function create(): void
	{
			$u = trim($_POST['username'] ?? '');
			$p = $_POST['password'] ?? '';

			$user   = new User();
			$result = $user->create($u, $p);

			if ($result === true) {
					header('Location: /login?new=1');
					exit;
			}
			
			$_SESSION['error'] = $result;
			header('Location: /login');
			exit;
	}
}





 
