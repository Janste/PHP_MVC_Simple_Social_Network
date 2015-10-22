<?php

namespace view;

class FacadeView {

    private $view;

    public function __construct() {
        $this->view = new \view\GeneralView();
    }

    public function getLoginView() {
        return $this->view->getLoginView();
    }

    public function getRegisterView() {
        return $this->view->getRegisterView();
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

}