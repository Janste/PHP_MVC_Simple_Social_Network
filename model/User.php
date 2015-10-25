<?php

namespace model;

/**
 * This class represents one user.
 * This class contains only getter and setter methods.
 */
class User {

    private $username;
    private $password;
    private $firstName;
    private $lastName;
    private $emailAddress;
    private $description;

    /**
     * Get username for this user
     * @return $username
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Sets a new username for this user
     * @param $username
     */
    public function setUsername($username){
        $this->username = $username;
    }

    /**
     * Get password belonging to this user
     * @return $password
     */
    public function getPassword(){
        return $this->password;
    }

    /**
     * Sets a new password for this user
     * @param $password
     */
    public function setPassword($password){
        $this->password = $password;
    }

    /**
     * Get first name
     */
    public function getFirstName(){
        return $this->firstName;
    }

    /**
     * Set first name
     */
    public function setFirstName($firstName){
        $this->firstName = $firstName;
    }

    /**
     * Get last name
     */
    public function getLastName(){
        return $this->lastName;
    }

    /**
     * Set last name
     */
    public function setLastName($lastName){
        $this->lastName = $lastName;
    }

    /**
     * Get email address
     */
    public function getEmailAddress(){
        return $this->emailAddress;
    }

    /**
     * Set email address
     */
    public function setEmailAddress($emailAddress){
        $this->emailAddress = $emailAddress;
    }
    /**
     * Get description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


}