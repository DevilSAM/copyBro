<?php

class UserController {

    /**
     * This function find out current's user ID and calls user_info() function
     * @return null
     */
    public function userGet(){

        $sql = "SELECT * FROM users LEFT JOIN sessions ON users.user_id = sessions.user_id WHERE token = '".USER::$TOKEN."'";
        $stmt = (new MyDB)->get()->query($sql);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'User');
        $row = $stmt->fetch();
        $uid = $row->user_id;
        return (new User())->user_info($uid);

    }

    /**
     * This function updates User
     * @return null
     */
    public function userUpdate() {

        // get current user_id based on session token
        $sql = "SELECT * FROM users LEFT JOIN sessions ON users.user_id = sessions.user_id WHERE token = '".USER::$TOKEN."'";
        $stmt = (new MyDB)->get()->query($sql);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'User');
        $row = $stmt->fetch();
        $uid = $row->user_id;

        // make vars from request
        if(!isset($_POST)) {
            return "bad request";
        } else {
            $first_name = !empty($_POST["first_name"]) ? $_POST["first_name"] : "";
            $last_name = !empty($_POST["last_name"]) ? $_POST["last_name"] : "";
            $middle_name = !empty($_POST["middle_name"]) ? $_POST["middle_name"] : "";
            $email = !empty($_POST["email"]) ? strtolower($_POST["email"]) : "";
            $phone = !empty($_POST["phone"]) ? $this->clearPhone($_POST["phone"]) : "";
        }

        if (!isset($_POST) && !empty($_POST)) {
            return "Error: empty request not allowed.";
        }
        if (empty($first_name) || empty($last_name) || empty($phone)) {
            return "Error: first_name, last_name and phone can't be empty!";
        }

        // HERE WE perform staff with DB
        $sql = "UPDATE users SET first_name = ?, last_name = ?, phone = ?";
        $values_arr = [$first_name, $last_name, $phone];

        if ($middle_name) {
            $sql .= ", middle_name = ?";
            $values_arr[] = $middle_name;
        }
        if ($email) {
            $sql .= ", email = ?";
            $values_arr[] = $email;
        }
        // we need to update UPDATED field
        $sql .= ", updated = ?";
        $values_arr[] = time();

        $stmt = (new MyDB)->get()->prepare($sql . " WHERE user_id=".$uid);
        $stmt->execute($values_arr);

        if ($stmt->rowCount() > 0) {
            print_r("User successfully updated /n");
        } else {
            print_r("User not found /n");
        }

        return "userUpdate finished";

    }


    /**
     * this function clears the phone number and verify it
     * @param $phone
     * @return string|null
     */
    public function clearPhone($phone) {
        // now we take numbers only
        $pattern = '/[^0-9]/i';
        $clean = preg_replace($pattern, '', $phone);

        // possible cases: 89991112233 and 79991112233
        if (strlen($clean) == 11) {
            return $clean[0] == 8 ?  "7".substr($clean, 1) : $clean;
        }

        // also the user may sent 9991112233
        if (strlen($clean) == 10)
            return "7" . $clean;

        // not 11 digits
        return "";
    }
}





class NotificationsController {

    /**
     * Gets all notifications from DB
     *
     */
    public function notificationsGet() {

        // get current user_id based on session token
        $sql = "SELECT * FROM users LEFT JOIN sessions ON users.user_id = sessions.user_id WHERE token = '".USER::$TOKEN."'";
        $stmt = (new MyDB)->get()->query($sql);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'User');
        $row = $stmt->fetch();
        $uid = $row->user_id;

        // get all notifies from current user
        $unreaded = isset($_GET['unreaded']) ? "viewed = 0" : "true";
        $sql = "SELECT title, description, viewed, created FROM user_notifications WHERE user_id = '".$uid."' AND " .$unreaded;
        $res = (new MyDB)->get()->query($sql);
        while ($row = $res->fetch())
        {
            foreach ($row as $key => $val) {
                echo $val . "<br> \n";
            }
        }

        return  "All notifications printed";
    }

    /**
     * This func will mark all notifies as read
     */
    public function readAllNotifications(){

        // get current user_id based on session token
        $sql = "SELECT * FROM users LEFT JOIN sessions ON users.user_id = sessions.user_id WHERE token = '".USER::$TOKEN."'";
        $stmt = (new MyDB)->get()->query($sql);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'User');
        $row = $stmt->fetch();
        $uid = $row->user_id;

        // update values in the table user_notifications
        $sql = "UPDATE user_notifications SET viewed = 1 WHERE user_id = ?";
        (new MyDB)->get()->prepare($sql)->execute([$uid]);

        return "Notifications was READ";
    }

}