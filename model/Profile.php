<?php

namespace model;

class Profile {

    private $loggedInUser;

    public function __construct(User $currentUser) {
        $this->loggedInUser = $currentUser;
    }

    /**
     * Updates data for the currently logged in user
     * @param $firstName
     * @param $lastName
     * @param $emailAddress
     * @param $newPassword
     * @param $repeatNewPassword
     * @return bool
     */
    public function updateUser($firstName, $lastName, $emailAddress, $newPassword, $repeatNewPassword) {

        if(!empty($firstName) && $this->isValidString($firstName)) {
            try {
                $this->updateFirstName($firstName);
                $this->loggedInUser->setFirstName($firstName);
            } catch (\Exception $e) {
                return false;
            }
        }

        if (!empty($lastName) && $this->isValidString($lastName)) {
            try {
                $this->updateLastName($lastName);
                $this->loggedInUser->setLastName($lastName);
            } catch (\Exception $e) {
                return false;
            }
        }

        if (!empty($emailAddress) && $this->isValidEmail($emailAddress)) {
            try {
                $this->updateEmail($emailAddress);
                $this->loggedInUser->setEmailAddress($emailAddress);
            } catch (\Exception $e) {
                return false;
            }
        }

        if (!empty($newPassword) && !empty($repeatNewPassword) && (strlen($newPassword) > 5) && ($newPassword === $repeatNewPassword) && $this->isValidString($newPassword)) {
            try {
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); // hash the password
                $this->updatePassword($hashedPassword);
                $this->loggedInUser->setPassword($newPassword);
            } catch (\Exception $e) {
                return false;
            }
        }

        return true;

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
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checks if the given email is written in a valid format
     */
    private function isValidEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        } else {
            return true;
        }
    }



}