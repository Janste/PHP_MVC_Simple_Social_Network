<?php

namespace model;

require_once('Status.php');

class StatusHandler {

    public function addNewStatus(\model\User $user, $content) {

        if (strlen($content) > 255) {
            return false;
        }

        try {
            $username = $user->getUsername();
            DB::getInstance()->addStatusToDB($username, $content);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getStatusArray(\model\User $user, $usersArray) {

        $followers = new \model\Followers();

        $followeesArray = $followers->getFollowees($user, $usersArray);

        try {

            $statusArray = array();

            $array = DB::getInstance()->getStatusList(); // Connect to DB and get users

            foreach ($array as $oneStatusRow) { // For each row that represents one user
                $status = new Status();
                $x = 0; // Counter
                foreach ($oneStatusRow as $statusCell) { // Set user data. Each cell in the row represents user's data
                    if ($x == 1) {
                        $status->setAuthor($statusCell);
                    } elseif ($x == 2) {
                        $status->setDate($statusCell);
                    } elseif ($x == 3) {
                        $status->setDescription($statusCell);
                    }
                    $x++;
                }

                if ($status->getAuthor() === $user->getUsername()) {
                    $status->setAuthor($user->getFirstName() . ' ' . $user->getLastName());
                    $statusArray[] = $status;
                } else {

                    foreach ($followeesArray as $followee) {

                        if($status->getAuthor() === $followee->getUsername()) {
                            $status->setAuthor($followee->getFirstName() . ' ' . $followee->getLastName());
                            $statusArray[] = $status;
                        }
                    }
                }
            }

            // Reverse this status array so that users can see it from the newest to the oldest
            $reversedArray = array_reverse($statusArray);

            return $reversedArray;
        } catch (\Exception $e) { // Catch exception
            return false;
        }

    }

}