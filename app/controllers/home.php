<?php

class Home extends Controller {

  public function index(): void
  {
      if (!isset($_SESSION['user'])) {
          header('Location: /login');
          exit;
      }
      $this->view('home/index', ['user' => $_SESSION['user']]);
  }
  }

}
