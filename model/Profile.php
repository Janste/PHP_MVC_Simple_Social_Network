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
    public function updateUser($firstName, $lastName, $emailAddress, $description, $newPassword, $repeatNewPassword) {

        if(!empty($firstName)) {

            // Check if input valid
            if ($this->isValidString($firstName) && (strlen($firstName) < 70)) {
                try {
                    $this->updateFirstName($firstName);
                    $this->loggedInUser->setFirstName($firstName);
                } catch (\Exception $e) {
                    return false;
                }
            } else {
                return false;
            }


        }

        if (!empty($lastName)) {

            // Check if input valid
            if($this->isValidString($lastName) && (strlen($lastName) < 70)) {
                try {
                    $this->updateLastName($lastName);
                    $this->loggedInUser->setLastName($lastName);
                } catch (\Exception $e) {
                    return false;
                }
            } else {
                return false;
            }


        }

        if (!empty($emailAddress)) {

            // Check if input valid
            if($this->isValidEmail($emailAddress) && (strlen($emailAddress) < 70)) {
                try {
                    $this->updateEmail($emailAddress);
                    $this->loggedInUser->setEmailAddress($emailAddress);
                } catch (\Exception $e) {
                    return false;
                }
            } else {
                return false;
            }


        }

        if (!empty($description)) {

            // Check if input valid
            if((strlen($description) < 255)) {
                try {
                    $this->updateDescription($description);
                    $this->loggedInUser->setDescription($description);
                } catch (\Exception $e) {
                    return false;
                }
            } else {
                return false;
            }


        }

        if (!empty($newPassword) && !empty($repeatNewPassword)) {

            // Check if input valid
            if((strlen($newPassword) > 5) && (strlen($newPassword) < 70) && ($newPassword === $repeatNewPassword) && $this->isValidString($newPassword)) {
                try {
                    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); // hash the password
                    $this->updatePassword($hashedPassword);
                    $this->loggedInUser->setPassword($newPassword);
                } catch (\Exception $e) {
                    return false;
                }
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * Updates the first name in the DB of the currently logged in user
     * @param $firstName
     */
    public function updateFirstName($firstName) {
        DB::getInstance()->updateUser("firstname", $this->loggedInUser->getUsername(), $firstName);
    }

    /**
     * Updates the last name in the DB of the currently logged in user
     * @param $lastName
     */
    public function updateLastName($lastName) {
        DB::getInstance()->updateUser("lastname", $this->loggedInUser->getUsername(), $lastName);
    }

    /**
     * Updates the email in the DB of the currently logged in user
     * @param $email
     */
    public function updateEmail($email) {
        DB::getInstance()->updateUser("email", $this->loggedInUser->getUsername(), $email);
    }

    /**
     * Updates the description in the DB of the currently logged in user
     * @param $desc
     */
    public function updateDescription($desc) {
        DB::getInstance()->updateUser("description", $this->loggedInUser->getUsername(), $desc);
    }

    /**
     * Updates the password in the DB of the currently logged in user
     * @param $pwd
     */
    public function updatePassword($pwd) {
        DB::getInstance()->updateUser("password", $this->loggedInUser->getUsername(), $pwd);
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