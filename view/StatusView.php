<?php

namespace view;

class StatusView {

    // A status list
    private $statusList = array();

    // Field names
    private static $status = 'StatusView::Status';
    private static $submit = 'StatusView::Submit';

    private static $statusUrl = 'status';

    public function getStatusUrl() {
        return self::$statusUrl;
    }

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

        return $this->showInputBox() . $this->showStatusList();

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

    public function setStatusList($statusArray = array()) {

        $this->statusList = $statusArray;

    }

    public function showStatusList() {

        $resultString = "";

        foreach($this->statusList as $status) {
            $resultString .= '<br />';
            $resultString .= $status->getAuthor() . ' wrote at ' . $status->getDate() . ':<br />';
            $resultString .= $status->getDescription();
            $resultString .= '<br />';
        }

        return $resultString;

    }

    /**
     * Redirects to the same page
     */
    public function redirect() {

        $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

        header("HTTP/1.1 302 Found");
        header("Location: $actual_link?" . $this->getStatusUrl());

    }

}