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
     * @param User $user, this user will follow
     * @param $followee, this user will be followed
     * @return bool
     */
    public function addFollower(\model\User $user, $followee) {

        $follower = $user->getUsername();

        try {
            DB::getInstance()->addFollowerToDB($follower, $followee);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Returns an array which contains users who are the followees of the currently logged in follower
     * @param $follower
     * @param $usersList
     * @return mixed, an array containing followees or false
     */
    public function getFollowees(\model\User $follower, $usersList = array()) {

        // Username of our currently logged in user
        $loggedInUsername = $follower->getUsername();

        // An array which will contain all followees
        $followeesArray = array();

        try {
            $followersTable = DB::getInstance()->getFolloweesList(); // Get list of followers and followees

            foreach ($followersTable as $oneRow) { // For each row

                $x = 0;
                $usernameFollower = "";
                $usernameFollowee = "";

                // Loop over cells inside this current row in order to set proper values for follower and followee
                foreach ($oneRow as $value) {
                    if ($x === 1) {
                        $usernameFollower = $value;
                    } elseif($x === 2) {
                        $usernameFollowee = $value;
                    }
                    $x++;
                }

                // If the username of the follower is the same as the username of the person who is currently logged in
                if($usernameFollower === $loggedInUsername) {

                    // Then loop through the array of all users
                    foreach ($usersList as $oneUser) {

                        // and find a user which has the same username as the followee
                        if ($usernameFollowee === $oneUser->getUsername()) {

                            // When found, add to followees array
                            $followeesArray[] = $oneUser;

                        }
                    }
                }
            }
            return $followeesArray;
        } catch (\Exception $e) { // Catch exception
            return false;
        }
    }

    /**
     * Used when the person who is currently logged in wants to stop following another user
     * @param User $user, person who is already following
     * @param $followee, a person who is being followed
     * @return bool
     */
    public function removeFollowee(\model\User $user, $followee) {

        $follower = $user->getUsername();

        try {
            DB::getInstance()->deleteFollowee($follower, $followee);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}