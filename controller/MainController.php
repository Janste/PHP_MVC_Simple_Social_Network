<?php

namespace controller;

require_once("RegisterController.php");
require_once("LoginController.php");

class MainController {

    private $view;
    private $model;

    // Constructor
    public function __construct(\model\FacadeModel $m, \view\GeneralView $v)
    {
        $this->view = $v;
        $this->model = $m;
    }

    public function run() {

        // This method initializes the model (connection to db, etc.)
        if (!$this->model->initialize()) {
            $this->view->showDatabaseErrorMessage(); // If an error with the DB occurred, show error message
        }

        if($this->view->isOnRegisterPage()) {

            $register = new RegisterController($this->model, $this->view);

            $register->doRegister();

        } else {

            $login = new LoginController($this->model, $this->view);

            $login->doLogin();

        }

    }
}