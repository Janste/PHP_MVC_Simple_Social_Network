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
            DB::getInstance()->addFollower($follower, $followee);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Returns a string array. That array contains usernames of those people who the currently logged in user wants
     * to follow (his or hers followees)
     * @param $follower
     * @return mixed
     */
    public function getFollowees($follower) {
        return DB::getInstance()->getFollowees($follower);
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