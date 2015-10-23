<?php

namespace view;

require_once('GeneralView.php');

/**
 * Class FacadeView
 * This is a facade to the view classes
 * @package view
 */
class FacadeView {

    private $view;
    private $lv;
    private $rv;
    private $dtv;
    private $pv;
    private $ouv;

    public function __construct() {
        $this->lv = new \view\LoginView();
        $this->rv = new \view\RegisterView();
        $this->dtv = new \view\DateTimeView();
        $this->pv = new \view\ProfileView();
        $this->ouv = new \view\OtherUsersView();

        $this->view = new \view\GeneralView($this->lv, $this->rv, $this->dtv, $this->pv, $this->ouv);
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

    public function getOtherUsersView() {
        return $this->ouv;
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
        $this->ouv->setUser($loggedInUser);
    }

    public function setCurrentListOfUsers ($users) {
        $this->ouv->setCurrentListOfUsers($users);
    }

    public function setCurrentFollowees($followees) {
        $this->ouv->setFollowees($followees);
    }

}