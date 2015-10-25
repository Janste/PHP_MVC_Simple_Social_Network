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

        // Get information from model about the situation (who is logged in, etc.) and send that information to the view
        $this->view->setCurrentUser($this->model->getCurrentlyLoggedInUser());
        $this->view->setCurrentListOfUsers($this->model->getAllUsers());
        $this->view->setCurrentFollowees($this->model->getFollowees());
        $this->view->setStatusList($this->model->getStatusArray());

        // If save profile changes button clicked
        if($this->view->getProfileView()->checkSaveChangesButtonClicked()) {

            // Get all data from the form
            $firstName = $this->view->getProfileView()->getFirstName();
            $lastName = $this->view->getProfileView()->getLastName();
            $email = $this->view->getProfileView()->getEmailAddress();
            $description = $this->view->getProfileView()->getDescription();
            $password = $this->view->getProfileView()->getNewPassword();
            $passwordRepeat = $this->view->getProfileView()->getNewRepeatedPassword();

            // Update form and display result
            $this->view->getProfileView()->redirect($this->model->updateUserData($firstName, $lastName, $email, $description, $password, $passwordRepeat));

        }
        // If follow another user button clicked
        elseif ($this->view->getOtherUsersView()->checkFollowButtonClicked()) {

            $followeeUsername = $this->view->getOtherUsersView()->getOtherUserData();

            $this->model->addFollower($followeeUsername);

            $this->view->getOtherUsersView()->redirect($followeeUsername);

        }
        // If stop following another user button clicked
        elseif($this->view->getOtherUsersView()->checkStopFollowingButtonClicked()) {

            $followeeUsername = $this->view->getOtherUsersView()->getOtherUserData();

            $this->model->removeFollowee($followeeUsername);

            $this->view->getOtherUsersView()->redirect($followeeUsername);

        }
        // If submit status button clicked
        elseif($this->view->getStatusView()->checkIfSubmitStatusButtonClicked()) {

            $this->view->getStatusView()->redirect($this->model->addNewStatus($this->view->getStatusView()->getNewStatus()));

        }
    }
}