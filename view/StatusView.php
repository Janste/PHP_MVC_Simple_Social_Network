<?php

namespace view;

class StatusView {

    // A status list
    private $statusList = array();

    // Field names
    private static $status = 'StatusView::Status';
    private static $submit = 'StatusView::Submit';
    private static $cookieSessionMessage = 'StatusView::CookieSessionMessage';

    private static $statusUrl = 'status';

    /**
     * Return the URL ending of this web page
     * @return string
     */
    public function getStatusUrl() {
        return self::$statusUrl;
    }

    /**
     * Checks if user is currently on this status web page
     * @return bool
     */
    public function isOnViewStatusPage() {
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if (strpos($url, $this->getStatusUrl()) !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns a string which contains a text area where user can insert their status
     * @return string
     */
    public function showInputBox() {

        $resultString = '';

        $resultString .= '<form method="post">';
        $resultString .= '<p>'  . $this->getSessionMessage() .  '</p>';
        $resultString .= '<textarea rows="4" cols="50" name ="' . self::$status . '">';
        $resultString .= '</textarea>';
        $resultString .= '<br />';
        $resultString .= '<input type="submit" name="' . self::$submit . '" value="Submit" />';
        $resultString .= '</form>';
        $resultString .= '<br />';

        return $resultString;

    }

    /**
     * Returns a string which contains the contents of this status web page
     * @return string
     */
    public function showStatusPage() {

        return $this->showInputBox() . $this->showStatusList() . '<br />';

    }

    /**
     * Returns the status that the user recently submitted
     * @return mixed
     */
    public function getNewStatus() {
        return $_POST[self::$status];
    }

    /**
     * Check if submit status button clicked
     * @return bool
     */
    public function checkIfSubmitStatusButtonClicked() {
        if(isset($_POST[self::$submit])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sets the status list
     * @param array $statusArray
     */
    public function setStatusList($statusArray = array()) {

        $this->statusList = $statusArray;

    }

    /**
     * Shows a list of every status that belongs to the user or to its followees
     * @return string
     * @throws \Exception
     */
    public function showStatusList() {

        if (is_null($this->statusList)) {
            throw new \Exception("Error when reading status list");
        }

        $resultString = "";

        foreach($this->statusList as $status) {
            $resultString .= '<br />';
            $resultString .= '<b>' . $status->getAuthor() . ' wrote on ' . $status->getDate() . ':</b><br />';
            $resultString .= $status->getDescription();
            $resultString .= '<br />';
        }

        return $resultString;

    }

    /**
     * Redirects to the same page
     */
    public function redirect($messageType) {

        if($messageType === true) {
            $message = "Your status was updated.";
        } else {
            $message = "An error occurred.";
        }

        $this->setMessage($message);

        $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

        header("HTTP/1.1 302 Found");
        header("Location: $actual_link?" . $this->getStatusUrl());

    }

    /**
     * Sets the message that will be later displayed to the user.
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

}