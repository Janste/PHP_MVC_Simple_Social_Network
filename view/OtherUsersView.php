<?php

namespace view;


/**
 * Class OtherUsersView
 * This class is responsible for displaying other users (other than the one that is currently logged in)
 * @package view
 */
class OtherUsersView {

    private $mainUser;
    private $allUsers = array();
    private $followees = array();

    private static $showProfile = 'profile';
    private static $keyCharacter = '=';

    private static $userToBeFollowed = 'OtherUsersView::FollowUser';
    private static $followUserButton = 'OtherUsersView::FollowUserButton';
    private static $stopFollowingUserButton = 'OtherUsersView::StopFollowingUserButton';

    /**
     * Sets the list of all users that are registered on this website
     * @param $usersArray
     */
    public function setCurrentListOfUsers($usersArray) {
        $this->allUsers = $usersArray;
    }

    /**
     * Sets the user which is currently logged in
     * @param \model\User $loggedInUser
     */
    public function setUser(\model\User $loggedInUser) {
        $this->mainUser = $loggedInUser;
    }

    /**
     * Sets the followees for the user that is currently logged in
     * @param $followeesArray
     */
    public function setFollowees($followeesArray) {
        $this->followees = $followeesArray;
    }

    /**
     * Shows a list of all users registered on this website
     * @return string
     */
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
            $resultString .= '<a href="?' . self::$showProfile . self::$keyCharacter . $oneUser->getUsername() . '">Show profile</a>';
            $resultString .= '</fieldset>';

        }

        return $resultString;

    }

    /**
     * Shows profile for the earlier specified user
     * @return string
     */
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

                $resultString .= $this->displayFollowStopFollowButton($username);

                $resultString .= '</fieldset>';
                return $resultString;
            }
        }
        return 'User not found.';
    }

    /**
     * Displays either follow or stop following button. This depends on who is currently logged in and if the current
     * user actually follows this other user.
     * @param $username
     * @return string
     */
    public function displayFollowStopFollowButton($username) {

        $returnStr = '';

        $bool = false;

        foreach ($this->followees as $followee) {

            if($username === $followee->getUsername()) {
                $bool = true;
            }

        }


        if($bool) {

            $returnStr .= '<form method="post" >';
            $returnStr .= '<input type="hidden" name="' . self::$userToBeFollowed . '" value="' . $username . '" />';
            $returnStr .= '<input type="submit" name="' . self::$stopFollowingUserButton . '" value="Stop following this user" />';
            $returnStr .= '</form>';

        } else {

            $returnStr .= '<form method="post" >';
            $returnStr .= '<input type="hidden" name="' . self::$userToBeFollowed . '" value="' . $username . '" />';
            $returnStr .= '<input type="submit" name="' . self::$followUserButton . '" value="Follow this user" />';
            $returnStr .= '</form>';

        }

        return $returnStr;

    }

    /**
     * Checks if follow button was clicked
     * @return bool
     */
    public function checkFollowButtonClicked() {
        if(isset($_POST[self::$followUserButton])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if stop following button was clicked
     * @return bool
     */
    public function checkStopFollowingButtonClicked() {
        if(isset($_POST[self::$stopFollowingUserButton])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the username of the other user that is being displayed for the currently logged in user
     * @return mixed
     */
    public function getOtherUserData() {
        return $_POST[self::$userToBeFollowed];
    }

    /**
     * Redirects to the same page
     * @param $username
     */
    public function redirect($username) {

        $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

        header("HTTP/1.1 302 Found");
        header("Location: $actual_link?" . self::$showProfile . self::$keyCharacter . $username);

    }

    /**
     * Checks if user is on a webpage where he can see profiles of other users
     * @return bool
     */
    public function isOnViewAnotherUserProfilePage() {
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if (strpos($url, self::$showProfile) !== false) {
            return true;
        } else {
            return false;
        }
    }



}