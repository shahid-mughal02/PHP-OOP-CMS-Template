<?php

/**
 * Manage users data
 */
class User
{
    private $error = "";
    public function signup($POST)
    {
        $data = array();
        $db = Database::getInstance();
        $data['name']       = trim($POST['name']);
        $data['email']      = trim($POST['email']);
        $data['password']   = trim($POST['password']);
        $password2          = trim($POST['password2']);

        if (empty($data['name']) || !preg_match("/[a-zA-Z]+$/", $data['name'])) {
            $this->error .= "Please enter valid name <br>";
        }

        if (empty($data['email']) || !preg_match("/^[a-zA-Z_-]+@[a-zA-Z]+.[a-zA-Z]+$/", $data['email'])) {
            $this->error .= "Please enter valid email <br>";
        }

        if ($data['password'] !== $password2) {
            $this->error .= "Password do not match <br>";
        }
        if (strlen($data['password']) < 5) {
            $this->error .= "Password must be atleast 5 characters long <br>";
        }

        /**
         * Check if email already exists
         * 
         */
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $arr['email'] = $data['email'];
        $check = $db->read($sql, $arr);
        if (is_array($check)) {
            $this->error .= "Email is already in use. <br>";
        }

        $data['url_address'] = $this->get_random_string_max(60);
        /**
         * Check for url_address
         * 
         */
        $arr = false;   //reseting array as its already in use for email
        $sql = "SELECT * FROM users WHERE url_address = :url_address LIMIT 1";
        $arr['url_address'] = $data['url_address'];
        $check = $db->read($sql, $arr);
        if (is_array($check)) {
            $data['url_address'] = $this->get_random_string_max(60);
        }

        if ($this->error == "") {
            /**
             * Saving User data into the database
             * 
             */
            $data['rank'] = "customer";
            $data['date'] = date("Y-m-d H:i:s");
            $data['password'] = hash('sha1', $data['password']);

            $query = "INSERT INTO users (url_address, name, email, password, date, rank) VALUES (:url_address, :name, :email, :password, :date, :rank)";
            $result = $db->write($query, $data);
            if ($result) {
                header("Location: " . ROOT . "login");
                die;
            }
        }
        $_SESSION['error'] = $this->error;
        /**
         * Showing Errors without storing in the session
         * 
         */
        // if ($this->error != "") {
        //     echo $this->error;
        // }
    }

    public function login($POST)
    {
        $data = array();
        $db = Database::getInstance();
        $data['email']      = trim($POST['email']);
        $data['password']   = trim($POST['password']);

        if (empty($data['email']) || !preg_match("/^[a-zA-Z_-]+@[a-zA-Z]+.[a-zA-Z]+$/", $data['email'])) {
            $this->error .= "Please enter valid email <br>";
        }

        if (strlen($data['password']) < 5) {
            $this->error .= "Password must be atleast 5 characters long <br>";
        }

        if ($this->error == "") {
            /**
             * Checking User data inside the database
             * 
             */
            $data['password'] = hash('sha1', $data['password']);
            $sql = "SELECT * FROM users WHERE email = :email && password = :password LIMIT 1";
            $result = $db->read($sql, $data);
            if (is_array($result)) {
                // $_SESSION['user_url'] = $result[0]['url_address'];   //return an error as $result[0] used as object
                $_SESSION['user_url'] = $result[0]->url_address;
                header("Location: " . ROOT . "home");
                die;
            }
            $this->error .= "Wrong Email or Password <br>";
        }
        $_SESSION['error'] = $this->error;
    }

    public function get_user($url)
    {
    }

    public function set_user($url)
    {
    }

    private function get_random_string_max($length)
    {
        $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $text = "";
        $length = rand(4, $length);

        for ($i = 0; $i < $length; $i++) {
            $random = rand(0, 61);
            $text .= $array[$random];
        }
        return $text;
    }

    /**
     * Checking user logged in
     *
     */
    public function check_login($redirect = false, $allowed = array())
    {
        /**
         * Checking logged in user rank
         * 
         * fetching user rank from the database as it preferred
         */
        $db = Database::getInstance();

        if (count($allowed) > 0) {
            $arr['url'] = $_SESSION['user_url'];
            $query = "SELECT * FROM users WHERE url_address = :url LIMIT 1";
            $result = $db->read($query, $arr);

            if (is_array($result)) {
                $result = $result[0];
                /**
                 * If allowed value match with the rank of user then user can access the result
                 * Otherwise redirected to the login
                 */
                if (in_array($result->rank, $allowed)) {
                    return $result;
                }
            }
            /**
             * Redirecting if user rank is not admin
             * 
             * @return - Login Page
             */
            header("Location: " . ROOT . "login");
            die;
        } else {
            /**
             * Fetcing user info if user_url session is set and return into the views as an object from array i.e $result[0]
             */
            if (isset($_SESSION['user_url'])) {
                $arr = false; //reseting array as it already contains above condition data
                $arr['url'] = $_SESSION['user_url'];
                $query = "SELECT * FROM users WHERE url_address = :url LIMIT 1";
                $result = $db->read($query, $arr);

                if (is_array($result)) {
                    return $result[0];
                }
            }
            if ($redirect) {
                header("Location: " . ROOT . "login");
                die;
            }
        }
        return false;
    }
    /**
     * Logout user
     * 
     * @return -to the home page
     */
    public function logout()
    {
        if (isset($_SESSION['user_url'])) {
            unset($_SESSION['user_url']);
        }
        header("Location: " . ROOT . "home");
        die;
    }
}
