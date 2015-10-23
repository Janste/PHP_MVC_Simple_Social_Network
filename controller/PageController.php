<?php

namespace controller;

require_once('view/FacadeView.php');
require_once('model/FacadeModel.php');

class PageController {

    private $view;
    private $model;

    // Constructor
    public function __construct(\model\FacadeModel $m, \view\FacadeView $v) {
        $this->view = $v;
        $this->model = $m;
    }

    public function doPageControl() {

        $this->view->setCurrentUser($this->model->getCurrentlyLoggedInUser());
        $this->view->setCurrentListOfUsers($this->model->getAllUsers());

        $this->view->setCurrentFollowees($this->model->getFollowees($this->model->getCurrentlyLoggedInUser()->getUsername()));

        if($this->view->getProfileView()->checkSaveChangesButtonClicked()) {

            $firstName = $this->view->getProfileView()->getFirstName();
            $lastName = $this->view->getProfileView()->getLastName();
            $email = $this->view->getProfileView()->getEmailAddress();
            $password = $this->view->getProfileView()->getNewPassword();
            $passwordRepeat = $this->view->getProfileView()->getNewRepeatedPassword();

            $this->view->getProfileView()->redirect($this->model->updateUserData($firstName, $lastName, $email, $password, $passwordRepeat));

        }
        elseif ($this->view->getOtherUsersView()->checkFollowButtonClicked()) {

            $followerUsername = $this->model->getCurrentlyLoggedInUser()->getUsername();
            $followeeUsername = $this->view->getOtherUsersView()->getOtherUserData();

            $this->model->addFollower($followerUsername, $followeeUsername);

            $this->view->getOtherUsersView()->redirect($followeeUsername);

        }
        elseif($this->view->getOtherUsersView()->checkStopFollowingButtonClicked()) {

            $followerUsername = $this->model->getCurrentlyLoggedInUser()->getUsername();
            $followeeUsername = $this->view->getOtherUsersView()->getOtherUserData();

            $this->model->removeFollowee($followerUsername, $followeeUsername);

            $this->view->getOtherUsersView()->redirect($followeeUsername);

        }




    }

}