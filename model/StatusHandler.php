<?php

namespace model;

require_once('Status.php');

class StatusHandler {

    public function addNewStatus(\model\User $user, $content) {

        $username = $user->getUsername();

        DB::getInstance()->addStatus($username, $content);

    }

    public function getStatusArray() {

        $followers = new \model\Followers();

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

                $followersArray = $followers->getFollowees('Admin');

                if ($status->getAuthor() === 'Admin' || in_array($status->getAuthor(), $followersArray)) {
                    $statusArray[] = $status;
                }


            }
            return $statusArray;
        } catch (\Exception $e) { // Catch exception
            return false;
        }

    }

}