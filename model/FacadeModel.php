<?php

namespace model;

require_once("Authentication.php");
require_once("UserClient.php");
require_once("Profile.php");

class FacadeModel {

    private $authentication;
    private $profile;

    public function __construct() {
        $this->authentication = new \model\Authentication();
    }

    public function initialize() {
        if ($this->authentication->initialize()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get a bool value indicating if there were invalid characters in user's input.
     */
    public function getInvalidCharactersFound() {
        return $this->authentication->getInvalidCharactersFound();
    }

    /**
     * Get a bool value indicating if the given username already exists
     */
    public function getUserAlreadyExists() {
        return $this->authentication->getUserAlreadyExists();
    }

    public function loginUser($username, $password, UserClient $userClient) {
        return $this->authentication->login($username, $password, $userClient);
    }

    public function isUserLoggedIn(UserClient $userClient) {
        return $this->authentication->isLoggedIn($userClient);
    }

    public function logoutUser() {
        return $this->authentication->doLogout();
    }

    public function registerNewUser($username, $password, $repeatedPassword) {
        return $this->authentication->register($username, $password, $repeatedPassword);
    }

    public function getCurrentlyLoggedInUser() {
        return $this->authentication->getCurrentUser();
    }

    public function updateUserData($firstName, $lastName, $emailAddress, $newPassword, $repeatNewPassword) {

        $this->profile = new \model\Profile($this->getCurrentlyLoggedInUser());

        $this->profile->updateUser($firstName, $lastName, $emailAddress, $newPassword, $repeatNewPassword);

    }
}