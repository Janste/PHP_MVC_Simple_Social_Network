<?php

namespace view;

require_once('GeneralView.php');

class FacadeView {

    private $view;
    private $lv;
    private $rv;
    private $dtv;
    private $pv;
    private $ulv;

    public function __construct() {
        $this->lv = new \view\LoginView();
        $this->rv = new \view\RegisterView();
        $this->dtv = new \view\DateTimeView();
        $this->pv = new \view\ProfileView();
        $this->ulv = new \view\UsersListView();

        $this->view = new \view\GeneralView($this->lv, $this->rv, $this->dtv, $this->pv, $this->ulv);
    }

    public function getLoginView() {
        return $this->lv;
    }

    public function getRegisterView() {
        return $this->rv;
    }

    public function getProfileView() {
        return $this->pv;
    }

    public function showDatabaseErrorMessage() {
        $this->view->showDatabaseErrorMessage();
    }

    public function getUserClient() {
        return $this->view->getUserClient();
    }

    public function render($isLoggedIn) {
        $this->view->render($isLoggedIn);
    }

    public function isOnRegisterPage() {
        return $this->view->isOnRegisterPage();
    }

    public function setCurrentUser(\model\User $loggedInUser) {
        $this->pv->setUser($loggedInUser);
        $this->ulv->setUser($loggedInUser);
    }

    public function setCurrentListOfUsers ($users) {
        $this->ulv->setCurrentListOfUsers($users);
    }

}