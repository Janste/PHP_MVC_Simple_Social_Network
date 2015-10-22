<?php

namespace view;

require_once('ProfileView.php');
require_once('LoginView.php');
require_once('DateTimeView.php');
require_once('RegisterView.php');

/**
 * Class GeneralView
 * This class represents a general overview of the whole view
 */

class GeneralView {

    private $lv;
    private $rv;
    private $dtv;
    private $profileView;

    private static $toRegister = 'register';
    private static $toEditProfile = 'edit_profile';
    private static $toViewProfile = 'view_profile';

    /**
     * Constructor
     */
    public function __construct() {
        $this->lv = new \view\LoginView();
        $this->rv = new \view\RegisterView();
        $this->dtv = new \view\DateTimeView();
        $this->profileView = new \view\ProfileView();
    }

    /**
     * A method which return the LoginView class, so that its methods can be used inside controller.
     * @return LoginView
     */
    public function getLoginView() {
        return $this->lv;
    }

    /**
     * A method which return the RegisterView class, so that its methods can be used inside controller.
     * @return RegisterView
     */
    public function getRegisterView() {
        return $this->rv;
    }

    public function getProfileView() {
        return $this->profileView;
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
            </ul>
        ';
    }

    /**
     * Shows page content, depending on what user chose inside the menu bar
     * @return string
     */
    public function showProperPage() {

        if ($this->isOnViewProfilePage()) {
            return $this->profileView->showViewProfile();
        }
        elseif ($this->isOnEditProfilePage()) {
            return $this->profileView->showEditProfile("");
        } else {
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
