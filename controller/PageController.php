<?php

namespace controller;

require_once('view/GeneralView.php');
require_once('model/FacadeModel.php');

class PageController {

    private $view;
    private $model;

    // Constructor
    public function __construct(\model\FacadeModel $m, \view\GeneralView $v) {
        $this->view = $v;
        $this->model = $m;
    }

    public function doPageControl() {

        $this->view->getProfileView()->setUser($this->model->getCurrentlyLoggedInUser());

        if($this->view->getProfileView()->checkSaveChangesButtonClicked()) {

            $firstName = $this->view->getProfileView()->getFirstName();
            $lastName = $this->view->getProfileView()->getLastName();
            $email = $this->view->getProfileView()->getEmailAddress();
            $password = $this->view->getProfileView()->getNewPassword();
            $passwordRepeat = $this->view->getProfileView()->getNewRepeatedPassword();

            $this->model->updateUserData($firstName, $lastName, $email, $password, $passwordRepeat);

        }



        // TODO: Check is save changes button clicked and react

    }

}