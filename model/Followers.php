<?php

namespace model;

/**
 * Class Followers
 * This class takes care of followers and followees
 * @package model
 */
class Followers {

    /**
     * Adds a user as a follower to another user who is the followee (a person who is being followed)
     * @param $follower, this user will follow
     * @param $followee, this user will be followed
     * @return bool
     */
    public function addFollower($follower, $followee) {
        try {
            DB::getInstance()->addFollowerToDB($follower, $followee);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Returns an array of users who are the followees of the currently logged in follower
     * @param $follower
     * @return mixed
     */
    public function getFollowees(\model\User $follower) {

        // Username of our currently logged in user
        $loggedInUsername = $follower->getUsername();

        // An array which will contain all followees
        $followeesArray = array();

        // Get an array of all users
        $allUsers = new UserArray();
        $allUsers->generateArray();
        $usersList = $allUsers->getUsers();

        try {
            $followersTable = DB::getInstance()->getFolloweesList(); // Get list of followers and followees

            //var_dump($followersTable);

            foreach ($followersTable as $oneRow) { // For each row

                $x = 0;
                $usernameFollower = "";
                $usernameFollowee = "";

                foreach ($oneRow as $value) {
                    if ($x === 1) {
                        $usernameFollower = $value;
                    } elseif($x === 2) {
                        $usernameFollowee = $value;
                    }
                    $x++;
                }

                if($usernameFollower === $loggedInUsername) {

                    foreach ($usersList as $oneUser) {

                        if ($usernameFollowee === $oneUser->getUsername()) {

                            $followeesArray[] = $oneUser;

                        }
                    }
                }
            }
            //print_r($followeesArray);
            return $followeesArray;
        } catch (\Exception $e) { // Catch exception
            return false;
        }
    }

    /**
     * Used when the person who is currently logged in wants to stop following another user
     * @param $follower
     * @param $followee
     * @return bool
     */
    public function removeFollowee($follower, $followee) {
        try {
            DB::getInstance()->deleteFollowee($follower, $followee);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}