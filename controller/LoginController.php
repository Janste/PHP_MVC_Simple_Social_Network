<?php

namespace controller;

require_once('view/GeneralView.php');
require_once('model/FacadeModel.php');
require_once('controller/PageController.php');

class LoginController {

    private $view;
    private $model;

    private $page;

    // Constructor
    public function __construct(\model\FacadeModel $m, \view\GeneralView $v) {
        $this->view = $v;
        $this->model = $m;
    }

    public function doLogin() {

        // Get information about the user
        $userClient = $this->view->getUserClient();

        // Check if user is logged in
        if ($this->model->isUserLoggedIn($userClient)) {

            // Create view for logged in page
            $this->view->getLoginView()->setUserLoggedIn();

            // Check if user clicked on log out button
            if ($this->view->getLoginView()->checkLogoutButtonClicked()) {

                // Logging out user, display proper view
                $this->model->logoutUser();
                $this->view->getLoginView()->setUserLogoutSucceed();
                $this->view->getLoginView()->redirect();

            } else {

                $this->page = new PageController($this->model, $this->view);

                $this->page->doPageControl();
            }

        } else { // User not logged in

            // Check if log in button clicked
            if ($this->view->getLoginView()->checkLogInButtonClicked()) {

                // Get username and password from the form in view
                $username = $this->view->getLoginView()->getUserName();
                $password = $this->view->getLoginView()->getPassword();

                // Authenticate user credentials
                if ($this->model->loginUser($username, $password, $userClient)) {

                    // User credentials correct, set up proper view
                    $this->view->getLoginView()->setUserLoggedIn();
                    $this->view->getLoginView()->setLoginSucceeded();
                    $this->view->getLoginView()->redirect();


                } else { // User credentials incorrect

                    // Set up view with error message
                    $this->view->getLoginView()->setLoginFailed();
                    $this->view->getLoginView()->redirect();

                }
            }
        }
    }
}