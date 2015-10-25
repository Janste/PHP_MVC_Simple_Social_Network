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
 * This class represents a general view, layout, menu, etc...
 */

class GeneralView {

    private $lv;
    private $rv;
    private $dtv;
    private $pv;
    private $ouv;
    private $sv;

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
          <title>Simple Social Network</title>
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
        if($this->rv->isOnRegisterPage()) {
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
                    <a href="?' . $this->pv->getEditProfileUrl() . '">Edit profile</a>
                </li>
                <li>
                    <a href="?' . $this->pv->getViewProfileUrl() . '">View profile</a>
                </li>
                <li>
                    <a href="?' . $this->ouv->getViewOtherUsersUrl() . '">View Users</a>
                </li>
                <li>
                    <a href="?' . $this->sv->getStatusUrl() . '">View Status</a>
                </li>
            </ul>
        ';
    }

    /**
     * Shows page content, depending on what user chose inside the menu bar
     * @return string
     */
    public function showProperPage() {

        if ($this->pv->isOnViewProfilePage()) {
            return $this->pv->showViewProfile();
        }
        elseif ($this->pv->isOnEditProfilePage()) {
            return $this->pv->showEditProfile();
        }
        elseif ($this->ouv->isOnViewUsersListPage()) {
            return $this->ouv->showListOfAllUsers();
        }
        elseif ($this->ouv->isOnViewAnotherUserProfilePage()) {
            return $this->ouv->showSpecifiedUser();
        }
        elseif($this->sv->isOnViewStatusPage()) {
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
            if($this->rv->isOnRegisterPage()) {
                return '<a href="?">Back to login</a>';
            } else {
                return '<a href="?' . $this->rv->getRegisterUrl() . '">Register a new user</a>';
            }
        } else {
            return '';
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
