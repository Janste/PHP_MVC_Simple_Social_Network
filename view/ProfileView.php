<?php

namespace view;

class ProfileView {

    private static $message = 'ProfileView::Message';
    private static $firstName = 'ProfileView::FirstName';
    private static $lastName = 'ProfileView::LastName';
    private static $emailAddress = 'ProfileView::EmailAddress';
    private static $password = 'ProfileView::Password';
    private static $repeatPassword = 'ProfileView::RepeatedPassword';
    private static $saveChanges = 'ProfileView::SaveChanges';
    private static $cookieSessionMessage = 'RegisterView::CookieSessionMessage';

    private $user;

    /**
     * Get first name from the form.
     */
    public function getFirstName() {
        if(isset($_POST[self::$firstName])) {
            $firstName = $_POST[self::$firstName];
            return $firstName;
        } else {
            return false;
        }
    }

    /**
     * Get last name from the form.
     */
    public function getLastName() {
        if(isset($_POST[self::$lastName])) {
            $lastName = $_POST[self::$lastName];
            return $lastName;
        } else {
            return false;
        }
    }

    /**
     * Get email address name from the form.
     */
    public function getEmailAddress() {
        if(isset($_POST[self::$emailAddress])) {
            $emailAddress = $_POST[self::$emailAddress];
            return $emailAddress;
        } else {
            return false;
        }
    }

    /**
     * Gets the password from the form
     * @return string, containing password, false otherwise
     */
    public function getNewPassword() {
        if(isset($_POST[self::$password])) {
            $password = $_POST[self::$password];
            return $password;
        } else {
            return false;
        }
    }

    /**
     * Gets the repeated password from the form
     * @return string, which contains the repeated password, false otherwise
     */
    public function getNewRepeatedPassword() {
        if(isset($_POST[self::$repeatPassword])) {
            $password = $_POST[self::$repeatPassword];
            return $password;
        } else {
            return false;
        }
    }

    /**
     * Check if user clicked on save changes button
     * @return true or false
     */
    public function checkSaveChangesButtonClicked() {
        if(isset($_POST[self::$saveChanges])) {
            return true;
        } else {
            return false;
        }
    }

    public function setUser(\model\User $loggedInUser) {

        $this->user = $loggedInUser;

    }

    public function showViewProfile() {

        return '<p>Your profile information: </p>

        <p>Username: ' . $this->user->getUsername() . '</p>

        <p>First name: ' . $this->user->getFirstName() . '</p>

        <p>Last name: ' . $this->user->getLastName() . '</p>

        <p>Email address: ' . $this->user->getEmailAddress() . '</p>

        ';

    }

    /**
     * Redirects to the register web page and sets the message.
     */
    public function redirect($messageType) {

        $message = "";

        if($messageType === true) {
            $message = "Changes saved successfully";
        } else {
            $message = "Error. Use only valid characters.";
        }

        $this->setMessage($message);

        $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
        header("HTTP/1.1 302 Found");
        header("Location: $actual_link?edit_profile");

    }

    /**
     * Sets the message that will be later dispalyed to the user.
     * @param $message
     */
    private function setMessage($message) {
        setcookie(self::$cookieSessionMessage, $message, 0 , "/");
    }

    /**
     * If there is a message to the user that should be shown, then this method will returns such message.
     * @return string
     */
    private function getSessionMessage() {

        if(isset($_COOKIE[self::$cookieSessionMessage])) {
            $msg = $_COOKIE[self::$cookieSessionMessage];
            setcookie(self::$cookieSessionMessage, "", time() - 1000 , "/");
            return $msg;
        } else {
            return "";
        }

    }

    public function showEditProfile() {

        return '
        <form method="post" >
				<fieldset>
					<legend>Edit your profile</legend>
					<p id="' . self::$message . '">'  . $this->getSessionMessage() .  '</p>

					<label for="' . self::$firstName . '">First name:</label>
					<input type="text" id="' . self::$firstName . '" name="' . self::$firstName . '" value="" />
                    <br/>

                    <label for="' . self::$lastName . '">Last name:</label>
					<input type="text" id="' . self::$lastName . '" name="' . self::$lastName . '" value="" />
                    <br/>

                    <label for="' . self::$emailAddress . '">Email address:</label>
					<input type="text" id="' . self::$emailAddress . '" name="' . self::$emailAddress . '" value="" />
                    <br/>

					<label for="' . self::$password . '">New password:</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />
                    <br/>
                    <label for="' . self::$repeatPassword . '">New password repeat:</label>
					<input type="password" id="' . self::$repeatPassword . '" name="' . self::$repeatPassword . '" />
                    <br/>


					<input type="submit" name="' . self::$saveChanges . '" value="Save changes" />
				</fieldset>
			</form>
        ';

    }



}