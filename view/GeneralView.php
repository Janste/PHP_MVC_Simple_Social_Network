<?php

namespace view;

require_once('ProfileView.php');
require_once('LoginView.php');
require_once('DateTimeView.php');
require_once('RegisterView.php');
require_once('OtherUsersView.php');
require_once('StatusView.php');

/**
 * Class GeneralView
 * This class represents a general view and layout
 */

class GeneralView {

    private $lv;
    private $rv;
    private $dtv;
    private $pv;
    private $ouv;
    private $sv;

    private static $toRegister = 'register';
    private static $toEditProfile = 'edit_profile';
    private static $toViewProfile = 'view_profile';
    private static $toViewOtherUsers = 'view_users_list';
    private static $toStatus = 'status';

    public function __construct(LoginView $loginView, RegisterView $registerView,
                                DateTimeView $dateTimeView, ProfileView $profileView, OtherUsersView $otherUsersView,
                                StatusView $statusView) {
        $this->lv = $loginView;
        $this->rv = $registerView;
        $this->dtv = $dateTimeView;
        $this->pv = $profileView;
        $this->ouv = $otherUsersView;
        $this->sv = $statusView;

    }

    /**
     * A method which echoes an error message saying that a problem with the DB had occurred.
     */
    public function showDatabaseErrorMessage() {
        echo 'A problem with the database occurred. Please try again later.';
    }

    /**
     * Returns the user's client information, like ip address
     * @return \model\UserClient
     */
    public function getUserClient() {
        return new \model\UserClient($_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]);
    }

    /**
     * This method displays a general view for the web page
     * @param $isLoggedIn, says of user is logged in or not
     * @return void, but writes to standard output!
     */
    public function render($isLoggedIn) {

    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>

          <h1>Simple Social Network</h1>

          ' . $this->showRegisterReturnLink($isLoggedIn) . '

          ' . $this->renderIsLoggedIn($isLoggedIn) . '



          <div class="container">
              ' . $this->showProperForm() . '

              ' . $this->showPageForLoggedInUser($isLoggedIn) . '

              ' . $this->dtv->show() . '
          </div>
         </body>
      </html>
    ';
    }

    /**
     * This method returns a form. What kind of form it returns depends on what web page we are on.
     * If user is on register page it will return register form. If user is on login page this method will
     * return login form.
     * @return string
     */
    private function showProperForm() {
        if($this->isOnRegisterPage()) {
            return $this->rv->generateRegisterForm();
        } else {
            return $this->lv->response();
        }
    }

    /**
     * Shows page content for the user.
     * This method makes calls to other methods which display menu, page content, etc.
     * @param $isLoggedIn
     * @return string
     */
    public function showPageForLoggedInUser($isLoggedIn) {
        if ($isLoggedIn) {
            return $this->showMainMenu() . $this->showProperPage();
        } else {
            return '';
        }
    }

    /**
     * Shows main navigation menu for th user
     * @return string
     */
    public function showMainMenu() {
        return '
            <ul>
                <li>
                    <a href="?' . self::$toEditProfile . '">Edit profile</a>
                </li>
                <li>
                    <a href="?' . self::$toViewProfile . '">View profile</a>
                </li>
                <li>
                    <a href="?' . self::$toViewOtherUsers . '">View Users</a>
                </li>
                <li>
                    <a href="?' . self::$toStatus . '">View Status</a>
                </li>
            </ul>
        ';
    }

    /**
     * Shows page content, depending on what user chose inside the menu bar
     * @return string
     */
    public function showProperPage() {

        if ($this->isOnViewProfilePage()) {
            return $this->pv->showViewProfile();
        }
        elseif ($this->isOnEditProfilePage()) {
            return $this->pv->showEditProfile();
        }
        elseif ($this->isOnViewUsersListPage()) {
            return $this->ouv->showListOfAllUsers();
        }
        elseif ($this->ouv->isOnViewAnotherUserProfilePage()) {
            return $this->ouv->showSpecifiedUser();
        }
        elseif($this->isOnViewStatusPage()) {
            return $this->sv->showStatusPage();
        }
        else {
            return '';
        }

    }

    /**
     * Depending on what web page we are on, this method return a link to register form or to main web page.
     * @return string
     */
    private function showRegisterReturnLink($isLoggedIn) {
        if (!$isLoggedIn) {
            if($this->isOnRegisterPage()) {
                return '<a href="?">Back to login</a>';
            } else {
                return '<a href="?' . self::$toRegister . '">Register a new user</a>';
            }
        } else {
            return '';
        }
    }

    /**
     * Checks if we are currently on register page or on normal page
     * @return bool
     */
    public function isOnRegisterPage() {
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if (strpos($url, self::$toRegister) !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if user is currently on edit profile
     * @return bool
     */
    public function isOnEditProfilePage() {
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if (strpos($url, self::$toEditProfile) !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if user is currently on view profile
     * @return bool
     */
    public function isOnViewProfilePage() {
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if (strpos($url, self::$toViewProfile) !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if user is on a webpage where he can see a list of other people's profiles
     * @return bool
     */
    public function isOnViewUsersListPage() {
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if (strpos($url, self::$toViewOtherUsers) !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function isOnViewStatusPage() {
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if (strpos($url, self::$toStatus) !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Displayed a text that says if user is logged in or not
     * @param $isLoggedIn, can be true or false
     * @return string, with h2 text telling if user is logged in or not
     */
    private function renderIsLoggedIn($isLoggedIn) {
        if ($isLoggedIn) {
            return '<h2>Logged in</h2>';
        } else {
            return '<h2>Not logged in</h2>';
        }
    }
}
