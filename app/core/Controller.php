<?php

class Controller {

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function model($model)
    {
        require_once 'app/models/' . $model . '.php';
        return new $model();
    }

    protected function view(string $path, array $data = []): void
    {
        $file = __DIR__ . '/../views/' . $path . '.php';
        if (file_exists($file)) {
            extract($data);
            include $file;
        } else {
            echo "View not found: {$path}";
        }
    }

}
