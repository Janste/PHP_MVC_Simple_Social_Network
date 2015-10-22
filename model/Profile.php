<?php

namespace model;

class Profile {

    private $loggedInUser;

    public function __construct(User $currentUser) {
        $this->loggedInUser = $currentUser;
    }

    public function updateUser($firstName, $lastName, $emailAddress, $newPassword, $repeatNewPassword) {

        if(!empty($firstName) && $this->isValidString($firstName)) {
            return false;
        }
        elseif (!empty($lastName) && $this->isValidString($lastName)) {
            return false;
        }
        elseif (!empty($emailAddress) && $this->isValidEmail($emailAddress)) {
            return false;
        }
        elseif (!empty($newPassword) && !empty($repeatNewPassword) && (strlen($newPassword) > 5) && ($newPassword === $repeatNewPassword) && $this->isValidString($newPassword)) {
            return false;
        } else {

            try {

                $this->updateFirstName($firstName);
                $this->updateLastName($lastName);
                $this->updateEmail($emailAddress);
                $this->updatePassword($emailAddress);
                return true;

            } catch (\Exception $e) {
                return false;
            }

        }
    }

    public function updateFirstName($firstName) {
        DB::getInstance()->updateRecord("users", "firstname", $this->loggedInUser->getUsername(), $firstName);
    }

    public function updateLastName($lastName) {
        DB::getInstance()->updateRecord("users", "lastname", $this->loggedInUser->getUsername(), $lastName);
    }

    public function updateEmail($email) {
        DB::getInstance()->updateRecord("users", "email", $this->loggedInUser->getUsername(), $email);
    }

    public function updatePassword($pwd) {
        DB::getInstance()->updateRecord("users", "password", $this->loggedInUser->getUsername(), $pwd);
    }

    /**
     * Checks if the given string contains only valid characters
     */
    private function isValidString($stringToCheck) {
        if (preg_match("/[^A-Za-z0-9]/", $stringToCheck)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if the given email is written in a valid format
     */
    private function isValidEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }



}