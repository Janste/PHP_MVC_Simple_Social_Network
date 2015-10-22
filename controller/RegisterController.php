<?php

namespace controller;

require_once('view/FacadeView.php');
require_once('model/FacadeModel.php');

class RegisterController {

    private $view;
    private $model;

    // Constructor
    public function __construct(\model\FacadeModel $m, \view\FacadeView $v) {
        $this->view = $v;
        $this->model = $m;
    }

    public function doRegister() {

        if($this->view->getRegisterView()->checkRegisterButtonClicked()) {

            // Get the data from the register form
            $newUsername = $this->view->getRegisterView()->getUserNameToRegister();
            $newPassword = $this->view->getRegisterView()->getPasswordToRegister();
            $repeatedPassword = $this->view->getRegisterView()->getRepeatedPasswordToRegister();

            // Register the new user
            if($this->model->registerNewUser($newUsername, $newPassword, $repeatedPassword)) {

                // If the register operation is successful, then redirect and show proper message
                $this->view->getLoginView()->setNewUserRegistered();
                $this->view->getLoginView()->setUsernameToDisplay($newUsername);
                $this->view->getLoginView()->redirect();

            } else { // Something was wrong with user input during registration

                if ($this->model->getInvalidCharactersFound() == true) {
                    $this->view->getRegisterView()->setInvalidCharactersFound();
                } elseif ($this->model->getUserAlreadyExists() == true) {
                    $this->view->getRegisterView()->setUserAlreadyExists();
                }

                $this->view->getRegisterView()->redirect();
            }
        }
    }
}