<?php

class User {
    public static $TOKEN = "ca9a770912015f2ac85a8791d12d02a1757c1e3f";

    public $first_name = null;
    public $last_name = null;
    public $middle_name = null;
    public $email = null;
    public $phone = null;

    /**
     * This function prints all User info
     * @param $id
     * @return null
     */
    public function user_info($id) {

        $sql = "SELECT * FROM users WHERE user_id = '".$id."'";
        $stmt = (new MyDB)->get()->query($sql);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'User');
        $row = $stmt->fetch();
        foreach ($row as $key => $val) {
            print_r($key . ": " . $val . "<br>\n");
        }
        return "User info successfully printed";

    }

    /**
     * This func returns current user's ID
     * @return mixed
     */
    public function getUserId() {
        // get current user_id based on session token
        $sql = "SELECT users.user_id FROM users LEFT JOIN sessions ON users.user_id = sessions.user_id WHERE token = '".$this->TOKEN."'";
        $stmt = (new MyDB)->get()->query($sql);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'User');
        $row = $stmt->fetch();
        return $row['user_id'];
    }



}