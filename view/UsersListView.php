<?php

namespace view;

class UsersListView {

    private $mainUser;
    private $allUsers = array();

    private static $showProfile = 'show_profile';
    private static $keyCharacter = '=';

    // TODO: Check that this is a user array that is coming in

    public function setCurrentListOfUsers($usersArray) {
        $this->allUsers = $usersArray;
    }

    public function setUser(\model\User $loggedInUser) {

        $this->mainUser = $loggedInUser;

    }

    public function showListOfAllUsers() {

        $resultString = '';

        foreach($this->allUsers as $oneUser) {

            if($oneUser == $this->mainUser) {
                continue;
            }

            $resultString .= '<fieldset>';
            $resultString .= '<p>' . 'Username: ' . $oneUser->getUsername() . '</p>';
            $resultString .= '<p>' . $oneUser->getFirstName() . ' ' . $oneUser->getLastName() . '</p>';
            $resultString .= '<p>' . $oneUser->getEmailAddress() . '</p>';

            $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

            $resultString .= '<a href="?' . self::$showProfile . self::$keyCharacter . $oneUser->getUsername() . '">Show profile</a>';
            $resultString .= '</fieldset>';

        }

        return $resultString;

    }

    public function isOnViewAnotherUserProfilePage() {
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if (preg_match('/' . self::$showProfile . '/', $url) !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function showSpecifiedUser() {

        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $username = substr($url, strpos($url, self::$keyCharacter) + 1);
        $resultString = '';

        foreach($this->allUsers as $oneUser) {

            if($oneUser->getUsername() === $username) {
                $resultString .= '<fieldset>';
                $resultString .= '<p>' . 'Username: ' . $oneUser->getUsername() . '</p>';
                $resultString .= '<p>' . $oneUser->getFirstName() . ' ' . $oneUser->getLastName() . '</p>';
                $resultString .= '<p>' . $oneUser->getEmailAddress() . '</p>';
                $resultString .= '</fieldset>';
                return $resultString;
            }
        }
        return 'User not found.';
    }

}