<?php

namespace model;

require_once("Authentication.php");
require_once("UserClient.php");
require_once("Profile.php");
require_once('Followers.php');
require_once('StatusHandler.php');

/**
 * Class FacadeModel
 * This is a facade to the model classes
 * @package model
 */
class FacadeModel {

    private $authentication;
    private $profile;
    private $followers;
    private $status;

    public function __construct() {
        $this->authentication = new \model\Authentication();
        $this->followers = new \model\Followers();
        $this->status = new \model\StatusHandler();
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

    public function getAllUsers() {
        return $this->authentication->getAllUsersArray();
    }

    public function updateUserData($firstName, $lastName, $emailAddress, $description, $newPassword, $repeatNewPassword) {

        $this->profile = new \model\Profile($this->getCurrentlyLoggedInUser());

        return $this->profile->updateUser($firstName, $lastName, $emailAddress, $description, $newPassword, $repeatNewPassword);

    }

    public function addFollower($followee) {
        return $this->followers->addFollower($this->getCurrentlyLoggedInUser(), $followee);
    }

    public function getFollowees() {
        return $this->followers->getFollowees($this->getCurrentlyLoggedInUser(), $this->getAllUsers());
    }

    public function removeFollowee($followee) {
        return $this->followers->removeFollowee($this->getCurrentlyLoggedInUser(), $followee);
    }

    public function addNewStatus($content) {
        return $this->status->addNewStatus($this->getCurrentlyLoggedInUser(), $content);
    }

    public function getStatusArray() {
        return $this->status->getStatusArray($this->getCurrentlyLoggedInUser(), $this->getAllUsers());
    }
}